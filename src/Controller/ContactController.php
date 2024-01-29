<?php

namespace App\Controller;

use App\Classe\MailContact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', '<div class="alert alert-success">Merci pour votre contact. Notre équipe va vous répondre dans le meilleurs délais. <a href="/">Revenir à page d\'accueil</a></div>');

            $userLastname = $form['nom']->getData();
            $userName = $form['prenom']->getData();
            $userContent =  $form['content']->getData();
            $userEmail = $form['email']->getData();

            //send email to admin
            $content = "Bonjour </br>Vous avez reçus un message de <strong>" . $userLastname . " " . $userName . "</strong></br>Adresse email : <strong>" . $userEmail . "</strong> </br>Message : " . $userContent . "</br></br>";

            $mail = new MailContact();
            $mail->send($userEmail, $userLastname . ' ' . $userName, 'Vous avez reçus une nouvelle demande de contact', $content);

            return $this->redirect($request->getUri());
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
