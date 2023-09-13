<?php

namespace App\Controller\Front;

use App\Entity\Genre;
use DateTime;
use App\Entity\Movie;
use App\Entity\Season;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieTestController extends AbstractController
{
    /**
     * @Route("/movie/test", name="app_movie_test")
     */
    public function index(): Response
    {
        return $this->render('movie_test/index.html.twig', [
            'controller_name' => 'MovieTestController',
        ]);
    }

    /**
     * Ajouter une entité dans la base de donnée
     * @Route("/movie/test/add", name="app_movie_test_add")
     */
    public function add(ManagerRegistry $doctrine)
    {
        // Ci dessous on récupère le manager de doctrine
        // C'est comme si on récuperait le "manager" qui va s'occuper de traiter avec la base de donnée
        $entityManager = $doctrine->getManager();
        // Ici je créer une instance de mon entité Movie
        $movie = new Movie;

        // $movie est un objet de l'entité Movie
        // On va donner des valeurs aux propriétés de $movie grace aux setters de l'entité Movie

        $movie->setTitle('The Walking Dead');
        $movie->setType('Serie');
        $movie->setReleaseDate(new DateTime('2018-05-15'));
        $movie->setDuration(50);
        $movie->setPoster('https://media.senscritique.com/media/000004591491/0/the_walking_dead.jpg');
        $movie->setRating(4.8);
        $movie->setSummary("Après une apocalypse ayant transformé la quasi-totalité de la population en zombies, un groupe d'hommes et de femmes mené par l'officier Rick Grimes tente de survivre...");
        $movie->setSynopsis("Après une apocalypse ayant transformé la quasi-totalité de la population en zombies, un groupe d'hommes et de femmes mené par l'officier Rick Grimes tente de survivre... Ensemble, ils vont devoir tant bien que mal faire face à ce nouveau monde devenu méconnaissable, à travers leur périple dans le Sud profond des États-Unis.");


        // Pour créer une saison a twd ...
        $s1 = new Season();
        $s1->setNumber(1); // LA saison 1
        $s1->setEpisodesNumber(8); // 6 épisodes dans la saison 1
        $s1->setMovie($movie); // Le Movie associé a $s1 sera $movie (donc stranger things)

        // Pour créer une saison 2 ...
        $s2 = new Season();
        $s2->setNumber(2); // LA saison 2
        $s2->setEpisodesNumber(9); // 7 épisodes dans la saison 2
        $s2->setMovie($movie); // Le Movie associé a $s2 sera $movie (donc stranger things)


        $horreur = new Genre();
        $horreur->setName('Horreur');
        $movie->addGenre($horreur);

        $aventure = new Genre();
        $aventure->setName('Aventure');
        $movie->addGenre($aventure);


        // La methode persist permet de sauvegarder l'entité donnée en parametre ($movie) sans pour autant executer la requete sql
        $entityManager->persist($movie);
        $entityManager->persist($s1);
        $entityManager->persist($s2);
        $entityManager->persist($horreur);
        $entityManager->persist($aventure);

        // flush ci dessous va s'occuper d'executer les requetes sql qu'on doit executer
        $entityManager->flush();

        return $this->render('movie_test/index.html.twig', [
            'controller_name' => 'MovieTestController',
        ]);
    }

    /**
     * Modifier une entité Movie
     *
     * @Route("/movie/test/edit/{id}", name="app_movie_test_edit")
     */
    public function edit($id, MovieRepository $movieRepository, ManagerRegistry $doctrine)
    {
        // Ci dessous $movie sera egal a l'entité qui a pour id $id
        $movie = $movieRepository->find($id);
        dump($movie);
        // Maintenant qu'on a récupérer notre entité Movie, on va lui modifier son titre
        $movie->setPoster("https://fr.web.img4.acsta.net/pictures/22/05/18/14/31/5186184.jpg");

        // Puis on sauvegarde dans la base de donnée
        $entityManager = $doctrine->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();
        return $this->redirectToRoute('app_movie_test');
    }
}
