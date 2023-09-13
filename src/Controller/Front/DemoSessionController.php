<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DemoSessionController extends AbstractController
{
    /**
     * @Route("/demo/session", name="app_demo_session")
     */
    public function index(Request $request): Response
    {
        // On a récupéré dans $sesison la requete qui a été faite
        $session = $request->getSession();

        // Ici on récupère l'élément de ma session qui a pour clé "user_name", si aucun attribut "user_name" n'a été défini, alors 'user_name' aura pour valeur 'inconnu'.
        $userName = $session->get('user_name', 'inconnu');
        return $this->render('demo_session/index.html.twig', [
            'user_name' => $userName,
        ]);
    }

    /**
     * Fonction d'ajout d'utilisateur dans notre session
     *
     * @Route ("/demo/session/{name}", name="app_demo_session_add")
     * doc : https://symfony.com/doc/5.4/session.html#basic-usage
     */
    public function add(Request $request, $name)
    {
        // 1ere etape : On récupère la session
        $session = $request->getSession();

        // 2eme etape : on ajoute une donnée dans notre session
        $session->set('user_name', $name);

        // 3eme etape : On redirige vers la page de démo
        return $this->redirectToRoute('app_demo_session');
    }
}
