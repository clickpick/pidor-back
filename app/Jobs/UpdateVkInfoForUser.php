<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateVkInfoForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param User|integer $user User model or vk id
     * @param array|null $data
     */
    public function __construct($user, ?array $data = null)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($this->user instanceof User) {
            $this->user->fillPersonalInfoFromVk();
            return;
        }

        if (!$this->data) {
            return;
        }

        if (is_integer($this->user)) {
            $user = User::whereVkUserId($this->user)->first();

            if (!$user) {
                return;
            }

            $user->fillPersonalInfoFromVk($this->data);
        }
    }
}
