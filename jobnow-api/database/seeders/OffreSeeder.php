<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offre;
use App\Models\Entreprise;
use App\Models\Ville;

class OffreSeeder extends Seeder
{
    public function run(): void
    {
        // Get first entreprise and ville, or create them if they don't exist
        $entreprise = Entreprise::first();
        $ville = Ville::first();

        if (!$entreprise || !$ville) {
            $this->command->error('Please create at least one Entreprise and Ville first!');
            return;
        }

        $jobs = [
            ['titre' => 'React.js Developer', 'type' => 'CDI', 'salaire' => '45000 - 65000 MAD'],
            ['titre' => 'Angular Developer', 'type' => 'CDI', 'salaire' => '42000 - 60000 MAD'],
            ['titre' => 'Laravel Developer', 'type' => 'CDI', 'salaire' => '40000 - 58000 MAD'],
            ['titre' => '.NET Developer', 'type' => 'CDI', 'salaire' => '48000 - 70000 MAD'],
            ['titre' => 'MERN Stack Developer', 'type' => 'CDI', 'salaire' => '50000 - 75000 MAD'],
            ['titre' => 'Flutter Developer', 'type' => 'CDD', 'salaire' => '38000 - 55000 MAD'],
            ['titre' => 'React Native Developer', 'type' => 'CDI', 'salaire' => '43000 - 62000 MAD'],
            ['titre' => 'Vue.js Developer', 'type' => 'CDI', 'salaire' => '41000 - 59000 MAD'],
            ['titre' => 'Node.js Backend Developer', 'type' => 'CDI', 'salaire' => '46000 - 67000 MAD'],
            ['titre' => 'Python Django Developer', 'type' => 'CDI', 'salaire' => '44000 - 64000 MAD'],
            ['titre' => 'Java Spring Boot Developer', 'type' => 'CDI', 'salaire' => '47000 - 68000 MAD'],
            ['titre' => 'PHP Symfony Developer', 'type' => 'CDD', 'salaire' => '39000 - 56000 MAD'],
            ['titre' => 'Full Stack JavaScript Developer', 'type' => 'CDI', 'salaire' => '48000 - 70000 MAD'],
            ['titre' => 'DevOps Engineer', 'type' => 'CDI', 'salaire' => '55000 - 80000 MAD'],
            ['titre' => 'Mobile App Developer (iOS/Android)', 'type' => 'CDI', 'salaire' => '45000 - 66000 MAD'],
            ['titre' => 'WordPress Developer', 'type' => 'Freelance', 'salaire' => '30000 - 45000 MAD'],
            ['titre' => 'Shopify Developer', 'type' => 'Freelance', 'salaire' => '32000 - 48000 MAD'],
            ['titre' => 'UI/UX Designer & Frontend Developer', 'type' => 'CDI', 'salaire' => '40000 - 58000 MAD'],
            ['titre' => 'Backend API Developer', 'type' => 'CDI', 'salaire' => '46000 - 67000 MAD'],
            ['titre' => 'Cloud Solutions Architect', 'type' => 'CDI', 'salaire' => '60000 - 90000 MAD'],
            ['titre' => 'Data Engineer', 'type' => 'CDI', 'salaire' => '52000 - 78000 MAD'],
            ['titre' => 'Machine Learning Engineer', 'type' => 'CDI', 'salaire' => '58000 - 85000 MAD'],
            ['titre' => 'QA Automation Engineer', 'type' => 'CDD', 'salaire' => '38000 - 54000 MAD'],
            ['titre' => 'Cybersecurity Specialist', 'type' => 'CDI', 'salaire' => '55000 - 82000 MAD'],
            ['titre' => 'Blockchain Developer', 'type' => 'CDI', 'salaire' => '60000 - 95000 MAD'],
            ['titre' => 'Game Developer (Unity)', 'type' => 'CDD', 'salaire' => '42000 - 60000 MAD'],
            ['titre' => 'Embedded Systems Developer', 'type' => 'CDI', 'salaire' => '48000 - 70000 MAD'],
            ['titre' => 'Salesforce Developer', 'type' => 'CDI', 'salaire' => '50000 - 75000 MAD'],
            ['titre' => 'SAP Developer', 'type' => 'CDI', 'salaire' => '55000 - 80000 MAD'],
            ['titre' => 'iOS Developer (Swift)', 'type' => 'CDI', 'salaire' => '44000 - 65000 MAD'],
            ['titre' => 'Android Developer (Kotlin)', 'type' => 'CDI', 'salaire' => '43000 - 63000 MAD'],
            ['titre' => 'GraphQL API Developer', 'type' => 'CDD', 'salaire' => '45000 - 66000 MAD'],
            ['titre' => 'Microservices Architect', 'type' => 'CDI', 'salaire' => '58000 - 88000 MAD'],
            ['titre' => 'Frontend Developer (HTML/CSS/JS)', 'type' => 'Stage', 'salaire' => '3000 - 5000 MAD'],
            ['titre' => 'Junior Full Stack Developer', 'type' => 'Stage', 'salaire' => '3500 - 6000 MAD'],
            ['titre' => 'Senior Software Engineer', 'type' => 'CDI', 'salaire' => '65000 - 100000 MAD'],
            ['titre' => 'Technical Lead', 'type' => 'CDI', 'salaire' => '70000 - 110000 MAD'],
            ['titre' => 'Scrum Master / Agile Coach', 'type' => 'CDI', 'salaire' => '50000 - 75000 MAD'],
            ['titre' => 'Product Manager (Tech)', 'type' => 'CDI', 'salaire' => '55000 - 85000 MAD'],
            ['titre' => 'Site Reliability Engineer (SRE)', 'type' => 'CDI', 'salaire' => '56000 - 83000 MAD'],
        ];

        $descriptions = [
            'Nous recherchons un développeur passionné pour rejoindre notre équipe dynamique.',
            'Rejoignez une startup innovante et participez à des projets ambitieux.',
            'Opportunité unique de travailler sur des technologies de pointe.',
            'Environnement de travail collaboratif avec des défis techniques stimulants.',
            'Contribuez à la transformation digitale de notre entreprise.',
        ];

        $competences = [
            ['Git', 'Agile', 'REST API', 'Docker'],
            ['CI/CD', 'Testing', 'Clean Code', 'Design Patterns'],
            ['Microservices', 'Cloud', 'Security', 'Performance'],
            ['Team Collaboration', 'Problem Solving', 'Communication'],
        ];

        foreach ($jobs as $index => $job) {
            Offre::create([
                'entreprise_id' => $entreprise->id,
                'titre' => $job['titre'],
                'description' => $descriptions[$index % count($descriptions)],
                'competences_requises' => $competences[$index % count($competences)],
                'ville_id' => $ville->id,
                'salaire' => $job['salaire'],
                'type_contrat' => $job['type'],
                'date_expiration' => now()->addMonths(2),
                'statut' => 'ouverte',
            ]);
        }

        $this->command->info('40 job offers created successfully!');
    }
}
