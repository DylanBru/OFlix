<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    public function testList(): void
    {
        // On récupère un objet qui peut faire des requpêtes (un peu comme des fetchs)
        $client = static::createClient();

        $crawler = $client->request('GET', '/movie');
        
        // je vérifie que je recois bien un code 200 
        $this->assertResponseIsSuccessful();

        // ! à partir d'ici je test la rechercher

        // je veux récupérer le form pour filtrer avec une recherche
        $form = $crawler->selectButton('button-search')->form();

        // je remplis le formulaire
        $form["search"] = "Mummy";

        // J'envoi le formulaire
        $crawler = $client->submit($form);

        //  vérifier que la recherche fonctionne
        $this->assertEquals(1,$crawler->filter('html:contains("Mummy")')->count());
        
        $this->assertEquals(0,$crawler->filter('html:contains("Austin")')->count());
        
    }
}
