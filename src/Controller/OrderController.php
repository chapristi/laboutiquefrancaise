<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart): Response
    {
        if(!$this -> getUser() -> getAdresses() -> getValues())
        {
            return   $this -> redirectToRoute('account_adress_add');
        }
        $form  = $this -> createForm(OrderType::class,null,[
            'user'  => $this -> getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form -> createView(),
            'cart' =>  $cart -> getFull()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap', methods : ["POST"])]
    public function add(Cart $cart , Request $request ): Response
    {

        $form  = $this -> createForm(OrderType::class,null,[
            'user'  => $this -> getUser()
        ]);
        $form-> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()){
            //enregistrer la commande dans cette vue c'est pour cela quon modifie l'action
            $carriers  = $form -> get('carriers')->getData();
            $delivery = $form -> get('addresses') -> getData();
            $delivery_content = $delivery -> getFirstname() . ' ' . $delivery -> getLastname() . "<br>";
            $delivery_content .= $delivery -> getPhone();
            if($delivery -> getCompany()){
                $delivery_content .= '<br>' . $delivery -> getCompany();
            }
            $delivery_content .= '<br>' . $delivery -> getAdress();
            $delivery_content .= '<br>' . $delivery -> getPostal(). ' ' . $delivery -> getCountry();
            $time = new DateTimeImmutable();
            $order = new Order();
            //reference va nous servir a avoir un element en base de donné pour target un order  recuperer ce qu'on veut
            $reference = $time ->format('dmY') . '' . uniqid();
            $order -> setReference($reference);
            $order -> setUser($this -> getUser());
            $order -> setCreatedAt($time);
            $order -> setCarierName($carriers -> getName());
            $order -> setCarierPrice($carriers -> getPrice());
            $order -> setDelivery($delivery_content);
            $order -> setIsPaid(0);
            $this -> entityManager -> persist($order);


            foreach ($cart -> getFull() as $product)
            {
                $orderDetails = new OrderDetails();

                $orderDetails -> setMyOrder($order);
                $orderDetails -> setProduct($product['product']->getName());
                $orderDetails -> setQauntity($product['quantity']);
                $orderDetails -> setPrice($product['product']->getPrice());
                $orderDetails -> setTotal($product['product']->getPrice() * $product['quantity']);

                $this -> entityManager -> persist($orderDetails);


            }
            $this -> entityManager -> flush();



            return $this->render('order/add.html.twig', [
                'cart' =>  $cart -> getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order -> getReference()
            ]);
        }
        return $this->redirectToRoute('cart');


    }
}
