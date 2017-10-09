<?php

namespace App\Http\Controllers\Api\Social;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Volunteer;
use App\Post;
use App\Like;

class LikesController extends ApiController
{
    protected $like;
    //
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
    public function index()
    {
        //
        return false;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $success = false;
        $message = 'There was an error liking this object.';
        $object = '';
        
        $this->validate($request, [
            'likable_id' => 'required',
            'likable_type' => 'required',
        ]);
        
        $likable_id = $request->likable_id;
        $likable_type = $request->likable_type;

        $user_id = $this->volunteer->id;

        // Make sure the user hasn't already liked this object
        if (!empty($this->volunteer->likes()->where([['likable_id','=',$likable_id],['likable_type','=',$likable_type],])->first())) {
            // User has already liked this or something fishy is going on...
            return response()->json([
                'success' => $success,
                'message' => $message,
            ]);
        }

        $newObject = '';
        
        $like = new Like();
        $like->likable_id = $likable_id;
        $like->likable_type = $likable_type;
        $like->user_id = $user_id;
        if ($created_like = $like->save()) {
            $message = 'Object successfully liked.';
            $success = true;
            $newObject = $like;
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'object' => $newObject,
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
        return false;
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
        return false;
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
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $success = false;
        $message = 'There was an error unliking this object.';

        $like = Like::find($id);

        if (!empty($like)) {
            if ($like->user_id == $request->user()->id) {
                $deleted = $like->delete(); 

                $success = !empty($deleted);
                $message = 'Successfully unliked Object';
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);

    }
}