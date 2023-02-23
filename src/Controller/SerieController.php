<?php

namespace App\Controller;

use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list_')]
    public function list(SerieRepository $serieRepository): Response
    {
        //TODO Récupéerer la liste des series en BDD
        //on récupère toutes les series en passant le repository
        //$series = $serieRepository->findAll();

        //utilisation de findBY avec un tableau de classe where, order by
        //$series = $serieRepository->findBy(["status" =>"ended"], ["popularity" => 'DESC']);

        $series = $serieRepository->findBy([], ["vote" => 'DESC'], limit: 50);
        dump($series);
        return $this->render('serie/list.html.twig', [
            //on envoie les données à la vue
            'series' => $series
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        dump($serie);
        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/show.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, EntityManagerInterface $entityManager): Response
    {

        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig');
    }


}
