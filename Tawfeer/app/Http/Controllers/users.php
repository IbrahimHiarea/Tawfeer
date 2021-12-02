<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

class users extends Controller
{
    public function register(Request $request){
        //add new User
        $product = new User();

        $product->name = $request->input('name');
        $product->email = $request->input('email');
        $product->password = $request->input('password');
        $product->phone = $request->input('phone');
        $product->imgUrl = $request->input('imgUrl');

        $product->save();

        return response()->json(['message' => 'Welcome :)'],200);
        //Don't forget to return Token !!!
    }
}
