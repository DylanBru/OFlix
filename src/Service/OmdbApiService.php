<?php
namespace App\Service;

use App\Entity\Movie;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiService{

    // les prérequis du service
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey){
        // httpClientInterface nous permettra de lancer des requêtes clients de puis notre app symfony
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * Return a movie from the omdbApi
     */
    public function fetch(Movie $movie){
        $response = $this->client->request(
            'GET',
            'https://www.omdbapi.com/',[
                "query" => [
                    "apiKey" => $this->apiKey,
                    "t" => $movie->getTitle()
                ]
            ]
        );
        return $response->toArray();
    }

    /**
     * Return a poster
     */
    public function getPoster(Movie $movie){
        $movieApi = $this->fetch($movie);

        if(!array_key_exists("Poster",$movieApi)){
            return null;
        }
        return $movieApi["Poster"];
    }
    
    // exemple avec une autre API
    // public function chuckNorris(){
    //     $response = $this->client->request(
    //         'GET',
    //         'https://api.chucknorris.io/jokes/random'
    //     );

    //     dd($response->toArray());
    // }
}