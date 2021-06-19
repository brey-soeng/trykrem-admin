<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\Models\SysAdmin;
        $admin->username = 'admin';
        $admin->email = 'admin@ity.com';
        $admin->status = 1;
        $admin->password = bcrypt('123456');
        $admin->save();
    }
}
