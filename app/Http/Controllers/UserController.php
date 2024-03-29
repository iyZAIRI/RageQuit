<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function login(Request $data)
    {
        $valdiator=Validator::make($data->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        if($valdiator->fails()){
            return redirect()->back()->withErrors($valdiator,'loginErrors');
        }
        if (Auth::attempt(['name' => $data['username'], 'password' => $data['password']], $data['remember'])) {
            return redirect()->back();
        }
        $error['invalid'] = 'Invalid username or password';
        return redirect()->back()->withErrors($error,'loginErrors');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request $data
     * @return \App\Models\User
     */
    protected function register(Request $data)
    {
        $validator=Validator::make($data->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator,'signupErrors');
        }
            $user=User::create([
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        Auth::login($user, $data['remember']);
        //stay on same page without reloading
        return redirect()->back();
    }
    public function profileEdit(Request $request){
        if(auth()->check()){
            $user=auth()->user();
            $username=$user->name;
            $email=$user->email;
            if($request->query('goto')){
                $avatar=true;
                return view('user.profile-edit',compact('username','email','avatar'));
            }
            return view('user.profile-edit',compact('username','email'));
        }
        return redirect()->route('home');
    }
    public function update(Request $request){
        if($request->input('password')){
            //validate password and check if old password matches current user password
            $validator=Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator,'passwordErrors');
            }
            $password=$request->input('password');
            $user=User::where('email',auth()->user()->email)->first();
            $user->password=Hash::make($password);
            $user->save();
            return redirect()->back()->with('success','Password updated successfully');
        }
        else if ($request->input('name')){
            $validator=Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:10'],
            ]);
            $user=User::where('email',auth()->user()->email)->first();
            if($validator->fails()){
                return redirect()->back()->withErrors($validator,'profileErrors');
            }
            $username=$request->input('name');
            $user->name=$username;
            $user->save();
            return redirect()->back()->with('success','Profile updated successfully');
        }
        else if ($request->input('email')){
            $validator=Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator,'emailErrors');
            }
            $user=User::where('email',auth()->user()->email)->first();
            $email=$request->input('email');
            $user->email=$email;
            $user->save();
            return redirect()->back()->with('success','Email updated successfully');
        }
        else if ($request->file('avatar')){
            $validator=Validator::make($request->all(), [
                'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ]);
            if($validator->fails()){
                return redirect()->route('user.profile-edit')->withErrors($validator,'avatarErrors');
            }
            $user=User::where('email',auth()->user()->email)->first();
            $avatar=$request->file('avatar');
            $avatarName=$user->id.'_avatar'.time().'.'.$avatar->extension();
            $avatar->move(public_path('images/avatars'),$avatarName);
            $user->avatar=$avatarName;
            $user->save();
            return redirect()->route('user.profile-edit')->with('success','Avatar updated successfully');
        }
        else{
            return redirect()->back();
        }

    }
}
