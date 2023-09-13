<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    /**
     * @Route("/api/movies", name="app_api_movie_list", methods={"GET"})
     */
    public function list(MovieRepository $movieRepository): JsonResponse
    {

        //  récupérer les films
        $movies = $movieRepository->findAll();

        // on retour les films en json
        return $this->json($movies, Response::HTTP_OK, [],["groups" => "movies"]);
    }

    /**
     * @Route("/api/movies", name="app_api_movie_add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, MovieRepository $movieRepository): JsonResponse
    {
        // ICI je récupère le contenu de la requête à ce stade c'est du json 
        $jsonContent = $request->getContent();
        // J'ai besoin d'une entité pour faire l'ajout en bdd donc je transforme le json en entité à l'aide du serializer
        // la méthode veut dire ce contenu, tu le transforme en Movie, le contenu de base est du json.

        // mettre un try catch au cas ou le json n'est pas bon
        try {
            $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        } catch (NotEncodableValueException $e) {
            // si je suis ici c'est que le json n'est pas bon
            return $this->json(["error" => "json invalide"], Response::HTTP_BAD_REQUEST);
        }
        // je check si mon film contient des erreurs
        $errors = $validator->validate($movie);

        // est ce qu'il y a au moins une erreur
        if (count($errors) > 0) {
    
            foreach($errors as $error){
                // je me crée un tableau avec les erreurs en valeur et les champs concernés en index
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);

        }
        
        // ! j'arrive je sais que mes constraints sont bien passés
        $movieRepository->add($movie,true);

        // on retour les films en json
        // préférence perso : je retourne le film crée
        // norme rest : 201, Location avec le lien de la ressource
        return $this->json($movie,Response::HTTP_CREATED,["Location" => $this->generateUrl("app_api_movie_show",["id" => $movie->getId()])], ["groups" => "movies"]);
    }

    /**
     * @Route("/api/movies/random", name="app_api_movie_showRandom", methods={"GET"})
     */
    public function showRandom(MovieRepository $movieRepository): JsonResponse
    {

        //  récupérer les films
        $movie = $movieRepository->findRandomMovie();

        // on retour les films en json
        return $this->json($movie, Response::HTTP_OK, []);
    }

    /**
     * @Route("/api/genres/{id}/movies", name="app_api_movie_listByGenre", methods={"GET"})
     */
    public function listByGenre(Genre $genre): JsonResponse
    {

        //  récupérer les films
        $movies = $genre->getMovies();

        // on retour les films en json
        return $this->json($movies, Response::HTTP_OK, [],["groups" => "movies"]);
    }

    /**
     * @Route("/api/movies/{id}", name="app_api_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): JsonResponse
    {

        // on retour les films en json
        return $this->json($movie, Response::HTTP_OK, [],["groups" => "movies"]);
    }
}
