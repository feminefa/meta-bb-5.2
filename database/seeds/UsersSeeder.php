<?php
/**
 * Created by PhpStorm.
 * User: femi
 * Date: 4/3/2017
 * Time: 12:41 PM
 */
use Illuminate\Database\Seeder;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Femi Nefa',
            'email' => 'feminefa@gmail.com',
            'password' => bcrypt('asdfgh'),
            'level' =>1
        ]);
    }

}