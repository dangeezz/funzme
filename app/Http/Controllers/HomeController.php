<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Posts;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

    	return Inertia::render('Dashboard',[ 

    		'user' => auth()->user()
	    ]);
    }

    public function userPage($user){

    	$user = User::whereUsername($user)->first();

    	return Inertia::render('Post/Profile',[ 

    		'tweets' => Posts::with('user')->where(function($query) use ($user) {
				            return $query->where('user_id', $user->id)
				            ->orWhereIn('user_id', $user->getFriends()->pluck('id'));
				        })            
				        ->latest()
				        ->get(),
			'profiler' => $user
	    ]);
    }
}
