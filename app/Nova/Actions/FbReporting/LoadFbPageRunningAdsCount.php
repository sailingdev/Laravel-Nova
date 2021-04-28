<?php

namespace App\Nova\Actions\FbReporting;

use App\Jobs\FbReporting\LoadFacebookPageRunningAdsJob;
use App\Revenuedriver\FacebookPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class LoadFbPageRunningAdsCount extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Load Page Running Ads Count';

    protected $runInBackground = false;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    { 
        $facebookPage = new FacebookPage; 
        foreach ($models as $model) {
            $facebookPage->curateRunningAds($model->id, $model->page_id);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
