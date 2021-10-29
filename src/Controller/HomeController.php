<?php

namespace App\Controller;

use App\Classes\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/" , name="home")
     *
     */
    public function index(): Response
    {

        return $this->render('home/index.html.twig', [

        ]);
    }
}
