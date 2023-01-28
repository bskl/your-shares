<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            $this->merge(Arr::except((array) parent::toArray($request), [
                'user_id', 'order', 'created_at', 'updated_at',
            ])),
        ];
    }
}
