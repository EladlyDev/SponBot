<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LanguagesTableSeeder::class);
        
        $this->call(CountriesTableSeeder::class);
        
        User::create([
            'name' => 'Mohamed Eladly',
            'email' => 'eladlydev@gmail.com',
            'password' => "password",
        ]);
        User::factory(10000)->create();
    }
}
