<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function details($id){
        //get the first game with the id
        $game=Game::where('id',$id)->first();
        //get the game publishers to $gamePublishers
        $gamePublishers=$game->gamePublishers;
        //iterate over $gamePublishers and get the gamePlatforms    
        foreach($gamePublishers as $gamePublisher){
            $gamePlatforms[]=$gamePublisher->gamePlatforms;
        }
        //get the oldest release year of $gamePlatforms
        $releaseYear=$gamePlatforms[0][0]->release_year;
        return view('game.details',compact('game','gamePublishers','gamePlatforms','releaseYear'));
    }
    public function index(Request $request){
        $page=$request->query('page');
        $totalGames=Game::count();
        $games=Game::paginate(5,['*'],'page',$page);
        return view('game.index',compact('games','page','totalGames'));
    }

}