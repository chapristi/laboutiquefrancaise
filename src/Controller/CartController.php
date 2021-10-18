<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private   $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {
        $cartComplete = [];
        foreach ($cart -> get() as $id => $quantity){
            $cartComplete[] = [
                'product' =>$this ->entityManager -> getRepository(Product::class)-> findOneById($id),
                'quantity' => $quantity,
            ];
        }
        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete
        ]);
    }
    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart,$id): Response
    {
        $cart -> add($id);
        return $this -> redirectToRoute('cart');
    }
    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart ->remove();
        return $this -> redirectToRoute('products');
    }
    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart,$id): Response
    {
        $cart ->delete($id);
        return $this -> redirectToRoute('cart');
    }
}
