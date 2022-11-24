<?php

namespace App\Http\Controllers;

use App\Http\Resources\MentorResource;
use App\Mail\MentorMentee;
use App\Models\Mentee;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MentorController extends Controller
{
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request,[
            'name' => ['required']
        ]);

        $mentee = new Mentor();
        $mentee->name = $request->name;

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
        return response()->json(Mentor::orderBy('created_at','asc')->get());
    }

    public function edit(int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Mentor::where('id',$id)->first());
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => ['required']
        ]);

        $mentee = Mentor::where('id',$request->id)->first();
        $mentee->name = $request->name;

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
        $mentee = Mentor::where('id',$id)->first();
        $mentee->delete();

        return response()->json(['success' => true]);
    }
    public function getMentees(): \Illuminate\Http\JsonResponse
    {
        return response()->json(MentorResource::collection(Mentor::all()),200);
    }
    public function shuffle(Request $request)
    {

        if($request->current === null) {
            $request->current = 0;
        }
        $mentors = Mentor::orderBy('created_at', 'asc')->get()->toArray();
        $currentIndex = $mentors[$request->current];
        $nextId = ($request->current + 1) % count($mentors);
        $mentee = Mentee::where('mentor_id',null)->get()->shuffle()->first();

        if(!$mentee) {
            return response()->json(true,200);
        }

        $mentee->mentor_id = $currentIndex['id'];
        $mentee->update();

        Mail::to($mentee->email)->send(new MentorMentee($mentee));

        return response()->json($nextId);

    }

    public function reset(): \Illuminate\Http\JsonResponse
    {
       $mentees =  Mentee::all();
       foreach ($mentees as $mentee) {
           $mentee->mentor_id = null;
           $mentee->update();
       }

       return response()->json(['success' => true]);
    }
}
