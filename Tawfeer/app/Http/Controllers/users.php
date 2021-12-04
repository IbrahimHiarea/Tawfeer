<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;



class users extends Controller
{
    public function register(Request $request){

        // make a conditions
        $valid = Validator::make($request->all() , [
            'name' => ['required' , 'string' , 'max:25'] ,
            'email' => ['required' , 'string' , 'email' , 'max:50' , 'unique:users'],
            'password' =>['required' , 'string' , 'min:7'],
            'phone' => ['required'],
        ]);

        //Handling The Errors
        if($valid->fails()){
            return $valid->errors()->all();
        }

        //add new User
        $user = new User();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->phone = $request->input('phone');
        $user->imgUrl = $request->input('imgUrl');

        $user->save();

        return response()->json(['message' => 'Welcome :)'],200);
        //Don't forget to return Token !!!
    }

    public function login(Request $request){

    }
}
