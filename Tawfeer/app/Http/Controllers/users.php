<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class users extends Controller
{
    public function register(Request $request){

        // make A conditions
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
}


/*public function update(Request $request,$id){
    if(User::where('id', $id)->exists()){
        $user = User::find($id);

        $user->fullName = !empty($request->input('fullName')) ? $request->input('fullName') : $user->fullName;
        $user->email = !empty($request->input('email')) ? $request->input('email') : $user->email;
        $user->password = !empty($request->input('password')) ? $request->input('password') : $user->password;
        $user->phoneNumber = !empty($request->input('phoneNumber')) ? $request->input('phoneNumber') : $user->phoneNumber;
        $user->imgUrl = !empty($request->input('imgUrl')) ? $request->input('imgUrl') : $user->imgUrl;
        $user->save();
        return response()-> json([
            'message'=>'Updated Successfully'
        ], 200);
    }else{
        return response([
            'status'=> 0,
            'message'=> 'User not found'
        ],404);
    }
}*/
