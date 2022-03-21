<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'yacoub',
            'email' => 'yacoubdemire1@outlook.fr',
            'password' => bcrypt('123456'),
        ]);
    }
}
