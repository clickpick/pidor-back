<?php

namespace App\Jobs;

use App\Donate;
use App\PidorOfTheDay;
use App\Services\Facades\VkDonate;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CheckDonates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lastDonatesResponse = VkDonate::lastDonates();

        $lastDonates = $lastDonatesResponse['donates'];

        $transactionIds = collect($lastDonates)->map(function ($lastDonate) {
            return $lastDonate['id'];
        });

        $transactionsInDb = Donate::whereIn('transaction_id', $transactionIds->toArray())->get()->map(function (Donate $donate) {
            return $donate->transaction_id;
        });


        $newTransactionIds = $transactionIds->diff($transactionsInDb->toArray());

        $newDonates = collect($lastDonates)->filter(function ($lastDonate) use ($newTransactionIds) {
            return $newTransactionIds->contains($lastDonate['id']);
        });

        $newDonates->each(function ($newDonate) {

            DB::transaction(function () use ($newDonate) {
                $donate = Donate::create([
                    'transaction_id' => $newDonate['id'],
                    'vk_user_id' => $newDonate['uid'],
                    'paid_at' => $newDonate['date'],
                    'value' => $newDonate['sum']
                ]);

                $user = User::where('vk_user_id', $newDonate['uid'])->first();

                if ($user) {
                    $donate->user()->associate($user);
                    $donate->save();


                    if ($newDonate['sum'] <= 1000) {
                        $rateToDraw = floor($newDonate['sum'] / 10);

                        $user->minusRate($rateToDraw, $donate);
                    } else {
                        PidorOfTheDay::queueUser($user);
                    }
                }
            });

        });
    }
}
