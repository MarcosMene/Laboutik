<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            //verify if user exist on database
            $search_email = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $user->setPassword(
                    $encoder->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                //email to the user
                $mail = new Mail();
                $content = "Bonjour " . $user->getFirstname() . "<br/> Bienvenue sur la première boutique made in France.<br><br/>" .
                    "<p>Lorem ipsum dolor sit amet consectetur adipiscing elit vehicula, quis lacus ut aenean libero congue ornare nec enim, justo velit varius laoreet tristique bibendum fringilla. Phasellus magna faucibus fermentum metus ad ac primis porta felis, eget risus scelerisque purus et morbi class commodo auctor.</p>";

                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur la LabouTik', $content);

                $notification = "Vous êtes bien inscrit dans notre site LabouTik. Vous pouvez maintenant vous connecter à votre compte.";
            } else {
                $notification = "Cet email existe déjà.";
            }

            //SHOW MESSAGE TO USER TO INFORM THAT HIS INSCRIPTION IS OK.
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
