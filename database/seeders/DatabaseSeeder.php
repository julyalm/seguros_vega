<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Emmanuel Vega (Admin)',
            'email' => 'admin@segurosvega.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Agentes
        User::create([
            'name' => 'Carlos Mendoza (Agente)',
            'email' => 'agente1@segurosvega.com',
            'password' => bcrypt('password'),
            'role' => 'agente',
        ]);

        User::create([
            'name' => 'Laura Torres (Agente)',
            'email' => 'agente2@segurosvega.com',
            'password' => bcrypt('password'),
            'role' => 'agente',
        ]);

        // Catálogo de Aseguradoras
        $aseguradoras = [
            ['nombre' => 'Qualitas',  'color' => '#003366', 'inicial' => 'Q'],
            ['nombre' => 'Chubb',     'color' => '#E31837', 'inicial' => 'C'],
            ['nombre' => 'HDI',       'color' => '#005F9E', 'inicial' => 'H'],
            ['nombre' => 'ANA',       'color' => '#004990', 'inicial' => 'A'],
            ['nombre' => 'Afirme',    'color' => '#007934', 'inicial' => 'AF'],
        ];

        foreach ($aseguradoras as $aseg) {
            \Illuminate\Support\Facades\DB::table('aseguradoras')->insert([
                'nombre' => $aseg['nombre'],
                'color' => $aseg['color'],
                'inicial' => $aseg['inicial'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
