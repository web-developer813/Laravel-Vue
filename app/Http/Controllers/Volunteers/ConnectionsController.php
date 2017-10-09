<?php

namespace App\Http\Controllers\Volunteers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Repositories\UserRepository;
use App\Volunteer;

class ConnectionsController extends Controller
{
	/**
	* The user repository instance.
	*
	* @var UserRepository
	*/
	protected $users;
	
	/**
	* Create a new controller instance.
	*
	* @param  UserRepository  $users
	*/
	public function __construct(UserRepository $users) {
		$this->middleware('auth');
		$this->users = $users;
	}
	
	public function index(Request $request, User $user) {
		
		// $query = Volunteer::query();
		
		// // search
		// if ($request->search)
		// 	$query->search($request->search);

		// // ordering
		// //$query->ordered();

		// // relationships
		// $query->with('user');

		// // pagination
		// $total_volunteers = $this->count($query);
		// $volunteers = $this->paginate($query, $request);
		
		// $follows = $request->user()->follows;

		// // items
		// foreach($volunteers as $volunteer)
		// {
		// 	$va = $volunteer->toArray();
		// 	$va['following'] = false;
		// 	foreach ($follows as $follow) {
		// 		if ($volunteer->id === $follow->follow_id) {
		// 			$va['following'] = true;
		// 			$va['follow_id'] = $follow->id;
		// 		}
		// 	}
			
		// 	$this->items[] = $va;
		// }

		// return view('volunteers.connections.index', [
		// 	'users' => $this->items,
		// 	'meta' => [
		// 		'count' => $total_volunteers
		// 	],
		// 	'nextPageUrl' => nextPageUrl($volunteers->nextPageUrl())
		// ]);

		return view('volunteers.connections.index');
	}
	
	# paginate
	protected function paginate($query, $request, $per_page = 10)
	{
		$results = $query->paginate($per_page);
		$results->appends($request->except('page'));
		return $results;
	}

	# search
	protected function search($query, $request)
	{
		if ($request->search)
			return $query->search($request->search);
		return $query;
	}

	# count
	protected function count($query)
	{
		return $query->count();
	}
}
