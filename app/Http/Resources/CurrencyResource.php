<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'currency_code' => $this->currency_code,
            'currency_name' => $this->currency_name,
            'icon_url' => $this->icon_url,
            'icon_alt' => $this->icon_alt,
            'buy_rate' => $this->buy_rate,
            'sell_rate' => $this->sell_rate,
            'user_id' => $this->user_id,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString()
        ];
    }
}
