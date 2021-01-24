<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /*
    * This Method To Get Posts 
    * ['For Admin'] Will Get All Posts
    * ['For User'] Will Get His Posts Only
    */
    public function index()
    {
        
        if (Gate::allows('is-Admin')) { // if User Is Admin You Will Get All
            $posts = Post::get();
            if(count($posts)<1){
                $posts='There Is No Posts';
            }
            return response()->json([
                'status'=>true,
                'posts'=>$posts
            ]);
        }else{ // If user Not Admin Will Get His Posts Only
            $posts = Post::where('user_id',Auth::id())->get();
            if(count($posts)<1){
                $posts='There Is No Posts';
            }
            return response()->json([
                'status'=>true,
                'posts'=>$posts
            ]);
        }
    
    }

    /*
    * This Method To Add Posts 
    */
    public function store(Request $request)
    {
        //Check If Fileds Is not Empty
        $validator = Validator::make($request->all(),[
            'title'=> 'required|max:50',
            'content'=> 'required|min:50',
        ],$messages =[
            'title.required' => 'We need to know your Title',
            'content.required' => 'We need to know your Content',
            'title.max' => 'You Must Make Title Les Than 100',
            'content.min' => 'You Must Make Content Greater Than 100',
        ]);
        // Get Errors
        $errors = $validator->errors();
        if($validator->fails()){ // View Errors
            return response()->json([
                'status'=>false,
                'msg'=>'Errorr',
                'errors'=>$errors
            ]);
        }
        // If There IS No Errors Will Add The Post
        Post::insert([
            'title' => $request->title,
            'content' => $request->content,
            'user_id'=>Auth::id()
        ]);
        // This To View True Message 
        return response()->json([
            'status'=>true,
            'msg'=>'Insert Done'
        ]);


    }

    /*
    * This Method To Update Posts 
    * ['For Admin'] He Can Update All Posts
    * ['For User'] He Can Update His Posts Only
    */
    public function update(Request $request, $id)
    {
        // Valedation
        $validator = Validator::make($request->all(),[
            'title'=> 'required|max:50',
            'content'=> 'required|min:50',
        ],$messages =[
            'title.required' => 'We need to know your Title',
            'content.required' => 'We need to know your Content',
            'title.max' => 'You Must Make Title Les Than 100',
            'content.min' => 'You Must Make Content Greater Than 100',
        ]);
        $errors = $validator->errors();
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'msg'=>'Errorr',
                'errors'=>$errors
            ]);
        }
        // Update
            if (Gate::allows('is-Admin')) { // If This Is Admin Will Update Any Post
                $update = Post::where('id',$request->id)->update([
                    'title' => $request->title,
                    'content' => $request->content,
                ]);
                if($update != 1){ // If ther Is No Id For Any Post 
                    return response()->json([
                    'status'=>false,
                    'msg'=>'There Is no Post To Edit'
                ]);
                }
                return response()->json([
                    'status'=>true,
                    'msg'=>'Updated Done By Admin'
                ]);
            }else{ // If This Is User Will Update His Post Only
                $update = Post::where([['id',$request->id],['user_id',Auth::id()]])->update([
                    'title' => $request->title,
                    'content' => $request->content,
                ]);
                if($update != 1){ // If ther Is No Id For Any Post 
                    return response()->json([
                    'status'=>false,
                    'msg'=>'You Don\' Have Access to Edit this Post'
                ]);
                }
                return response()->json([
                    'status'=>true,
                    'msg'=>'Updated Done'
                ]);
            }
        
    }
 
    /*
    * This Method To Delete Posts 
    * ['For Admin'] He Can Delete All Posts
    * ['For User'] He Can Delete His Posts Only
    */
    public function destroy($id)
    {
        if (Gate::allows('is-Admin') || Gate::allows('is-Moderator')) { //Check If This User Is Admin He Can Delete Any Post
            $delete = Post::where('id',$id)->delete();
            if($delete != 1){
                return response()->json([
                'status'=>false,
                'msg'=>'There Is no Posts To Delete It'
            ]);
        }
        return response()->json([
            'status'=>true,
            'msg'=>'Deleted Done'
        ]);
        }else{ //Check If This User Is Not Admin He Can Delete His Post Only
            $delete = Post::where([['id',$id],['user_id',Auth::id()]])->delete();
            if($delete != 1){
                return response()->json([
                'status'=>false,
                'msg'=>'You Don\' Have Access to Delete this Post'
            ]);
            }
            return response()->json([
                'status'=>true,
                'msg'=>'Deleted Done'
            ]);
        }
        
    }
}
