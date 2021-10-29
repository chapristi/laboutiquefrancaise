<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager ,$reference): Response
    {
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);


        if (!$order) {
            return new  JsonResponse(['error' => 'order']);
        }

        $productStripe = [];
        $YOUR_DOMAIN = 'https://127.0.0.1:8000';


        foreach ($order->getOrderDetails()->getValues() as $product)
        {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());

            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $product_object->getIllustration()],
                    ]

                ],

                'quantity' => $product->getQauntity(),
            ];
        }
        $productStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order -> getCarierPrice(),
                'product_data' => [
                    'name' => $order->getCarierName(),
                    'images' => [$YOUR_DOMAIN]
                ],
            ],
            'quantity' => 1,
        ];





        Stripe::setApiKey('sk_test_51JooZkDfZJsKn0Qy1MSlslsEhZKbRgY3plDJRiZPE6Nb6rx0D3I22tBuRGDqNfQuaElzqlxNre9w0cAIpsxJG3em002pyn6sHi');

            $checkout_session = Session::create([
                'customer_email' => $this-> getUser() -> getEmail() ,

                'line_items' => [
                    $productStripe,
                ],
                'payment_method_types' => [
                    'card',
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',

            ]);
            //nous ajoutons une colone StripeSessionId pour pour pouvoir recupere la commande quand elle est payé
            // comme stripe nous renvoie une checkout_id cela nous permet de bien identifier la commande
            $order -> setStripeSessionId($checkout_session -> id);
            $entityManager -> flush();

       return  new JsonResponse(['id' => $checkout_session -> id]);

    }
}
