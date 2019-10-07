<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoryRequest;
use App\Http\Resources\UserResource;
use App\Services\VkClient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VK\Exceptions\Api\VKApiPrivateProfileException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class MeController extends Controller
{

    public function me() {
        return new UserResource(Auth::user());
    }

    public function getFriends() {
        $user = Auth::user();

        try {
            $friendIds = (new VkClient())->getFriendIdsOfUser($user->vk_user_id);
        } catch (VKApiPrivateProfileException $e) {
            abort(403, 'profile is private');
        } catch (VKApiException $e) {
            abort(500);
        } catch (VKClientException $e) {
            abort(500);
        }

        /** @var array $friendIds */
        $registeredFriends = User::whereIn('vk_user_id', $friendIds['items'])->paginate();

        return UserResource::collection($registeredFriends);
    }

    public function postStory(PostStoryRequest $request) {
        (new VkClient())->postStory($request->upload_url);
    }
}
