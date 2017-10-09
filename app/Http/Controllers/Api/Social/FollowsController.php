<?php

namespace App\Http\Controllers\Api\Social;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Follow;

class FollowsController extends Controller
{
    protected $volunteer;

    public function __construct() {
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
        $success = false;
        $message = 'Failed to follow this entity';
        $object = '';

        $this->validate($request, [
            'entity_to_follow' => 'required|max:255',
            'entity_type' => 'required|max:255',
        ]);

        $activeUser = $this->volunteer->id;
        $entityToFollow = $request->entity_to_follow;
        $followable_type = $request->entity_type;

        switch ($followable_type) {
            case 'App\Nonprofit':
                $target_feed_slug = 'organizations';
                break;
            case 'App\Forprofit':
                $target_feed_slug = 'businesses';
                break;
            default:
                $target_feed_slug = 'user';
                break;
        }

        // Make sure the user isn't already following this person
        if (empty($this->volunteer->follows->where([['followable_id','=',$entityToFollow],['followable_type','=',$followable_type]])->first())) {
            try {
                $follow = new Follow();

                $follow->followable_id = $entityToFollow;
                $follow->followable_type = $followable_type;
                $follow->user_id = $this->volunteer->id;
                
                $saved = $follow->save();

                $success = !empty($saved);
                $message = !empty($saved) ? 'Successfully followed user.' : $message;
                $object = $follow;
            }
            catch (Exception $e) {
                $message = $e;
            }
        }
        
        return response()->json([
            'success' => $success,
            'message' => $message,
            'object' => $object,
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
    public function destroy(Request $request, $id)
    {
        $success = false;
        $message = 'Failed to unfollow. Please try again.';

        $follow = Follow::find($id);

        // Make sure we are working with a real record 
        if (!empty($follow)) {
            // Make sure we are actually follow this user before just destroying it. This should hopefully prevent someone from spoofing the DOM to follow/unfollow for other users
            if ($follow->user_id == $this->volunteer->id) {

                try {
                    $deleted = $follow->delete();
                    $success = !empty($deleted);
                    $message = !empty($deleted) ? 'Successfully unfollowed!' : $message;
                }

                catch (Exception $e) {
                    $message = $e;
                }
                
            }
        }
        
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
