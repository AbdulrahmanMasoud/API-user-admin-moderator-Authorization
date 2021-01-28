<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /*
    * This Method To Login 
    * ['For Admin'] Will Get Token For It
    * ['For User'] Will Get Token For It
    */



/**
     * @OA\Post(
     *      path="/api/user/login",
     *      operationId="userLogin",
     *      tags={"User"},
     *      summary="Login In User System",
     *      description="Login In User System If U r Admin orn Mod Or User",
     * @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"email","password"},
     *                  
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="Sender Email"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="password",
     *                      description="Sender password"
     *                  ),
     *                  
     *             )
     *         )
     *      ),
     * 
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
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
            ],404);
        }

        if (Gate::allows('is-Admin')) { // If This User Is Admin
            $admin_token = Auth::attempt($credentials);
            return response()->json([
                'status'=>true,
                'msg'=>'You Are Login',
                "admin_token"=>$admin_token
            ],200);
        }elseif(Gate::allows('is-Moderator')){
            $moderator_token = Auth::attempt($credentials);
            return response()->json([
                'status'=>true,
                'msg'=>'You Are Login',
                "moderator_token"=>$moderator_token
            ],200);
        }
        else{ // If This User Is Not Admin
            $user_token = Auth::attempt($credentials);
            return response()->json([
                'status'=>true,
                'msg'=>'You Are Login',
                "user_token"=>$user_token
            ],200);
        }
    }

    /*
    * This Method To Regesteration 
    */

/**
     * @OA\Post(
     *      path="/api/user/register",
     *      operationId="userRegister",
     *      tags={"User"},
     *      summary="ٌRegister In User System",
     *      description="ٌRegister In User System",
     * @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name","email","password"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="you must add Name"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="you must add Email"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="password",
     *                      description="you must add password"
     *                  ),
     *                  
     *             )
     *         )
     *      ),
     * 
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
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


/**
     * @OA\Post(
     *      path="/api/user/logout",
     *      operationId="userLogout",
     *      tags={"User"},
     *      summary="ٌLogoutIn User System",
     *      description="ٌLogout In User System",
     *      security={{"bearer":{}}},
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
*/

    public function logout(){
  
        Auth::logout();
        return response()->json([
            'status'=>true,
            'msg'=>'You Are Logout',
        ]);
        
    }

}
