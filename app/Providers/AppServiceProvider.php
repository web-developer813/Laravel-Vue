<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Logging\Log;
use Psr\Log\LoggerInterface;

use App\Observers\HoursObserver;
use App\Observers\InvitationObserver;
use App\Observers\DonationObserver;
use App\Observers\IncentivePurchaseObserver;
use App\Observers\OpportunityObserver;
use App\Observers\LikeObserver;
use App\Hours;
use App\Invitation;
use App\Donation;
use App\IncentivePurchase;
use App\Application;
use App\Opportunity;
use App\Like;
use App\Follow;
use App\Incentive;
use App\Volunteer;
use App\Nonprofit;
use App\Forprofit;
use URL;
use Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Hours::observe(HoursObserver::class);
        Invitation::observe(InvitationObserver::class);
        Donation::observe(DonationObserver::class);
        IncentivePurchase::observe(IncentivePurchaseObserver::class);
		Opportunity::observe(OpportunityObserver::class);
        Like::observe(LikeObserver::class);
        Follow::observe(FollowObserver::class);
        Validator::extend('max_array_size', 'App\Helpers\Services\ValidationService@max_array_size');

        // intercom
        Volunteer::created('App\Listeners\IntercomListeners@onVolunteerCreated');
        Nonprofit::created('App\Listeners\IntercomListeners@onNonprofitCreated');
        Forprofit::created('App\Listeners\IntercomListeners@onForprofitCreated');
        Application::created('App\Listeners\IntercomListeners@onApplicationCreated');
        Opportunity::created('App\Listeners\IntercomListeners@onOpportunityCreated');
        Incentive::created('App\Listeners\IntercomListeners@onIncentiveCreated');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('bugsnag.multi', Log::class);
        $this->app->alias('bugsnag.multi', LoggerInterface::class);
    }
}
