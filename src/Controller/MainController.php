<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    public function index(): Response
    {
        $username = "WorldWIdeDreamer";
        $serie = ['title' => 'One ¨Piece', 'year'=>'1999', 'platform'=>'Neko-sama'];

        return $this->render("main/home.html.twig",[
            //la clé de la virgule devient le nom de la variable coté twig
        "name" => $username,
        "serie" => $serie
    ]);
}

    /**
     * @Route("/test", name="main_test")
     */
    public function test(): Response
    {
        return $this->render('main/test.html.twig');
    }
}
