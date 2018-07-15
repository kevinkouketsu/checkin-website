<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'Kevin Kouketsu',
            'username' => str_random(30),
            'email' => 'kevinkouketsum@hotmail.com',
            'password' => bcrypt('kevin123'),
            'graduate_id' => '1'
        ]);

        DB::table('users')->insert([
            'name' => 'Fumiko Kouketsu',
            'username' => str_random(30),
            'email' => 'fumikokouketsu@fumikokouketsu.com',
            'password' => bcrypt('kevin123'),
            'graduate_id' => '2'
        ]);

        DB::table('users')->insert([
            'name' => 'Joel Machado',
            'username' => str_random(30),
            'email' => 'distrimachrep@yhoo.com.br',
            'password' => bcrypt('kevin123'),
            'graduate_id' => '2'
        ]);
        
    }
}
