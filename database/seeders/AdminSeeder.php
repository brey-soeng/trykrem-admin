<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        static $createdAt;

        $admin = new \App\Models\SysAdmin;
        $admin->username = 'jany';
        $admin->email = 'jany@ity.com';
        $admin->nickname = 'Jany';
        $admin->phone = '010422825';
        $admin->create_user = 0;
        $admin->avatar = 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif';
        $admin->api_token = Str::random(32);
        $admin->status = 1;
        $admin->token_expire_time =  time() + 24 * 3600;
        $admin->remember_token = Str::random(10);
        $admin->password = bcrypt('123456');
        $admin->created_at = $createdAt ?: $createAt =\Carbon\Carbon::now()
                                                        ->subDays(rand(1, 100))
                                                        ->subHours(rand(1, 23))
                                                        ->subMinutes(rand(1, 60));
        $admin->updated_at = $createdAt ?: $createdAt = \Carbon\Carbon::now()
                                                        ->subDays(rand(1, 100))
                                                        ->subHours(rand(1, 23))
                                                        ->subMinutes(rand(1, 60));
        $admin->save();
    }

}
