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

/**
     * @OA\Get(
     *      path="/api/user/posts",
     *      operationId="userPosts",
     *      tags={"Posts"},
     *      summary="Posts User System",
     *      description="Posts In User System",
     *      security={
     *         {"bearer": {}}
     *     },
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

            ],200);

        }else{ // If user Not Admin Will Get His Posts Only
            $posts = Post::where('user_id',Auth::id())->get();
            if(count($posts)<1){
                return response()->json([
                    'status'=>true,
                    'msg'=>'ther is no posts'
                ],404);
            }
            return response()->json([
                'status'=>true,
                'posts'=>$posts
            ],200);
        }
    
    }

    /*
    * This Method To Add Posts 
    */

/**
     * @OA\Post(
     *      path="/api/user/add-post",
     *      operationId="add Posts",
     *      tags={"Posts"},
     *      summary="Add Post",
     *      description="Add Posts In User System",
     *      security={
     *         {"bearer": {}}
     *     },
     * @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(mediaType="multipart/form-data",
     *        @OA\Schema(
     *            required={"title","content"},
     *            @OA\Property(
     *               property="title",
     *               type="string",
     *               description="you must add Title To Post"
     *             ),
     *             @OA\Property(
     *                property="content",
     *                type="string",
     *                description="you must add Content To Post"
     *             ),    
     *         )
     *      )
     *  ),
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
            ],404);
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
        ],200);


    }

    /*
    * This Method To Update Posts 
    * ['For Admin'] He Can Update All Posts
    * ['For User'] He Can Update His Posts Only
    */

    /**
     * @OA\Put(
     *      path="/api/user/edit-post/{id}",
     *      operationId="Edit Posts",
     *      tags={"Posts"},
     *      summary="Edit Post",
     *      description="Edit Posts In User System",
     *      security={
     *         {"bearer": {}}
     *     },
     * 
     *@OA\Parameter(
     *          name="id",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Edit Pots ",
      *    @OA\JsonContent(
     *       required={"title","content"},
     *       @OA\Property(property="title", type="string", format="title", example="This Is Post title"),
     *       @OA\Property(property="content", type="string", format="content", example="this is post content"),
     *    ),
     * ),
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
            ],422);
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
                    ],404);
                }
                return response()->json([
                    'status'=>true,
                    'msg'=>'Updated Done By Admin'
                ],200);
            }else{ // If This Is User Will Update His Post Only
                $update = Post::where([['id',$request->id],['user_id',Auth::id()]])->update([
                    'title' => $request->title,
                    'content' => $request->content,
                ]);
                if($update != 1){ // If ther Is No Id For Any Post 
                    return response()->json([
                    'status'=>false,
                    'msg'=>'You Don\' Have Access to Edit this Post'
                    ],404);
                }
                return response()->json([
                    'status'=>true,
                    'msg'=>'Updated Done'
                ],200);
            }
        
    }
 
    /*
    * This Method To Delete Posts 
    * ['For Admin'] He Can Delete All Posts
    * ['For User'] He Can Delete His Posts Only
    */
        /**
     * @OA\Delete(
     *      path="/api/user/delete-post/{id}",
     *      operationId="Delete Posts",
     *      tags={"Posts"},
     *      summary="Delete Post",
     *      description="Edit Posts In User System",
     *      security={
     *         {"bearer": {}}
     *     },
     * 
     *@OA\Parameter(
     *          name="id",
     *          description="Post id To Delete It",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
    public function destroy($id)
    {
        if (Gate::allows('is-Admin') || Gate::allows('is-Moderator')) { //Check If This User Is Admin He Can Delete Any Post
            $delete = Post::where('id',$id)->delete();
            if($delete != 1){
                return response()->json([
                'status'=>false,
                'msg'=>'There Is no Posts To Delete It'
                ],404);
        }
        return response()->json([
            'status'=>true,
            'msg'=>'Deleted Done'
        ],200);
        }else{ //Check If This User Is Not Admin He Can Delete His Post Only
            $delete = Post::where([['id',$id],['user_id',Auth::id()]])->delete();
            if($delete != 1){
                return response()->json([
                'status'=>false,
                'msg'=>'You Don\' Have Access to Delete this Post'
                ],404);
            }
            return response()->json([
                'status'=>true,
                'msg'=>'Deleted Done'
            ],200);
        }
        
    }
}
