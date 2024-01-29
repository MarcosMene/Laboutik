<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController

{

    #[Route('/compte/adresses', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/compte/ajouter-une-adresse', name: 'account_address_add')]
    public function add(Request $request, EntityManagerInterface $entityManager,  Cart $cart): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser()); //get user info
            $entityManager->persist($address);
            $entityManager->flush();

            //verify if there is product on chart to resend user on order page or account page
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
            }
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_address_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUsder() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/supprimer-une-adresse/{id}', name: 'account_address_delete')]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('account_address');
    }
}
