<?php

namespace App\Http\Controllers\Api\Social;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Volunteer;
use App\Post;
use App\PostMedia;
use App\MediaTypes;
use App\Photo;
use Auth;
use Cloudder;

class PostsController extends ApiController
{
    protected $volunteer;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->volunteer = auth()->user()->volunteer;
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Volunteer $volunteer)
    {
        $success = false;
        $message = 'There was an error retrieving posts for this user.';
        $this->validate($request, [
            'user_id' => 'required'
        ]);

        $user_id = $request->user_id;

        $query = Post::where('user_id', '=', $user_id);

        $query->with('volunteer');

        if ($request->search) {
            $query->search($request->search);
        }

        // with relationships
        //$query->with('volunteer');

        // order
        $query->orderedByCreationDate();

        $total = $this->count($query);

        // pagination
        $posts = $query->paginate(20);
        $posts->appends($request->except('page'));

        $following = $this->volunteer->following->where('followable_id', '=', $user_id)->first();
        // items
        $items = [];
        foreach ($posts as $post) {
            $post['has_image'] = false; // Temporary patch
            $items[] = array(
                'object' => $post,
                'slug' => 'post',
                'type' => 'App\Post',
                'actor' => $post->volunteer,
                'following' => $following,
                'total_likes' => $post->likes->count(),
                'like' => $post->likedByMe(),
                'formated_time' => $post->created_at->diffForHumans(),
            );
        }

        return response()->json([
            'items' => $items,
            'nextPageUrl' => nextPageUrl($posts->nextPageUrl()),
            'meta' => array(
                'total' => $total,
                //'token' => $token,
                //'api_key' => env('GETSTREAM_API_KEY'),
                //'app_id' => env('GETSTREAM_API_APPID')
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // dd($request->all());
        $success = false;
        $message = 'There was an error creating your post.';
        $newPost = '';

        $this->validate($request, [
            'content' => 'required'
        ]);
        
        $post = new Post();
        $post->content = $request['content'];
        if ($created_post = $request->user()->posts()->save($post)) {
            $message = 'Post Successfully Created';
            $success = true;
            $newPost = $created_post;
        }

        if ($request->hasFile('media')) {
            
           if (!is_array($request->file('media'))) {
                $files[] = $request->file('media');
            } else {
                $files = $request->file('media');
            }

            foreach ($files as $file) {
                $cloudinary = Cloudder::upload($file);
                // create new upload
                $photo = PostMedia::create([
                    'cloudinary_key' => $cloudinary->getResult()['public_id'],
                    'post_id' => $post->id
                ]);
            }
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
            'object' => $newPost,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
