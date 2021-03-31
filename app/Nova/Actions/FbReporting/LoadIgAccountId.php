<?php

namespace App\Nova\Actions\FbReporting;

use App\Revenuedriver\FacebookPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class LoadIgAccountId extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Load Instagram Account ID';

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
            $igAccountId = $facebookPage->loadInstagramAccounts($model->page_id, [
                'id'
            ]);
            if ($igAccountId[0] == true) {
                $model->instagram_id = $igAccountId[1];
                $model->save();
            }
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
