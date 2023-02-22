<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list_')]
    public function list(): Response
    {
        //TODO Récupéerer la liste des series en BDD
        return $this->render('serie/list.html.twig',);
    }
    #[Route('/{id}', name: 'show', requirements: ['id'=>'\d+'])]
    public function show(int $id): Response
    {
        dump($id);
        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/show.html.twig');
    }
    #[Route('/add', name: 'add')]
    public function add(): Response
    {
        dump(1231456);
        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig');
    }


}
