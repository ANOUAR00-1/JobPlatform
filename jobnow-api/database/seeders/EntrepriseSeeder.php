<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entreprises = [
            [
                'raison_social' => 'TechNova Maroc',
                'email' => 'contact@technova.ma',
                'password' => Hash::make('password123'),
                'adresse' => 'Casablanca Nearshore Park, Sidi Maarouf',
                'telephone' => '+212 522 123 456',
                'logo_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=200&h=200'
            ],
            [
                'raison_social' => 'Atlas Solutions',
                'email' => 'rh@atlassolutions.ma',
                'password' => Hash::make('password123'),
                'adresse' => 'Avenue Mohammed VI, Rabat',
                'telephone' => '+212 537 987 654',
                'logo_url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=200&h=200'
            ],
            [
                'raison_social' => 'Casablanca Finance Group',
                'email' => 'careers@cfg.ma',
                'password' => Hash::make('password123'),
                'adresse' => 'Boulevard Al Massira Al Khadra, Casablanca',
                'telephone' => '+212 522 654 321',
                'logo_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=200&h=200'
            ],
            [
                'raison_social' => 'Digital Factory Menara',
                'email' => 'jobs@df-menara.ma',
                'password' => Hash::make('password123'),
                'adresse' => 'Gueliz, Marrakech',
                'telephone' => '+212 524 333 444',
                'logo_url' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=200&h=200'
            ],
            [
                'raison_social' => 'Tanger Tech Hub',
                'email' => 'hello@tangertech.ma',
                'password' => Hash::make('password123'),
                'adresse' => 'Tanger Free Zone, Tanger',
                'telephone' => '+212 539 111 222',
                'logo_url' => 'https://images.unsplash.com/photo-1497215848143-69e38e64c1bd?auto=format&fit=crop&w=200&h=200'
            ]
        ];

        foreach ($entreprises as $data) {
            // Check if user already exists
            $user = User::where('email', $data['email'])->first();
            
            if (!$user) {
                // Create the root User record
                $user = User::create([
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'role' => 'entreprise',
                ]);

                // Create the linked Entreprise record
                Entreprise::create([
                    'user_id' => $user->id,
                    'raison_social' => $data['raison_social'],
                    'adresse' => $data['adresse'],
                    'telephone' => $data['telephone'],
                    'logo_url' => $data['logo_url'],
                ]);
            }
        }
    }
}
