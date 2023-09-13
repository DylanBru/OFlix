<?php

namespace App\Controller\Front;

use App\Model\Movies;
use App\Repository\MovieRepository;
use App\Service\MyMailerService;
use App\Service\OmdbApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * Page d'accueil du site qui liste tous les films de Oflix
     * Ci dessous on appelle ca une annotation
     * 
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function home(MovieRepository $movieRepository)
    {
        // On va chercher les données du model Movies à l'aide du getter qu'on a mit en place dans le model Movies
        // $movies = Movies::getMovies();
        // On va chercher les données de la table movies à l'aide de notre entité/Repository Movie
        $movies = $movieRepository->findAll();
        // Retourne la vue home en lui passant le parametre $movies (tableau contenant tous mes films)
        return $this->render("main/home.html.twig", [
            'movies' => $movies
        ]);
    }
}