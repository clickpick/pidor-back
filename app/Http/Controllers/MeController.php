<?php

namespace App\Http\Controllers;

use App\Http\Requests\GivePidorRateRequest;
use App\Http\Requests\PostStoryRequest;
use App\Http\Resources\UserResource;
use App\PublishedStory;
use App\Services\VkClient;
use App\User;
use Illuminate\Support\Facades\Auth;
use VK\Exceptions\Api\VKApiPrivateProfileException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class MeController extends Controller
{

    public function me()
    {
        return new UserResource(Auth::user());
    }

    public function getFriends()
    {
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

    public function prepareStory()
    {
        $user = Auth::user();

        return  $user->generateConfessionStory()->stream('data-url');
    }

    public function postStory(PostStoryRequest $request)
    {
        $user = Auth::user();

        $user->postStory(PublishedStory::CONFESSION, $request->upload_url);

        $user->refresh();

        return new UserResource($user);
    }

    public function givePidorRate(GivePidorRateRequest $request) {
        $sender = Auth::user();

        $acceptor = User::find($request->acceptor_id);

        $friendIds = collect((new VkClient())->getFriendIdsOfUser($sender->vk_user_id)['items']);

        if (!$friendIds->contains($acceptor->vk_user_id)) {
            abort(403);
        }

        if ($sender->pidorFeedbackAsSender()->where('acceptor_id', $acceptor->id)->exists()) {
            abort(403);
        }

        $sender->givePidorRateTo($acceptor);
    }
}
