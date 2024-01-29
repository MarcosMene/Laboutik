<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {

        $user = $this->getUser();
        assert($user instanceof User);

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_pwd);

                $user->setPassword($password);

                $entityManager->flush();
                $this->addFlash('notice', '<div class="alert alert-success">Votre mot de passe a bien été mis à jour.</div>');
            } else {
                $this->addFlash('notice', '<div class="alert alert-danger">Votre mot de passe actuel n\'est pas le bon.</div>');
            }
            return $this->redirect($request->getUri());
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()

        ]);
    }
}
