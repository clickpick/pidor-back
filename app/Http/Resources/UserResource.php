<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'vk_user_id' => $this->vk_user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar_200' => $this->avatar_200,
            'utc_offset' => $this->utc_offset,
            'messages_are_enabled' => $this->messages_are_enabled,
            'notifications_are_enabled' => $this->notifications_are_enabled,
            'pidor_rate' => $this->pidor_rate,
            'gif' => $this->gif,
            'phrase' => $this->getPhrase()
        ];
    }
}
