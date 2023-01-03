<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenteeResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Mentee;
use App\Models\Mentor;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MenteeController extends Controller
{
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required']
        ]);
        $password = mt_rand(10000,999999);
        $mentee = new Mentee();
        $mentee->name = $request->name;
        $mentee->email = $request->email;
        $mentee->password = Hash::make($password);
        $mentee->saved_password = $password;

        if($request->image) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('profile'), $imageName);
            $mentee->image = $imageName;
        }else{
            $mentee->image = "default.jpeg";
        }
        $mentee->save();

        return response()->json($mentee);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Mentee::where('is_mentor',false)->orderBy('created_at','desc')->get());
    }

    public function edit(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Mentee::where('id',$id)->first());
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => ['required']
        ]);

        $mentee = Mentee::where('id',$request->id)->first();
        $mentee->name = $request->name;
        $mentee->email = $request->email;

        if($request->image) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('profile'), $imageName);
            $mentee->image = $imageName;
        }
        $mentee->update();

        return response()->json($mentee);

    }
    public function delete(int $id)
    {
        $mentee = Mentee::where('id',$id)->first();
        $mentee->delete();

        return response()->json(['success' => true]);
    }

    public function mentee_without_mentor(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Mentee::where(['mentor_id' => null,'is_mentor' => false])->get());
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'id' => ['required'],
            'email' => ['required','email'],
            'password' => ['required']
        ]);
        $mentor = Mentor::where('id',$request->id)->first();
        $checkMenteeExistence = $mentor->mentees->where('email', $request->email)->first();
        if(!$checkMenteeExistence) {
            return response()->json(['errors' => [
                'email' => ["Please you do not belong to {$mentor->name} group"]
            ]],422);
        }
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('mentee')->attempt($credentials);
        if (!$token) {
            return response()->json(['errors' => [
                'email' => ['Incorrect credentials']
            ]], 422);
        }
        $user = new MenteeResource(auth('mentee')->user());
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => new MenteeResource(auth('mentee')->user()),
            'authorisation' => [
                'token' => Auth::guard('mentee')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function group_members($id)
    {
        $mentor = Mentor::where('name',$id)->first();
        $checkIfMenteeExit = $mentor->mentees->where('id',\auth('mentee')->id())->first();
        if(!$checkIfMenteeExit) {
            return response()->json(['error' => 'You do not belong to this group'],302);
        }

        return response()->json(MenteeResource::collection($mentor->mentees->where('is_mentor',false)->where('id','!=',\auth()->id())));

    }

    public function create_post(Request $request)
    {
        $mentor = Mentor::where('name',$request->mentor)->first();
        $checkIfMenteeExit = $mentor->mentees->where('id',\auth('mentee')->id())->first();
        if(!$checkIfMenteeExit) {
            return response()->json(['error' => 'You do not belong to this group'],302);
        }

        $this->validate($request,[
            'body' => ['required']
        ]);

        $post = new Post();
        $post->body = $request->body;
        $post->mentor_id = $mentor->id;
        $post->mentee_id = \auth('mentee')->id();

        if($request->image) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('post_files'), $imageName);
            $post->image = $imageName;
        }
        $post->save();

        return response()->json(new PostResource($post));


    }

    public function get_post(): \Illuminate\Http\JsonResponse
    {
        $mentee = Mentee::where('id',\auth('mentee')->id())->first();
        $mentor = $mentee->mentor;
        $posts = $mentor->posts->toQuery()->orderBy('created_at','desc')->paginate(10);

        return response()->json(PostResource::collection($posts)->response()->getData(true));
    }

    public function get_single_post(Request $request)
    {
        $mentor = Mentor::where('name',$request->mentor)->first();
        $checkIfMenteeExit = $mentor->mentees->where('id',\auth('mentee')->id())->first();
        if(!$checkIfMenteeExit) {
            return response()->json(['error' => 'You do not belong to this group'],302);
        }
        $post = $mentor->posts->where('id',$request->post_id)->first();

        return response()->json(new PostResource($post));
    }
    public function post_comment(Request $request)
    {
        $this->validate($request,[
            'body' => ['required']
        ]);
        $post = Post::where('id', $request->post_id)->first();
        $mentor = $post->mentor;
        $checkIfMenteeExit = $mentor->mentees->where('id',\auth('mentee')->id())->first();
        if(!$checkIfMenteeExit) {
            return response()->json(['error' => 'You do not belong to this group'],302);
        }
        $comment = new Comment();
        $comment->mentee_id = \auth('mentee')->id();
        $comment->post_id = $post->id;
        $comment->body = $request->body;

        $comment->save();
        $comment->post->touch();
        return response()->json(new PostResource($post));

    }

    public function top_post(): \Illuminate\Http\JsonResponse
    {
        $mentee = Mentee::where('id',\auth('mentee')->id())->first();
        $mentor = $mentee->mentor;
        $posts = Post::withCount('comments')->orderBy('updated_at', 'desc')->with('comments')->where('mentor_id',$mentor->id)->take(5)->get();

        return response()->json(PostResource::collection($posts));
    }
    public function edit_post(Request $request): \Illuminate\Http\JsonResponse
    {
        $post = Post::where('id',$request->id)->first();
        if (\auth()->id() !== $post->mentee_id) {
            return response()->json(['error' => 'Please you cant edit this post'],302);
        }
        return response()->json(new PostResource($post));
    }

    public function update_post(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'body'=>['required'],
        ]);
        $post = Post::where('id',$request->post_id)->first();
        if (\auth('mentee')->id() !== $post->mentee_id) {
            return response()->json(['error' => 'Please you cant edit this post'],302);
        }
        $post->body = $request->body;

        if($request->image) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('post_files'), $imageName);
            $post->image = $imageName;
        }

        $post->update();

        return response()->json(new PostResource($post));


    }
    public function delete_post(Request $request)
    {
        $post = Post::where('id',$request->id)->first();
        if (\auth()->id() !== $post->mentee_id) {
            return response()->json(['error' => 'Please you cant edit this post'],302);
        }
        $post->delete();

        return response()->json(['success' => true]);
    }
}
