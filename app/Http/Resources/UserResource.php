<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_account' => Carbon::make($this->created_at)->format("d-m-Y | H:i:s"),
            'updated_account' => Carbon::make($this->updated_at)->format("d-m-Y | H:i:s"),
        ];
    }
}
