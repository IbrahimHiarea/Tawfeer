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
            'fullName' => ['required' , 'string' , 'max:25'] ,
            'email' => ['required' , 'string' , 'email' , 'max:50' , Rule::unique('users' , 'email')],
            'password' => ['required' , 'string' , 'min:7'],
            'phoneNumber' => ['required'],
        ]);
        //Handling The Errors
        if($valid->fails()){
            return $valid->errors()->all();
        }
        //add new User
        $user = new User();
        $user->fullName = $request->input('fullName');
        $user->email = $request->input('email');
        $user->password =bcrypt($request->input('password'));
        $user->phoneNumber = $request->input('phoneNumber');
        $user->imgUrl = $request->input('imgUrl');
        $user->save();

        // Generate Token
        $token = auth()->attempt(['email' => $request->email , 'password' => $request->password]);

        return response()->json(['message' => 'Welcome :)' , 'token' => $token],200);
    }

    public function login(Request $request){
        // make a conditions
        $valid = Validator::make($request->all() , [
            'email' => ['required' , 'email'],
            'phoneNumber' => ['required'],
        ]);

        // verify user + Token
        if(!$token = auth()->attempt(['email' => $request->email , 'password' => $request->password])){
            return response()->json(['message' => 'Invalid email or password'] , 400);
        }

        return response()->json(['message' => 'Logged in successfully' , 'token' => $token],200);
    }
}
