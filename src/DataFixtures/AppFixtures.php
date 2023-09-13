<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Season;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OflixProvider;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Fonction qui va s'executer quand on va charger les fixtures (envoyer les données en bdd)
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // On crée une instance de Faker 
        // Lors de l'instanciation on définit la localisation => français
        // Ici on créer un faker français tt simplement
        $faker = Factory::create('fr_FR');

        // On instancie notre Provider (fournisseur de données) OflixProvider
        $provider = new OflixProvider();

        //  ! USERS
        $admin = new User;
        $admin->setEmail("admin@oclock.io");
        $admin->setRoles(["ROLE_ADMIN"]);
        // ici j'utilise le passwordhasher pour hasher le mot de passe par rapport à mes infos dans le security.yaml
        // ! SI PAS DE HASH, L'auth ne peut pas marcher
        $admin->setPassword($this->passwordHasher->hashPassword($admin, "admin"));

        $manager->persist($admin);

        $userManager = new User;
        $userManager->setEmail("manager@oclock.io");
        $userManager->setRoles(["ROLE_MANAGER"]);
        // ici j'utilise le passwordhasher pour hasher le mot de passe par rapport à mes infos dans le security.yaml
        // ! SI PAS DE HASH, L'auth ne peut pas marcher
        $userManager->setPassword($this->passwordHasher->hashPassword($admin, "manager"));

        $manager->persist($userManager);

        $user = new User;
        $user->setEmail("user@oclock.io");
        $user->setRoles(["ROLE_USER"]);
        // ici j'utilise le passwordhasher pour hasher le mot de passe par rapport à mes infos dans le security.yaml
        // ! SI PAS DE HASH, L'auth ne peut pas marcher
        $user->setPassword($this->passwordHasher->hashPassword($admin, "user"));

        $manager->persist($user);
        
        

        // un tableau vide pour stocker nos genre
        $genreList = [];

        // Boucle 20 fois pour créer 20 genres
        for ($i = 1; $i <= 20; $i++) {
            // on crée une entité
            $genre = new Genre();
            // on lui définit un nom qu'on récupère depuis le provider
            $genre->setName($provider->movieGenre());

            // on l'ajoute à notre tableau $genreList[]
            $genreList[] = $genre;

            // on persist l'entité, avec l'entity manager fourni
            $manager->persist($genre);
        }

        // On créer un tableau ou on va ajouter 100 acteurs
        $personList = [];
        // On ajoute 100 Person (Acteurs)
        for ($p = 1; $p <= 100; $p++) {
            // Nouvelle Person
            $person = new Person();
            // On définit son prenom à l'aide de faker
            $person->setFirstname($faker->firstName());
            // On définit son Nom à l'aide de faker
            $person->setLastname($faker->lastName());
            $personList[] = $person;
            // On persiste
            $manager->persist($person);
        }

        // dump($genreList);

        // On boucle 20 fois pour créer 20 films/séries
        for ($m = 1; $m <= 20; $m++) {
            // On créer une instance de l'entité Movie (On créer un film)
            $movie = new Movie();
            // on définit un titre au film à l'aide du provider
            $movie->setTitle($provider->movieTitle());
            // 1 chance sur 2 que ce soit un film => 'Film' ou 'Série', on utilise random Element de faker
            // https://fakerphp.github.io/formatters/numbers-and-strings/#randomelement
            $type = $faker->randomElement(['Film', 'Série']);

            // Définit le type
            $movie->setType($type);

            // Définit la date de sortie en utilisant faker => sortie il y a 100 ans
            // https://fakerphp.github.io/formatters/date-and-time/#datetimebetween
            $movie->setReleaseDate($faker->dateTimeBetween('-100 year'));
            // Définit la durée avec faker
            // https://fakerphp.github.io/formatters/numbers-and-strings/#numberbetween
            $movie->setDuration($faker->numberBetween(60, 120));
            // Définit l'image du film avec faker
            // https://fakerphp.github.io/formatters/image/#imageurl
            $movie->setPoster("https://source.unsplash.com/random/?funny");
            // Définit une note aec faker
            // https://fakerphp.github.io/formatters/numbers-and-strings/#randomfloat
            $movie->setRating($faker->randomFloat(1, 1, 5));
            // Définit un court résumé avec faker
            // https://fakerphp.github.io/formatters/text-and-paragraphs/#text
            $movie->setSummary($faker->text(100));
            // Définit un résumé aec faker
            $movie->setSynopsis($faker->text(200));

            // ici on va associer un ou des genres au film courant
            // par ex. le 1er de la liste
            // @todo à randomiser : par ex. de 1 à 3 genres au hasard
            $movie->addGenre($genreList[0]);

            // les saisons POUR LES SAISONS
            // @todo à randomiser : par ex. un nombre de saions au hasard avec nbres d'épisodes au hasard
            $season = new Season();
            $season->setNumber(1);
            $season->setEpisodesNumber(6);
            // on l'associe à la série
            $season->setMovie($movie);
            // on persiste la saison
            $manager->persist($season);

            // On ajoute 3 à 5 casting par film créée au hasard 
            for ($c = 1; $c <= mt_rand(3, 5); $c++) {
                // On créer une instance de l'entité Casting
                $casting = new Casting();
                // On définit le rôle de ce casting avec la methode name() de faker
                // https://fakerphp.github.io/formatters/#fakerprovideren_usperson
                $casting->setRole($faker->name());
                // On définit l'ordre d'importance de ce casting
                $casting->setCreditOrder($c);
                // On définit les 2 associations de Casting : movie et person
                $casting->setMovie($movie);
                $casting->setPerson($personList[$c]);
                $manager->persist($casting);
            }

            // ! REVIEWS
            for ($r=0; $r < mt_rand(0,5); $r++) { 
                $review = new Review();

                $review->setUsername($faker->userName());
                $review->setEmail($faker->email());
                $review->setContent($faker->paragraph());
                $review->setRating(mt_rand(0,5));
                $review->setWatchedAt(new \DateTimeImmutable($faker->date()));
                $review->setReactions($faker->randomElements(["smile","cry","think","sleep","dream"],mt_rand(1,5)));

                $movie->addReview($review);

                $manager->persist($review);

            }
            $manager->persist($movie);
        }


        $manager->flush();
    }
}
