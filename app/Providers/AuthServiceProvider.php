<?php

namespace App\Providers;

use App\Models\FbReporting\AdAccount;
use App\Models\FbReporting\AdLocale;
use App\Models\FbReporting\AdText;
use App\Models\FbReporting\FbPage;
use App\Models\FbReporting\Market;
use App\Models\FbReporting\Website;
use App\Models\User;
use App\Policies\FbReporting\AdAccountPolicy;
use App\Policies\FbReporting\AdLocalePolicy;
use App\Policies\FbReporting\AdTextPolicy;
use App\Policies\FbReporting\FbPagePolicy;
use App\Policies\FbReporting\MarketPolicy;
use App\Policies\FbReporting\UserPolicy;
use App\Policies\FbReporting\WebsitePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        AdAccount::class => AdAccountPolicy::class,
        AdLocale::class => AdLocalePolicy::class,
        AdText::class => AdTextPolicy::class,
        Market::class => MarketPolicy::class,
        User::class => UserPolicy::class,
        Website::class => WebsitePolicy::class,
        FbPage::class => FbPagePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
