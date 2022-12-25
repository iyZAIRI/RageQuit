<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameUser;

class GameUserController extends Controller
{
    public function create(){
        //check if user is authenticated
        if(auth()->check()){
            //check if user has already added game to their list
            $gameUser = GameUser::where('user_id',auth()->user()->id)->where('game_id',request('game_id'))->first();
            if($gameUser){
                //if user has already added game to their list, delete it
                $gameUser->delete();
                return response()->json(['success'=>'Successfully removed game from your list']);
            }else{
                //if user has not added game to their list, add it
                GameUser::create([
                    'user_id' => auth()->user()->id,
                    'game_id' => request('game_id')
                ]);
                return response()->json(['success'=>'Successfully added game to your list']);
            }
        }
        return response()->json(['success'=>'User not logged in']);
    }
}