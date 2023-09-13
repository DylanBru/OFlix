<?php
namespace App\EventListener;

use App\Entity\Review;

class ReviewListener{


    public function updateMovieRating(Review $review){
        // je recupere le film
        $movie = $review->getMovie();

        // pour calculer la moyenne je veux additionner toutes les notes
        // j'initalise une variable à null
        $allNotes = null;
        foreach($movie->getReviews() as $review){
            $allNotes += $review->getRating();
        }

        // pour une moyenne on divise le total par le nombre de note
        $rating = $allNotes / count($movie->getReviews());

        // je set la note du film arrondi à 1 après la virgule;
        $movie->setRating(round($rating,1));
    }
}