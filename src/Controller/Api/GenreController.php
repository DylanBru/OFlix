<?php

namespace App\Controller\Api;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="app_api_genre_list")
     */
    public function list(GenreRepository $genreRepository): JsonResponse
    {
        $genres = $genreRepository->findAll();

        // on oubli pas le group sinon circular error
        return $this->json($genres, Response::HTTP_OK,[], ["groups" => "genres"]);
    }
}
