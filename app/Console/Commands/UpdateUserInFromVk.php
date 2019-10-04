<?php

namespace App\Console\Commands;

use App\Jobs\UpdateVkInfoForUser;
use App\Services\VkClient;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class UpdateUserInFromVk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update_vk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user info from VK';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::chunk(1000, function(Collection $users) {

            $userIds = $users->map(function (User $user) {
                return $user->vk_user_id;
            });

            $response = (new VkClient())->getUsers($userIds->toArray(), ['first_name', 'last_name', 'photo_200', 'timezone', 'sex', 'bdate']);

            foreach ($response as $item) {
                UpdateVkInfoForUser::dispatch($item['id'], $item);
            }
        });
    }
}
