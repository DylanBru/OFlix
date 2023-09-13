<?php

namespace App\Controller\Front;

use App\Model\Movies;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use App\Service\OmdbApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie", name="app_movie_list")
     */
    public function list(MovieRepository $movieRepository, Request $request): Response
    {
    
        //  findAllBySearch, requete custom du repo
        $movies = $movieRepository->findAllBySearch($request->get("search"));

        return $this->render('movie/list.html.twig',[
            'movies' => $movies
        ]);
    }

    /**
     * Ci dessous dans ma route, je dis que j'attends un parametre {id} qui va correspondre à l'index du film que je veux afficher dans le tableau
     * @Route("/movie/show/{id}/{slug}", name="app_movie_show")
     */
    public function show($id, MovieRepository $movieRepository, CastingRepository $castingRepository, ReviewRepository $reviewRepository)
    {
        // Ci dessous $movie est egal au tableau associatif du film demandé (en fonction de l'index/id du film)
        // $movie = Movies::getMovieById($id);
        // Ci dessous on recupere l'objet $movie
        $movie = $movieRepository->find($id);
        // On recupere les casting d'un film en une seule requete
        $castings = $castingRepository->findAllForMovieJoinedToPersonOrderedByCreditAscDql($movie);
        // dump($movie);
        // On va chercher les critiques (review) du fim $movie (par ordre de date 'vu le ...' => watchedAt)
        $reviews = $reviewRepository->findBy(['movie' => $movie], ['watchedAt' => 'DESC']); // DESC car on veut afficher les plus récents en premier (les dernieres critiques ajouté seront affiché en 1er)
        // Si le film demandé n'existe pas, alors on retourne une erreur 404
        if ($movie === null) {
            throw $this->createNotFoundException('Film ou série non trouvé.');
        }
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
            'reviews' => $reviews
        ]);
    }

}
