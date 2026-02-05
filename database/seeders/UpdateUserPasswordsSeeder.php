<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Skip if password is already hashed with Bcrypt
            if (preg_match('/^\$2[ayb]\$.{56}$/', $user->password)) {
                $this->command->info("Skipping user {$user->email} - password already hashed");

                continue;
            }

            // Update the password with Bcrypt hash
            $user->password = Hash::make($user->password);
            $user->save();
            $this->command->info("Updated password for user: {$user->email}");
        }
    }
}
