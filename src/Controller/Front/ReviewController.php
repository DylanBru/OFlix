<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * Ajout d'une critique sur un film
     * @Route("/movie/{id}/review/add", name="app_movie_review_add")
     */
    public function add(Movie $movie, Request $request, ReviewRepository $reviewRepository): Response
    {
        // On créer un objet de l'entité Review car c'est cet objet qu'on va remplir
        $review = new Review();

        // Je créer le formulaire grace au builder
        $form = $this->createForm(ReviewType::class, $review);

        // Ici on récup le contenu de la requete
        $form->handleRequest($request);

        // Ici on verifie si le form a été soumis
        // Si oui on rentre dans le if, sinon on ne rentre pas dedans
        $movie_reviews = $movie->getReviews();
        // $average = array_sum($movie_reviews->getRating())/count($movie_reviews->getRating());
    if ($form->isSubmitted() && $form->isValid()) {
            // BONUS : Calculer la note moyenne d'un film
            // Je recupere la colelction de toutes les critiques

            // On associe la review au film concerné
            $movie->addReview($review);
            // On ajoute $review en bdd grace au reviewRepository
            $reviewRepository->add($review, true);
            // Maintenant on peut rediriger vers la page de detail du film
            return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId(),"slug" => $movie->getSlug()]);
        }

        return $this->renderForm('review/add.html.twig', [
            // pour afficher le titre du film, on doit passer par l'objet $movie
            'movie' => $movie,
            // pour afficher le formulaire
            'form' => $form
        ]);
    }
}
