<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {

        $request = $event->getRequest();

        //  Je vérifie que la route commence par api, si c'es pas le cas on stop
        if(strpos($request->getPathInfo(),"/api/")!== 0){
            return;
        }
        // Si jamais mon erreur est une erreur autres que http, j'arrete le subscriber
        if($event->getThrowable()->getCode() === 0){
            return;
        }

        // Je crée une nouvelle réponse mais en json ce coup ci
        $response = new JsonResponse(
            [
                // je mets dedans le code http et le message d'erreur
                "error" => $event->getThrowable()->getStatusCode(),
                "message" => $event->getThrowable()->getMessage(),
            ]
        );

        // on set la nouvelle réponse
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
