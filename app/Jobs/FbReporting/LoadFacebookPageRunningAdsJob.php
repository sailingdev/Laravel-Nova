<?php

namespace App\Jobs\FbReporting;

use App\Revenuedriver\FacebookPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LoadFacebookPageRunningAdsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The batch instance.
     *
     * @var \App\Models\FacebookPage
     */
    protected $models;


    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 900;
    
     /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($models)
    { 
        $this->models = $models;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $facebookPage = new FacebookPage;
        foreach ($this->models as $model) {
            $facebookPage->curateRunningAds($model->id, $model->page_id);
        }
    }
}
