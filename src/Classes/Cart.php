<?php
namespace App\Classes;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart{
    private $session;
    private $entityManger;
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this -> session = $session;
        $this -> entityManger = $entityManager;
    }
    public function add($id)
    {
        $cart = $this -> session -> get('cart', []);
        if(!empty($cart[$id])){
            $cart[$id] ++;
        }else{
            $cart[$id]  = 1;
        }
        $this -> session -> set('cart',$cart);
    }
    public function get()
    {
        return $this -> session -> get('cart');
    }
    public function remove()
    {
        return $this -> session -> remove('cart');
    }
    public function delete($id)
    {
        $cart = $this -> session -> get('cart' , []);
        unset($cart[$id]);
        return $this -> session -> set('cart' , $cart);
    }
    public function decrease($id)
    {
        $cart = $this -> session -> get('cart',[]);
        if($cart[$id] >  1){
            //retirer une quantité
            $cart[$id]--;
        }else{
            //suprimer totalement le produit du panier
            unset($cart[$id]);

        }
        return  $this -> session -> set('cart' , $cart);
    }
    public function getFull():array
    {
        $cartComplete = [];
        // on peut appeler notre methode get
        if ($this -> get()){
            foreach ($this  -> get() as $id => $quantity){
                $productObject = $this -> entityManger -> getRepository(Product::class)-> findOneById($id);
                // si un produit est entrez dans la session mais n'existe pas en base de donné alors
                //celui-ci est suprimmé du panier
                if (!$productObject){
                    $this -> delete($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' =>$this -> entityManger -> getRepository(Product::class)-> findOneById($id),
                    'quantity' => $quantity,
                ];
            }
        }
        return $cartComplete;

    }

}