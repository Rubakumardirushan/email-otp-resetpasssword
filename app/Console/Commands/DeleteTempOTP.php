<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User; // Replace with your actual User model
use Carbon\Carbon;

class DeleteTempOTP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:temp-otp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary OTPs older than 5 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fiveMinutesAgo = Carbon::now()->subMinutes(1);

        $users = User::where('temp_otp', '!=', null)
            ->where('updated_at', '<', $fiveMinutesAgo)
            ->get();

        foreach ($users as $user) {
            $user->temp_otp = null;
            $user->save();
        }

        $this->info('Temporary OTPs older than 5 minutes have been deleted.');

        return 0;
    }
}