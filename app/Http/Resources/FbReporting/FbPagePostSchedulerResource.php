<?php

namespace App\Http\Resources\FbReporting;

use App\Labs\StringManipulator;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class FbPagePostSchedulerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sm = new StringManipulator;
        
        return [
          'fb_page_post_scheduler_id' => $this->id,
          'fb_page_post_id' => $this->fbPagePost->id,
          'page_group' => $sm->generateStringFromArray((array) $this->page_groups, ','),
          'reference' => $this->fbPagePost->reference,
          'date' => Carbon::parse($this->start_date)->toDateString(),
          'time' => Carbon::parse($this->start_date)->toTimeString(),
          'text' => $this->fbPagePost->text,
          'media' => $this->fbPagePost->media,
          'url' => $this->fbPagePost->url
        ];
    }
}
