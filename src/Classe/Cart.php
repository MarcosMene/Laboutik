<?php


namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
  private $session;
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager, RequestStack $session)

  {
    $this->session = $session;
    $this->entityManager = $entityManager;
  }

  public function add($id)
  {
    $session = $this->session->getSession();

    $cart = $session->get('cart', []);

    if (!empty($cart[$id])) {
      $cart[$id]++;
    } else {
      $cart[$id] = 1;
    }

    $session->set('cart', $cart);
  }

  public function get()
  {
    $methodget = $this->session->getSession();
    return $methodget->get('cart');
  }

  public function remove()
  {
    $methodremove = $this->session->getSession();
    return $methodremove->remove('cart');
  }

  public function delete($id)
  {
    $session = $this->session->getSession();

    $cart = $session->get('cart', []);

    unset($cart[$id]);
    return $session->set('cart', $cart);
  }

  public function decrease($id)
  {
    //verify is quantity is equal to 1
    $session = $this->session->getSession();

    $cart = $session->get('cart', []);

    if ($cart[$id] > 1) {
      //decrease a quantity
      $cart[$id]--;
    } else {
      //delete my product
      unset($cart[$id]);
    }
    return $session->set('cart', $cart);
  }

  public function getFull()
  {
    $cartComplete = [];

    if ($this->get()) {
      foreach ($this->get() as $id => $quantity) {
        $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

        //evite ajouter un product avec un id random
        if (!$product_object) {
          $this->delete($id);
          continue;
        }

        $cartComplete[] = [
          'product' => $product_object,
          'quantity' => $quantity
        ];
      }
    }
    return $cartComplete;
  }
}
