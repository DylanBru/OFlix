<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MyMailerService{

    private $mailer;
    private $adminMail;

    public function __construct(MailerInterface $mailer, string $adminMail){
        $this->mailer = $mailer;
        $this->adminMail = $adminMail;
    }

    /**
     * Send an Email to The website administrator 
     * @param string $subject The subject of the email
     * @param string $template path of the template twig
     * @param array $context array of variables for the template
     */
    public function alertToAdmin(string $subject, string $template, array $context){

         //  Envoyer un mail au proprio du site pour préciser qu'un film a été crée
         $email = (new TemplatedEmail())
         // ici mail du compte mailjet
         ->from($this->adminMail)
         // a qui on envoi l'email
         ->to($this->adminMail)
         // sujet de l'email
         ->subject($subject)
         // le html du contenu de l'email
         ->htmlTemplate("email/$template")
         // équivalent du tableau de variables qu'on fait passé à la vue
         ->context($context);
         // le mail part
         $this->mailer->send($email);

    }
}
