<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenteeResource;
use App\Models\Mentee;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenteeController extends Controller
{
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required']
        ]);

        $mentee = new Mentee();
        $mentee->name = $request->name;
        $mentee->email = $request->email;

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
        return response()->json(Mentee::orderBy('created_at','desc')->get());
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
        return response()->json(Mentee::where('mentor_id',null)->get());
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
        $token = auth('mentee')->attempt($credentials);
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
}
