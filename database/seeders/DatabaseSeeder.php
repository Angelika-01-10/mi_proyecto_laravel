<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Primero roles
        $this->call(RoleSeeder::class);

        // Después usuarios
        $this->call(UserSeeder::class);
    }
}
