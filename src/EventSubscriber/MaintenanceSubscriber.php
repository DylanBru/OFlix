<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class MaintenanceSubscriber implements EventSubscriberInterface
{

    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }
    public function onKernelResponse(ResponseEvent $event): void
    {

        // est ce que app.maintenance est remplis
        if(!$this->parameterBag->get("app.maintenance")) return;

        // si j'arrive ici c'est que maintenance est bien remplis et je recupere sa valeur
        $message = $this->parameterBag->get("app.maintenance");
        
        
        // J'ai accès à la reponse via l'event
        $response = $event->getResponse();

        // je change le contenu dynamiquement avec la date du .env
        $newContent = str_replace("</nav>", "</nav><div class='alert alert-danger mt-2'>Maintenance prévue $message</div>", $response->getContent());

        // je remplace le contenu de base par le noveau contenu
        $response->setContent($newContent);

    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
