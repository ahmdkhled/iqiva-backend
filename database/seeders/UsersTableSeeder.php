<?php

namespace Database\Seeders;

use App\Models\User;
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

        $user = User::create([
            'name' => 'super admin',
            'email' => 'super_admin@app.com',
            'password' => bcrypt('password'),
        ]);

        $user->attachRole('super_admin');

        $user = User::create([
            'name' => 'supervisor',
            'email' => 'supervisor@app.com',
            'password' => bcrypt('password'),
        ]);

        $user->attachRole('supervisor');



        $user = User::create([
            'name' => 'PM Manager',
            'email' => 'pm_manager@app.com',
            'password' => bcrypt('password'),
        ]);

        $user->attachRole('agent');
        $user = User::create([
            'name' => 'Agent',
            'email' => 'agent@app.com',
            'password' => bcrypt('password'),
        ]);

        $user->attachRole('agent');

        $user = User::create([
            'name' => 'Data Entry',
            'email' => 'data_entry@app.com',
            'password' => bcrypt('password'),
        ]);

        $user->attachRole('data_entry');

    }//end of run

}//end of seeder
