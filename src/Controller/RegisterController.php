<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public  function  __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }
    /**
     * @Route("/inscription",name="register")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;
        $user = new  User();
        $form = $this -> createForm(RegisterType::class,$user);
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()){
            $user = $form-> getData();
            $sameMail  = $this -> entityManager -> getRepository(User::class)->findOneByEmail($user -> getEmail());
            if(!$sameMail){
                $password = $encoder -> encodePassword($user,$user -> getPassword());
                $user -> setPassword($password);
                $this -> entityManager -> persist($user);
                $this -> entityManager -> flush();
                $notification = "Votre inscription s'est correctement déroulée ";
                $mail = new Mail();
                $mail -> send($user -> getEmail(),$user -> getFirstname(),'Bienvenue sur La boutique Francaise',"bonJour....");

            }else{
                $notification = "L'email renseigné ne peut pas etre utilisé";
            }



        }
        return $this->render('register/index.html.twig', [
                "form" => $form->createView(),
                "notification" => $notification
        ]);
    }
}
