<?php

namespace App\Http\Resources\FbReporting;

use Illuminate\Http\Resources\Json\JsonResource;

class FbPagePostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'text' => $this->text,
            'url' => $this->url,
            'media' => $this->media
        ];
    }
}
