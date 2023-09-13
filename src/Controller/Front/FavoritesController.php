<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\FavoriteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * @Route("/favorites", name="app_favorites")
     */
    public function index(FavoriteService $favoriteService): Response
    {
        
        return $this->render('favorites/index.html.twig',[
            "favorites" => $favoriteService->getAll()
        ]);
    }

    /**
     * @Route("/favorites/add/{id}", name="app_favorites_add")
     * 
     */
    public function add(Movie $movie, FavoriteService $favoriteService): Response
    {
    
        // j'utilise mon service pour add le film
        $favoriteService->toggle($movie);

        // on add un messageFlash
        $this->addFlash("success","Le film a bien été ajouté aux favoris");

        
        return $this->redirectToRoute("app_favorites");
    }

    /**
     * @Route("/favorites/delete/{id}", name="app_favorites_delete")
     * 
     */
    public function delete(Movie $movie,FavoriteService $favoriteService): Response
    {
        $favoriteService->toggle($movie);

        $this->addFlash("warning","Le film ".$movie->getTitle()." a bien été retiré des favoris");

        
        return $this->redirectToRoute("app_favorites");
    }

    /**
     * @Route("/favorites/empty", name="app_favorites_empty")
     * 
     */
    public function empty(FavoriteService $favoriteService): Response
    {
        $favoriteService->empty();

        $this->addFlash("warning","Vos favoris ont été vidé");

        
        return $this->redirectToRoute("app_favorites");
    }
}
