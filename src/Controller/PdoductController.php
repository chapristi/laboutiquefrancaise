<?php

namespace App\Controller;

use App\Classes\Search;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdoductController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {

        $search = new Search();
        $form = $this -> createForm(\App\Form\SearchType::class,$search);
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()){
            //pas beoin ici de get la data vu qu'elle se trouve deja dejadans Search
            $products = $this -> entityManager -> getRepository(Product::class)->findWithSearch($search);
        }else{
            //nous alons aller chercher nos produits dans le repository  ici ProductRepository
            $products = $this -> entityManager -> getRepository(Product::class)->findAll();
            //la methode find all est la pour aller chercher tout lesproduits de la base de données
        }
        return $this->render('pdoduct/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {
        //findOneBy nous sert a chercher un element comme une codition where et
        //a la fin de la fonction on met par quoi on veut chercher donc ici Slug
        $product = $this -> entityManager -> getRepository(Product::class)->findOneBySlug($slug);
        if(!$product){
            return $this -> redirectToRoute('products');
        }
        return $this->render('pdoduct/show.html.twig', [
            'product' => $product
        ]);
    }
}
