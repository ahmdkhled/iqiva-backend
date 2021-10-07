<?php

namespace Database\Seeders;

use App\Models\Consent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(LaratrustSeeder::class);
        $this->call(UsersTableSeeder::class);

          //   Consent::factory(150)->create();
         // \App\Models\User::factory(10)->create();
    }
}
