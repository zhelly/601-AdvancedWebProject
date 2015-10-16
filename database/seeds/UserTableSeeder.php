<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
		
		$user = new User;
		$user->id = 123;
		$user->username = 'Želmira Mikljan';
		$user->email = 'zelmira.mikljan@gmail.com';
		$user->save();
    }
}
