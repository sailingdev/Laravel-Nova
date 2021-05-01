<?php

namespace App\Http\Resources\FbReporting;

use App\Labs\StringManipulator;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
          'page_group' => $sm->generateStringFromArray($this->page_groups, ','),
          'reference' => $this->fbPagePost->reference,
          'date' => Carbon::parse($this->startDate)->toDateString(),
          'time' => Carbon::parse($this->startDate)->toTimeString()  
        ];
    }
}
