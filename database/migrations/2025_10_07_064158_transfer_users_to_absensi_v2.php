<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TransferUsersToAbsensiV2 extends Migration
{
    public function up()
    {
        // Get users from the default connection
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            // Insert into absensi_v2.users
            DB::connection('absensi_v2')->table('users')->updateOrInsert(
                ['email' => $user->email],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            );
        }
    }

    public function down()
    {
        // Optional: Add logic to reverse the migration if needed
    }
}
