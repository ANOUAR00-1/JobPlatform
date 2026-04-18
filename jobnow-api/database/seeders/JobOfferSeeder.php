<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Ville;
use App\Models\Offre;

class JobOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Check or Create a dummy Entreprise
        $entreprise = Entreprise::first();
        if (!$entreprise) {
            // First, create the user who owns the entreprise
            $user = User::create([
                'email' => 'hr@techcorp.ma',
                'password' => bcrypt('password123'),
                'role' => 'entreprise',
            ]);

            // Then create the Entreprise identity
            $entreprise = Entreprise::create([
                'user_id' => $user->id,
                'raison_social' => 'Tech Corp Maroc',
                'adresse' => 'Twin Center, Casablanca',
                'telephone' => '+212 600 000000',
                'logo_url' => 'https://images.unsplash.com/photo-1549923746-c50264f3079e?auto=format&fit=crop&w=200&h=200',
            ]);
        }

        // 2. Setup Villes (Moroccan Cities)
        $cities = ['Casablanca', 'Rabat', 'Tanger', 'Marrakech'];
        $villes = [];
        foreach ($cities as $cityName) {
            $villes[$cityName] = Ville::firstOrCreate(['nom' => $cityName]);
        }

        // 3. Define 40 realistic developer jobs for the Moroccan market
        $jobs = [
            ['titre' => 'React.js Developer', 'description' => 'Développeur React.js expérimenté pour créer des interfaces utilisateur modernes et réactives.', 'ville_name' => 'Casablanca', 'salaire' => '15 000 - 22 000 DH', 'competences' => ['React', 'JavaScript', 'Redux', 'REST API']],
            ['titre' => 'Angular Developer', 'description' => 'Missions longue durée sur de grands projets bancaires marocains avec Angular 15+.', 'ville_name' => 'Rabat', 'salaire' => '14 000 - 20 000 DH', 'competences' => ['Angular', 'TypeScript', 'RxJS', 'SCSS']],
            ['titre' => 'Laravel Developer', 'description' => 'Développeur Laravel passionné pour créer des APIs robustes et scalables.', 'ville_name' => 'Casablanca', 'salaire' => '12 000 - 18 000 DH', 'competences' => ['Laravel', 'PHP', 'MySQL', 'REST API']],
            ['titre' => '.NET Developer', 'description' => 'Développeur .NET Core pour applications d\'entreprise complexes et performantes.', 'ville_name' => 'Rabat', 'salaire' => '16 000 - 24 000 DH', 'competences' => ['.NET Core', 'C#', 'SQL Server', 'Azure']],
            ['titre' => 'MERN Stack Developer', 'description' => 'Full-stack MERN pour startup SaaS en forte croissance.', 'ville_name' => 'Marrakech', 'salaire' => '17 000 - 25 000 DH', 'competences' => ['MongoDB', 'Express', 'React', 'Node.js']],
            ['titre' => 'Flutter Developer', 'description' => 'Créez des applications mobiles cross-platform avec Flutter et Dart.', 'ville_name' => 'Tanger', 'salaire' => '13 000 - 19 000 DH', 'competences' => ['Flutter', 'Dart', 'Firebase', 'Mobile']],
            ['titre' => 'React Native Developer', 'description' => 'Applications mobiles hybrides performantes pour iOS et Android.', 'ville_name' => 'Casablanca', 'salaire' => '14 000 - 21 000 DH', 'competences' => ['React Native', 'Mobile', 'Redux', 'APIs']],
            ['titre' => 'Vue.js Developer', 'description' => 'Développeur Vue.js pour interfaces web modernes et performantes.', 'ville_name' => 'Rabat', 'salaire' => '13 000 - 19 000 DH', 'competences' => ['Vue.js', 'Vuex', 'JavaScript', 'Nuxt']],
            ['titre' => 'Node.js Backend Developer', 'description' => 'Architecte backend Node.js pour APIs RESTful et microservices.', 'ville_name' => 'Casablanca', 'salaire' => '15 000 - 23 000 DH', 'competences' => ['Node.js', 'Express', 'MongoDB', 'Docker']],
            ['titre' => 'Python Django Developer', 'description' => 'Développeur Python Django pour applications web robustes.', 'ville_name' => 'Marrakech', 'salaire' => '14 000 - 21 000 DH', 'competences' => ['Python', 'Django', 'PostgreSQL', 'REST']],
            ['titre' => 'Java Spring Boot Developer', 'description' => 'Développeur Java Spring Boot pour systèmes d\'entreprise.', 'ville_name' => 'Rabat', 'salaire' => '16 000 - 24 000 DH', 'competences' => ['Java', 'Spring Boot', 'Hibernate', 'MySQL']],
            ['titre' => 'PHP Symfony Developer', 'description' => 'Expert Symfony pour applications web complexes et évolutives.', 'ville_name' => 'Casablanca', 'salaire' => '13 000 - 19 000 DH', 'competences' => ['PHP', 'Symfony', 'Doctrine', 'MySQL']],
            ['titre' => 'Full Stack JavaScript Developer', 'description' => 'Développeur full-stack JavaScript moderne (MEVN/MEAN).', 'ville_name' => 'Tanger', 'salaire' => '16 000 - 24 000 DH', 'competences' => ['JavaScript', 'Vue/React', 'Node.js', 'MongoDB']],
            ['titre' => 'DevOps Engineer', 'description' => 'Ingénieur DevOps pour infrastructure cloud et CI/CD.', 'ville_name' => 'Casablanca', 'salaire' => '18 000 - 30 000 DH', 'competences' => ['Docker', 'Kubernetes', 'AWS', 'CI/CD']],
            ['titre' => 'Mobile App Developer (iOS/Android)', 'description' => 'Développeur mobile natif iOS et Android expérimenté.', 'ville_name' => 'Rabat', 'salaire' => '15 000 - 23 000 DH', 'competences' => ['Swift', 'Kotlin', 'iOS', 'Android']],
            ['titre' => 'WordPress Developer', 'description' => 'Expert WordPress pour sites web et e-commerce personnalisés.', 'ville_name' => 'Marrakech', 'salaire' => '10 000 - 15 000 DH', 'competences' => ['WordPress', 'PHP', 'WooCommerce', 'MySQL']],
            ['titre' => 'Shopify Developer', 'description' => 'Développeur Shopify pour boutiques en ligne performantes.', 'ville_name' => 'Casablanca', 'salaire' => '11 000 - 16 000 DH', 'competences' => ['Shopify', 'Liquid', 'JavaScript', 'CSS']],
            ['titre' => 'UI/UX Designer & Frontend Developer', 'description' => 'Designer-développeur pour expériences utilisateur exceptionnelles.', 'ville_name' => 'Tanger', 'salaire' => '13 000 - 20 000 DH', 'competences' => ['Figma', 'HTML/CSS', 'JavaScript', 'UI/UX']],
            ['titre' => 'Backend API Developer', 'description' => 'Architecte d\'APIs RESTful et GraphQL performantes.', 'ville_name' => 'Rabat', 'salaire' => '15 000 - 23 000 DH', 'competences' => ['REST', 'GraphQL', 'Node.js', 'PostgreSQL']],
            ['titre' => 'Cloud Solutions Architect', 'description' => 'Architecte cloud pour solutions AWS/Azure évolutives.', 'ville_name' => 'Casablanca', 'salaire' => '20 000 - 35 000 DH', 'competences' => ['AWS', 'Azure', 'Architecture', 'Terraform']],
            ['titre' => 'Data Engineer', 'description' => 'Ingénieur data pour pipelines ETL et data warehousing.', 'ville_name' => 'Casablanca', 'salaire' => '17 000 - 28 000 DH', 'competences' => ['Python', 'Spark', 'SQL', 'ETL']],
            ['titre' => 'Machine Learning Engineer', 'description' => 'Ingénieur ML pour modèles prédictifs et IA.', 'ville_name' => 'Rabat', 'salaire' => '19 000 - 32 000 DH', 'competences' => ['Python', 'TensorFlow', 'ML', 'Data Science']],
            ['titre' => 'QA Automation Engineer', 'description' => 'Ingénieur QA pour automatisation des tests.', 'ville_name' => 'Tanger', 'salaire' => '12 000 - 18 000 DH', 'competences' => ['Selenium', 'Cypress', 'Testing', 'CI/CD']],
            ['titre' => 'Cybersecurity Specialist', 'description' => 'Expert sécurité pour protection des systèmes et données.', 'ville_name' => 'Casablanca', 'salaire' => '18 000 - 30 000 DH', 'competences' => ['Security', 'Penetration Testing', 'SIEM', 'Firewall']],
            ['titre' => 'Blockchain Developer', 'description' => 'Développeur blockchain pour smart contracts et DApps.', 'ville_name' => 'Rabat', 'salaire' => '20 000 - 35 000 DH', 'competences' => ['Solidity', 'Ethereum', 'Web3', 'Smart Contracts']],
            ['titre' => 'Game Developer (Unity)', 'description' => 'Développeur de jeux Unity pour mobile et desktop.', 'ville_name' => 'Marrakech', 'salaire' => '14 000 - 22 000 DH', 'competences' => ['Unity', 'C#', 'Game Design', '3D']],
            ['titre' => 'Embedded Systems Developer', 'description' => 'Développeur systèmes embarqués pour IoT et hardware.', 'ville_name' => 'Casablanca', 'salaire' => '16 000 - 25 000 DH', 'competences' => ['C/C++', 'Embedded', 'IoT', 'RTOS']],
            ['titre' => 'Salesforce Developer', 'description' => 'Expert Salesforce pour CRM et automatisation.', 'ville_name' => 'Rabat', 'salaire' => '17 000 - 27 000 DH', 'competences' => ['Salesforce', 'Apex', 'Lightning', 'CRM']],
            ['titre' => 'SAP Developer', 'description' => 'Développeur SAP pour solutions ERP d\'entreprise.', 'ville_name' => 'Casablanca', 'salaire' => '18 000 - 30 000 DH', 'competences' => ['SAP', 'ABAP', 'ERP', 'Fiori']],
            ['titre' => 'iOS Developer (Swift)', 'description' => 'Développeur iOS natif avec Swift et SwiftUI.', 'ville_name' => 'Tanger', 'salaire' => '15 000 - 23 000 DH', 'competences' => ['Swift', 'SwiftUI', 'iOS', 'Xcode']],
            ['titre' => 'Android Developer (Kotlin)', 'description' => 'Développeur Android natif avec Kotlin et Jetpack.', 'ville_name' => 'Rabat', 'salaire' => '14 000 - 22 000 DH', 'competences' => ['Kotlin', 'Android', 'Jetpack', 'Material']],
            ['titre' => 'GraphQL API Developer', 'description' => 'Expert GraphQL pour APIs modernes et performantes.', 'ville_name' => 'Casablanca', 'salaire' => '15 000 - 23 000 DH', 'competences' => ['GraphQL', 'Apollo', 'Node.js', 'TypeScript']],
            ['titre' => 'Microservices Architect', 'description' => 'Architecte microservices pour systèmes distribués.', 'ville_name' => 'Casablanca', 'salaire' => '19 000 - 33 000 DH', 'competences' => ['Microservices', 'Docker', 'Kubernetes', 'API Gateway']],
            ['titre' => 'Frontend Developer (HTML/CSS/JS)', 'description' => 'Développeur frontend junior pour interfaces web modernes.', 'ville_name' => 'Marrakech', 'salaire' => '8 000 - 12 000 DH', 'competences' => ['HTML', 'CSS', 'JavaScript', 'Responsive']],
            ['titre' => 'Junior Full Stack Developer', 'description' => 'Développeur full-stack junior pour projets web variés.', 'ville_name' => 'Tanger', 'salaire' => '9 000 - 14 000 DH', 'competences' => ['HTML/CSS', 'JavaScript', 'PHP/Node', 'MySQL']],
            ['titre' => 'Senior Software Engineer', 'description' => 'Ingénieur logiciel senior pour architecture et leadership technique.', 'ville_name' => 'Casablanca', 'salaire' => '22 000 - 40 000 DH', 'competences' => ['Architecture', 'Leadership', 'Design Patterns', 'Agile']],
            ['titre' => 'Technical Lead', 'description' => 'Lead technique pour encadrement d\'équipe et architecture.', 'ville_name' => 'Rabat', 'salaire' => '24 000 - 45 000 DH', 'competences' => ['Leadership', 'Architecture', 'Mentoring', 'Agile']],
            ['titre' => 'Scrum Master / Agile Coach', 'description' => 'Scrum Master pour transformation agile et coaching d\'équipes.', 'ville_name' => 'Casablanca', 'salaire' => '17 000 - 28 000 DH', 'competences' => ['Scrum', 'Agile', 'Coaching', 'Jira']],
            ['titre' => 'Product Manager (Tech)', 'description' => 'Product Manager technique pour produits digitaux innovants.', 'ville_name' => 'Rabat', 'salaire' => '18 000 - 32 000 DH', 'competences' => ['Product Management', 'Agile', 'UX', 'Analytics']],
            ['titre' => 'Site Reliability Engineer (SRE)', 'description' => 'SRE pour fiabilité et performance des systèmes en production.', 'ville_name' => 'Casablanca', 'salaire' => '19 000 - 33 000 DH', 'competences' => ['SRE', 'Monitoring', 'Kubernetes', 'Automation']],
        ];

        // 4. Seed the roles
        foreach ($jobs as $job) {
            Offre::create([
                'entreprise_id' => $entreprise->id,
                'titre' => $job['titre'],
                'description' => $job['description'],
                'competences_requises' => $job['competences'],
                'ville_id' => $villes[$job['ville_name']]->id,
                'salaire' => $job['salaire'],
                'type_contrat' => array_rand(array_flip(['CDI', 'CDD', 'Freelance'])),
                'date_expiration' => now()->addDays(rand(15, 60)),
                'statut' => 'ouverte',
            ]);
        }
    }
}
