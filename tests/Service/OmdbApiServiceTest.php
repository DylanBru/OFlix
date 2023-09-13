<?php

namespace App\Tests\Service;

use App\Entity\Movie;
use App\Service\OmdbApiService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbApiServiceTest extends KernelTestCase
{
    private const TITLES = [
        "gladiator",
        "tenet",
        "matrix",
        "inception"
    ];

    public function testFetch(): void
    {
        // ici on lance symfo
        $kernel = self::bootKernel();

        // je récupère mon service depuis le container de service
        $omdbApiService = static::getContainer()->get(OmdbApiService::class);

        foreach(self::TITLES as $title){
            // j'instancie un film
            $movie = new Movie();
    
            $movie->setTitle($title);
    
            $result = $omdbApiService->fetch($movie);
    
            //  vérifier que je recupere bien un tableau
            $this->assertIsArray($result);
            //  qu'il a bien un index title
            $this->assertArrayHasKey("Title",$result);
            //  que le titre du tableau correspond bien au titre du film
            $this->assertEqualsIgnoringCase($movie->getTitle(),$result["Title"]);

        }
    }
}
