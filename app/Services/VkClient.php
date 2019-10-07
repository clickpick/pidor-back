<?php

namespace App\Services;

use GuzzleHttp\Client;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiBlockedException;
use VK\Exceptions\Api\VKApiMessagesUserBlockedException;
use VK\Exceptions\Api\VKApiPrivateProfileException;
use VK\Exceptions\Api\VKApiStoryIncorrectReplyPrivacyException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkClient
{
    protected $client;
    private $accessToken;

    private const API_VERSION = '5.95';

    public function __construct()
    {
        $this->client = new VKApiClient(self::API_VERSION);
        $this->accessToken = config('services.vk.app.service');
    }

    public function getUsers($ids, array $fields)
    {

        $isFew = is_array($ids);

        $response = $this->client->users()->get($this->accessToken, [
            'user_ids' => $isFew ? $ids : [$ids],
            'fields' => $fields,
        ]);

        return $isFew ? $response : $response[0];
    }


    /**
     * @param $vkId
     * @return mixed
     * @throws VKApiException
     * @throws VKClientException
     */
    public function getFriendIdsOfUser($vkId)
    {
        $response = $this->client->friends()->get($this->accessToken, [
            'user_id' => $vkId,
            'order' => 'hints',
            'fields' => []
        ]);

        return $response;
    }

    /**
     * @param $uploadUrl
     */
    public function postStory($uploadUrl) {

        $client = new Client();

        $client->post($uploadUrl, [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen(storage_path('app/stories/story.png'), 'r')
                ],
            ]
        ]);
    }
}
