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

class LoadBMFacebookPagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 900;
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $facebookPage = new FacebookPage;
        return $facebookPage->loadBusinessAccountPages();
    }
}
