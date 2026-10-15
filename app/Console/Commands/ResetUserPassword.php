<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetUserPassword extends Command
{
    protected $signature = 'user:reset-password {email? : The email of the user}';

    protected $description = 'Reset a user password to 12345678';

    public function handle(): int
    {
        $email = $this->argument('email') ?? $this->ask('User email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No user found with email: {$email}");

            return self::FAILURE;
        }

        $user->password = '12345678';
        $user->save();

        $this->info("Password for {$user->email} has been reset to: 12345678");

        return self::SUCCESS;
    }
}
