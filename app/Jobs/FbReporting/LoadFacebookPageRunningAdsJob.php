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

class LoadFacebookPageRunningAdsJob //implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The batch instance.
     *
     * @var \App\Models\FacebookPage
     */
    protected $model;


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
    public function __construct($model)
    { 
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $facebookPage = new FacebookPage;
        Log::info('Load facebook page running ads count job fired', []);
        // foreach ($this->models as $model) {
            try {
                $facebookPage->curateRunningAds($this->model->id, $this->model->page_id);
            } catch (\Throwable $th) {
                Log::emergency('An error occured while counting running ads for facebook page', [$th, $this->model]);
            }
        // }
    }
}
