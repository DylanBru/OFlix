<?php
namespace App\EventListener;

use App\Entity\Movie;
use Symfony\Component\String\Slugger\SluggerInterface;

class MovieListener{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function slugifyTitle(Movie $movie){

        // je change le slug du film, par la slugification de son titre
        $movie->setSlug(strtolower($this->slugger->slug($movie->getTitle())));
    }
}