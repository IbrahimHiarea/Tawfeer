<?php

namespace App\Http\Controllers;



use http\Env\Response;
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
            return response()->json(['message' => 'The email has already been taken'],400);
        }
        //add new User
        $user = new User();
        $user->fullName = $request->input('fullName');
        $user->email = $request->input('email');
        $user->password =bcrypt($request->input('password'));
        $user->phoneNumber = $request->input('phoneNumber');

        $user->save();

        // Generate Token
        $token = auth()->attempt(['email' => $request->email , 'password' => $request->password]);

        return response()->json(['message' => 'Welcome :)' , 'token' => "bearer $token"],200);
    }

    public function login(Request $request){
        // make a conditions
        $valid = Validator::make($request->all() , [
            'email' => ['required' , 'email'],
            'passwords' => ['required'],
        ]);

        // verify user + Token
        if(!$token = auth()->attempt(['email' => $request->email , 'password' => $request->password])){
            return response()->json(['message' => 'Invalid email or password'] , 400);
        }

        return response()->json(['message' => 'Logged in successfully' , 'token' => "bearer $token"],200);
    }

    public function profile(){
        // Get the info of user
        $userData = auth()->user();

        return response()->json(['user info' => $userData],200);
    }

    public function logout(){
        // delete the Token
        auth()->logout();

        return response()->json(['message' => 'User logged out'],200);
    }

    public function checkToken(){
        return response()->json(['message' => 'Welcome'],200);
    }

    public function getUser($userId){
        if(!User::where('id',$userId)->exists())
            return response()->json(['message' => 'Invalid ID'],400);

        $user = User::find($userId);
        return response()->json(['user' => $user],200);
    }

    public function updatePhoto(Request $request){
        // make a conditions
        $valid = Validator::make($request->all() , [
            'img' => ['mimes:jpg,png,jpeg'],
        ]);
        //Handling The Errors
        if($valid->fails()  ||  (!$request->hasFile('img'))){
            return response()->json(['message' => 'Wrong form of image'],400);
        }

        $user = User::find(auth()->user()->id);

        //get the image
        $img = $request->file('img');
        //image Name
        $imgName = time() . '-' . $user->fullName . '.' . $request->file('img')->extension();
        //store the img in public folder
        $img->move(public_path('storage/app/public/img'),$imgName);
        $user->imgUrl = "storage/app/public/img/$imgName";
        $user->save();

        return response()->json(['message' => 'The photo has been updated'],200);
    }
}
