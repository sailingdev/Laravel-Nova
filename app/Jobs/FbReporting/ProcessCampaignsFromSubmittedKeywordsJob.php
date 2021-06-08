<?php

namespace App\Jobs\FbReporting;
 
use App\Services\SubmittedKeywordService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels; 
// 
class ProcessCampaignsFromSubmittedKeywordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The batch instance.
     *
     * @var \App\Models\SubmittedKeyword
     */
    protected $data;

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
    public function __construct($data)
    { 
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sks = new SubmittedKeywordService;
        return $sks->processSubmittedKeywords($this->data);
    }
}
