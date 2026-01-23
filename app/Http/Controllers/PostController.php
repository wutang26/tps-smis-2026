<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SessionProgramme;
use Illuminate\Http\Request;
use App\Services\AuditLoggerService;
use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        $session_programmes = SessionProgramme::all();
        return view('settings.posts.index', compact('posts','session_programmes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $session_programmes = SessionProgramme::all();
        return view('settings.posts.create', compact('session_programmes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_programme_id' => 'required|exists:session_programmes,id|unique:posts,session_programme_id',
        ]);
        if($validator->fails()){
            return redirect()->back()->with('error',$validator->errors()->first('session_programme_id'));
        }
        $post = Post::create([
            'session_programme_id' => $request->session_programme_id
        ]);
        return redirect()->back()->with('success','Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return "hhh";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Request $request, AuditLoggerService $auditLogger)
    {
        $postSnapshot = $post;
        $name = $post->session->session_programme_name ?? 'N/A';
        // Delete the post
        $post->delete();
        $auditLogger->logAction([
            'action' => 'delete_post',
            'target_type' => 'Post',
            'target_id' => $postSnapshot->id,
            'metadata' => [
                'title' => $name,
            ],
            'old_values' => [
                'post' => $postSnapshot,
            ],
            'new_values' => null,
            'request' => $request,
        ]);
        // Redirect back with a success message
        return redirect()->back() ->with('success', 'Post deleted successfully.');
    }

    public function publish(Request $request, Post $post)
    {

        $post->status = $post->status == "published"? "pending": "published";
        $post->published_by = $post->status == "published"? $request->user()->id: null;
        $post->published_at = \Carbon\Carbon::now();
        $post->save();

        // Redirect back with a success message
        return redirect()->back() ->with('success', 'Post '.$post->status.' successfully.');
    }
}
