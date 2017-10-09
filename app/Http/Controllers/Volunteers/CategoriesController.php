<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Category;

class CategoriesController extends Controller
{
	# index
	public function index()
	{
		$categories = Category::ordered()->get();
		return response()->json([
			'entities' => $categories
		], 200);
	}

	# show
	public function show(Request $request, $slug)
	{
		$category = Category::whereSlug($slug)->first();
		return response()->json($category, 200);
	}
}
