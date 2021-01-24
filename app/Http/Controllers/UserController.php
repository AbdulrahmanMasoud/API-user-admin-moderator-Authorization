<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /*
    * This Method To Login 
    * ['For Admin'] Will Get Token For It
    * ['For User'] Will Get Token For It
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=> 'required|email',
            'password'=> 'required',
        ],$messages =[
            'email' => 'Pleas Add Valid Email address',
            'email.required' => 'We need to know your email address!',
            'password.required' => 'We need to know your Password',
        ]);
        $errors = $validator->errors();
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'msg'=>'Errorr',
                'errors'=>$errors
            ]);
        }
        
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status'=>true,
                'msg'=>'Filed To Login Pleas Try Agin'
            ]);
        }

        if (Gate::allows('is-Admin')) { // If This User Is Admin
            $admin_token = Auth::attempt($credentials);
            return response()->json([
                'status'=>true,
                'msg'=>'You Are Login',
                "admin_token"=>$admin_token
            ]);
        }elseif(Gate::allows('is-Moderator')){
            $moderator_token = Auth::attempt($credentials);
            return response()->json([
                'status'=>true,
                'msg'=>'You Are Login',
                "moderator_token"=>$moderator_token
            ]);
        }
        else{ // If This User Is Not Admin
            $user_token = Auth::attempt($credentials);
            return response()->json([
                'status'=>true,
                'msg'=>'You Are Login',
                "user_token"=>$user_token
            ]);
        }
    }
    /*
    * This Method To Regesteration 
    */
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            'email'=> 'required|email',
            'password'=> 'required',
        ],$messages =[
            'email' => 'Pleas Add Valid Email address',
            'name.required' => 'We need to know your Name',
            'email.required' => 'We need to know your email address!',
            'password.required' => 'We need to know your Password',
        ]);
        $errors = $validator->errors();
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'msg'=>'Errorr',
                'errors'=>$errors
            ]);
        }
        User::insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);
        return response()->json([
            'status'=>true,
            'msg'=>'You Are Register',
        ]);
    }

    /*
    * This Method To Logout 
    */
    public function logout(){
        Auth::logout();
        return response()->json([
            'status'=>true,
            'msg'=>'You Are Logout',
        ]);
    }

}
