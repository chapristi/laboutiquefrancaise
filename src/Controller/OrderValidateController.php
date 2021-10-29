<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Classes\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index($stripeSessionId , Cart $cart): Response
    {
        $order = $this -> entityManager -> getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order-> getUser() != $this -> getUser()){
            return $this->redirectToRoute('home');
        }
        //Modifier le statue isPaid en mettant a 1

        if (!$order -> getIsPaid()){
            //vider la session
            $cart -> remove();
            $order -> setIspaid(1);
            $this -> entityManager -> flush();
            $mail = new Mail();
            $mail -> send('louis.bec05@gmail.com',"Louis","sdf","qsdfsdf");
        }
        //envoyer un email

        return $this->render('order_validate/index.html.twig', [
            'infos_commande' => $order
        ]);
    }
}
