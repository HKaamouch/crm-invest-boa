<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@bankofafrica.com'],
            [
                'name' => 'Admin',
                'nom_complet' => 'Administrateur Système',
                'telephone' => '+212 5 37 57 20 20',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Administrateur');

        // Créer un utilisateur éditeur
        $editeur = User::firstOrCreate(
            ['email' => 'houda@bankofafrica.com'],
            [
            'name' => 'Houda',
            'nom_complet' => 'Houda Benali',
            'email' => 'houda@bankofafrica.com',
            'telephone' => '+212 5 37 57 20 21',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $editeur->assignRole('Éditeur');

        // Créer un utilisateur lecture seule
        $lecteur = User::firstOrCreate(
            ['email' => 'ahmed@bankofafrica.com'],
            [
            'name' => 'Ahmed',
            'nom_complet' => 'Ahmed Alami',
            'email' => 'ahmed@bankofafrica.com',
            'telephone' => '+212 5 37 57 20 22',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $lecteur->assignRole('Lecture seule');
    }
}
