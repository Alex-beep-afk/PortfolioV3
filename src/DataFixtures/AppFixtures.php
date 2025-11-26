<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Diplomas;
use App\Entity\Experience;
use App\Entity\Project;
use App\Entity\Techno;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'utilisateur Alexandre Prigent
        $user = new User();
        $user->setUsername('alexandre.prigent');
        $user->setFirstName('Alexandre');
        $user->setLastName('Prigent');
        $user->setEmail('alexandre.prigent@example.com');
        $user->setPhone('+33612345678');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new DateTimeImmutable());
        
        $manager->persist($user);

        // Création des diplômes
        $diploma1 = new Diplomas();
        $diploma1->setTitle('Master en Informatique');
        $diploma1->setSchool('Université de Technologie');
        $diploma1->setCity('Paris');
        $diploma1->setDateStart(new DateTimeImmutable('2020-09-01'));
        $diploma1->setDateEnd(new DateTimeImmutable('2022-06-30'));
        $diploma1->setUser($user);
        $manager->persist($diploma1);

        $diploma2 = new Diplomas();
        $diploma2->setTitle('Licence en Développement Web');
        $diploma2->setSchool('École Supérieure du Numérique');
        $diploma2->setCity('Lyon');
        $diploma2->setDateStart(new DateTimeImmutable('2017-09-01'));
        $diploma2->setDateEnd(new DateTimeImmutable('2020-06-30'));
        $diploma2->setUser($user);
        $manager->persist($diploma2);

        $diploma3 = new Diplomas();
        $diploma3->setTitle('Baccalauréat Scientifique');
        $diploma3->setSchool('Lycée Victor Hugo');
        $diploma3->setCity('Marseille');
        $diploma3->setDateStart(new DateTimeImmutable('2014-09-01'));
        $diploma3->setDateEnd(new DateTimeImmutable('2017-06-30'));
        $diploma3->setUser($user);
        $manager->persist($diploma3);

        // Création des expériences
        $experience1 = new Experience();
        $experience1->setTitle('Développeur Full Stack Senior');
        $experience1->setBusiness('TechCorp Solutions');
        $experience1->setCity('Paris');
        $experience1->setDescription('Développement d\'applications web complexes avec Symfony et React. Gestion d\'équipe de 5 développeurs et architecture de solutions scalables.');
        $experience1->setDateStart(new DateTimeImmutable('2022-07-01'));
        $experience1->setDateEnd(null); // Poste actuel
        $experience1->setUser($user);
        $manager->persist($experience1);

        $experience2 = new Experience();
        $experience2->setTitle('Développeur Backend');
        $experience2->setBusiness('StartupInnov');
        $experience2->setCity('Lyon');
        $experience2->setDescription('Développement d\'APIs REST avec Symfony et intégration de services tiers. Optimisation des performances et mise en place de tests automatisés.');
        $experience2->setDateStart(new DateTimeImmutable('2020-09-01'));
        $experience2->setDateEnd(new DateTimeImmutable('2022-06-30'));
        $experience2->setUser($user);
        $manager->persist($experience2);

        $experience3 = new Experience();
        $experience3->setTitle('Développeur Web Junior');
        $experience3->setBusiness('WebAgency Pro');
        $experience3->setCity('Marseille');
        $experience3->setDescription('Développement de sites web avec PHP et JavaScript. Maintenance et évolution de sites existants pour divers clients.');
        $experience3->setDateStart(new DateTimeImmutable('2018-07-01'));
        $experience3->setDateEnd(new DateTimeImmutable('2020-08-31'));
        $experience3->setUser($user);
        $manager->persist($experience3);

        // Création des technologies
        $technos = [];
        $technoNames = ['PHP', 'Symfony', 'JavaScript', 'React', 'TypeScript', 'Node.js', 'MySQL', 'PostgreSQL', 'Docker', 'Git'];
        
        foreach ($technoNames as $technoName) {
            $techno = new Techno();
            $techno->setName($technoName);
            $manager->persist($techno);
            $technos[$technoName] = $techno;
        }

        // Création des projets
        $project1 = new Project();
        $project1->setCustomerName('E-Commerce Platform');
        $project1->setDescription('Plateforme e-commerce complète avec gestion de commandes, paiements en ligne et système de recommandations. Interface admin pour la gestion des produits et des utilisateurs.');
        $project1->setDificulties('Gestion de la montée en charge, optimisation des requêtes SQL complexes, intégration de multiples APIs de paiement.');
        $project1->setLinkToProject('https://github.com/alexandre-prigent/ecommerce-platform');
        $project1->setUser($user);
        $project1->addTechno($technos['PHP']);
        $project1->addTechno($technos['Symfony']);
        $project1->addTechno($technos['MySQL']);
        $project1->addTechno($technos['React']);
        $project1->addTechno($technos['Docker']);
        $manager->persist($project1);

        $project2 = new Project();
        $project2->setCustomerName('API REST pour Mobile App');
        $project2->setDescription('API REST sécurisée pour application mobile de gestion de tâches. Authentification JWT, gestion des utilisateurs et synchronisation en temps réel.');
        $project2->setDificulties('Sécurisation des endpoints, gestion de la synchronisation offline/online, optimisation pour les connexions lentes.');
        $project2->setLinkToProject('https://github.com/alexandre-prigent/task-api');
        $project2->setUser($user);
        $project2->addTechno($technos['Symfony']);
        $project2->addTechno($technos['PostgreSQL']);
        $project2->addTechno($technos['Docker']);
        $project2->addTechno($technos['Git']);
        $manager->persist($project2);

        $project3 = new Project();
        $project3->setCustomerName('Dashboard Analytics');
        $project3->setDescription('Tableau de bord analytique avec visualisations de données en temps réel. Intégration de graphiques interactifs et export de rapports PDF/Excel.');
        $project3->setDificulties('Traitement de grandes quantités de données, génération de rapports complexes, optimisation des performances de rendu des graphiques.');
        $project3->setLinkToProject('https://github.com/alexandre-prigent/analytics-dashboard');
        $project3->setUser($user);
        $project3->addTechno($technos['TypeScript']);
        $project3->addTechno($technos['React']);
        $project3->addTechno($technos['Node.js']);
        $project3->addTechno($technos['PostgreSQL']);
        $project3->addTechno($technos['Docker']);
        $manager->persist($project3);

        $manager->flush();
    }
}
