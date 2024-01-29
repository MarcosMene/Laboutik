<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart $cart, $stripeSessionId): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        //in the case order doesnt exist or someone write an false URL and the user is not the same connected
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getState() == 0) {
            //clear session cart
            $cart->remove();

            //modify status setState of the purchase passing 1 as value
            $order->setState(1);
            $this->entityManager->flush();

            //send email to the client to confirm the purchase
            //email to the user
            $mail = new Mail();
            $content = "Bonjour " . $order->getUser()->getFirstname() . "<br/> Merci pour votre commande.<br><br/>" .
                "<p>Lorem ipsum dolor sit amet consectetur adipiscing elit vehicula, quis lacus ut aenean libero congue ornare nec enim, justo velit varius laoreet tristique bibendum fringilla. Phasellus magna faucibus fermentum metus ad ac primis porta felis, eget risus scelerisque purus et morbi class commodo auctor.</p>";

            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande dans LabouTik est validé.', $content);
        }

        //show information about the client's purchase
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
