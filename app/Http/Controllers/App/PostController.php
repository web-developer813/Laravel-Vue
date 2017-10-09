<?php

namespace App\Http\Controllers\App;

use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Photo;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postCreatePost(Request $request)
    {
        dd($request->all());
        $success = false;
        $message = 'There was an error creating your post.';

        $this->validate($request, [
             'content' => 'required'
         ]);

        $post = new Post();
        $post->content = $request['content'];

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $cloudinary = Cloudder::upload($file);
           // create new upload
           $photo = Photo::create([
               'cloudinary_key' => $cloudinary->getResult()['public_id'],
               'photoable_type' => get_class($this),
               'photoable_id' => $this->id
           ]);
            $post->post_photo_id =$photo->id;
        }

        if ($request->user()->posts()->save($post)) {
            var_dump($request->all());
            $message = 'Post Successfully Created2121';
            $success = true;
        }

        // return to dashboard
        if (session()->has('auth-nonprofit')) {
            return redirect()->intended(route('nonprofits.dashboard', ['nonprofit' => auth()->id()]))->with(['success' => $success,'message' => $message]);
        } elseif (session()->has('auth-forprofit')) {
            return redirect()->intended(route('forprofits.dashboard', ['forprofit' => auth()->id()]))->with(['success' => $success,'message' => $message]);
        } else {
            return redirect()->intended(route('dashboard'))->with(['success' => $success,'message' => $message]);
        }
    }

    public function streamTest(Request $request)
    {
        $feed = \FeedManager::getUserFeed(1);
        $activity = [
            'actor' => 'App\Volunteer:1',
            'verb' => 'post',
            'object' => 'App\Post:94',
            'time' => '2017-05-27T05:56:35.000000',
            'foreign_id' => 'App\Post:91',
        ];
        return response()->json($feed->getActivities());
    }
}
