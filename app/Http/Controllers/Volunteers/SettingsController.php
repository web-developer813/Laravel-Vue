<?php

namespace App\Http\Controllers\Volunteers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Volunteers\UpdateAccountSettingsRequest;
use App\Http\Requests\Volunteers\UpdateProfileSettingsRequest;
use App\Http\Requests\Volunteers\UpdateCategoriesSettingsRequest;
use App\Helpers\Services\LocationService;
use DB;

class SettingsController extends Controller
{
    # account
    public function account()
    {
        $user = auth()->user();
        return view('volunteers.settings.account', compact('user'));
    }

    # update account
    public function update_account(UpdateAccountSettingsRequest $request)
    {
        $input = $request->only(['email', 'password']);

        DB::transaction(function () use ($input) {
            auth()->user()->update($input);
        });

        flash('Your settings have been updated', 'success');
        return redirect()->route('settings.account');
    }

    # profile
    public function profile()
    {
        $volunteer = auth()->user()->volunteer;
        return view('volunteers.settings.profile', compact('volunteer'));
    }

    # update account
    public function update_profile(UpdateProfileSettingsRequest $request)
    {
        $input = $request->only(['name', 'description']);

        DB::transaction(function () use ($input, $request) {
            $volunteer = auth()->user()->volunteer;
            $volunteer->update($input);
            $volunteer->updateProfilePhoto($request->file('photo'));
            $volunteer->updateFile($request->file('resume'), 'resume');
        });

        flash('Your settings have been updated', 'success');
        return redirect()->route('settings.profile');
    }

    # update account
    public function updateImage(Request $request)
    {
        $input = $request->only(['name', 'description']);

        DB::transaction(function () use ($input, $request) {
            $volunteer = auth()->user()->volunteer;
            //$volunteer->update($input);
            $volunteer->updateProfilePhoto($request->file('photo'));
            //$volunteer->updateFile($request->file('resume'), 'resume');
        });

        flash('Your settings have been updated', 'success');
        return redirect()->route('settings.profile');
    }

    # categories
    public function categories()
    {
        // $volunteer = auth()->user()->volunteer;
        return view('volunteers.settings.categories');
    }

    # update categories
    public function update_categories(UpdateCategoriesSettingsRequest $request)
    {
        $volunteer = auth()->user()->volunteer;

        $input = $request->only('location');
        $input = array_merge($input, LocationService::getCoordinates($request->location, $volunteer->location));
        $categories = $request->input('categories', []);

        $feed = \FeedManager::getUserFeed($volunteer->id);
        if (empty($feed)) {
            throw new CustomValidationException(['categories' => 'There was an error finding your user feed. Please try again later.']);
        }
        $existingCategories = $volunteer->categories()->pluck('id');
        $existingCategories = $existingCategories->toArray();
        $unfollows = array_diff($existingCategories, $categories);
        if (!empty($unfollows)) {
            foreach ($unfollows as $unfollow) {
                $feed->unfollowFeed('categories', $unfollow);
            }
        }

        $cats = [];
        foreach ($categories as $category) {
            $followed = $feed->followFeed('categories', $category);
            if ($followed) {
                $cats[] = $category;
            }
        }

        DB::transaction(function () use ($volunteer, $input, $cats) {
            $volunteer->update($input);
            $volunteer->categories()->sync($cats);
        });

        flash('Your settings have been updated', 'success');
        return redirect()->route('settings.categories');
    }
}
