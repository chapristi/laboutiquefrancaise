<?php

namespace App\Controller;

use App\Form\ChangePasswordType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManger;
    public  function  __construct(EntityManagerInterface  $entityManager)
    {
        $this -> entityManger = $entityManager;
    }
    #[Route('/compte/modifier-mon-mdp', name: 'account_password')]
    public function index(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
        $notification =  null;
        $user = $this -> getUser();
        $form = $this -> createForm(ChangePasswordType::class,$user);
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()){
            //$old_pwd recupere le mdp entré dans le formulaire pour le verifier
            $old_pwd = $form -> get("old_password")->getData();
            //la methode isPasswordValid va direct cherche password pour le verifier qaund on lui donne user
            if($encoder -> isPasswordValid($user,$old_pwd)){
                $new_pwd = $form -> get("new_password") ->getData();
                $password = $encoder ->encodePassword($user,$new_pwd);

                $user -> setPassword($password);
                $this -> entityManger -> persist($user);
                $this -> entityManger -> flush();
                $notification = "votre mot de passe a été mis a jour";
            }else{
                $notification = "votre mot de passe actuel n'est pas le bon";
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form -> createView(),
            'notification' => $notification,
        ]);
    }
}
