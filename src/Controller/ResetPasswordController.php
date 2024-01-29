<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        //verify if email was entered on the form
        if ($request->get('email')) {

            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {
                //save on database the information reset_password
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();


                //send email to user with a link to update password
                $url = $this->generateUrl(
                    'update_password',
                    [
                        'token' => $reset_password->getToken(),
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
                $content = "Bonjour " . $user->getFirstname() . "<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site LabouTik.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'>mettre à jour votre mot de passe.</a>";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialiser votre mot de passe sur LabouTik', $content);

                $this->addFlash('notice', '<div class="alert alert-success">Vous allez recevoir dans quelque minutes un mail avec la procédure pour réinitialiser votre mot de passe.</div>');
            } else {
                $this->addFlash('notice', '<div class="alert alert-danger">Cette adresse email est inconnue.</div>');
            }
        };
        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }

        //verify if createdAt = now - 3h 
        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            // die('on est bon');

            $this->addFlash('notice', '<div class="alert alert-danger">Votre demande de mot de passe a expiré. Merci de la renouveller.</div>');
            //return to reset_password because it is expired
            return $this->redirectToRoute('reset_password');
        }

        //show page with password and confirm your password
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();

            //encode password
            $password = $encoder->hashPassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);

            //send to database
            $this->entityManager->flush();

            //message to user to inform password was updated
            $this->addFlash('notice', '<div class="alert alert-success">Votre mot de passe a bien été mis à jour.</div>');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
