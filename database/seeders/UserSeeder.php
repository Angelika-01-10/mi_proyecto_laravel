<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrador', 'password' => bcrypt('password')]
        );
        $admin->assignRole('administrador');

        // Dermatólogo
        $dermatologo = User::firstOrCreate(
            ['email' => 'dermatologo@example.com'],
            ['name' => 'Dermatólogo', 'password' => bcrypt('password')]
        );
        $dermatologo->assignRole('dermatologo');

        // Paciente
        $paciente = User::firstOrCreate(
            ['email' => 'paciente@example.com'],
            ['name' => 'Paciente', 'password' => bcrypt('password')]
        );
        $paciente->assignRole('paciente');

    }
}
