<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class RandomMovieGlobalSubscriber implements EventSubscriberInterface
{
    // je récupère twig en injection de dépendance
    private $twig;
    private $movieRepository;

    public function __construct(Environment $twig, MovieRepository $movieRepository)
    {
        $this->twig = $twig;
        $this->movieRepository = $movieRepository;
    }

    public function onKernelController(ControllerEvent $event): void
    {

        $randomMovie = $this->movieRepository->findRandomMovie();
        // J'utilise la focntion addGlobal pour faire passer une variable à toutes mes vues
        $this->twig->addGlobal("randomMovie",$randomMovie);

    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
