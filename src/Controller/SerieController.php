<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\Uploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list{page}', name: 'list', requirements: ['page' => '\d+'], methods: "GET")]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        //TODO Récupéerer la liste des series en BDD
        $series = $serieRepository->findAll();
        //nombre de série dans ma table
        $nbSerieMax = $serieRepository->count([]);
        $maxPage = ceil($nbSerieMax / SerieRepository::SERIE_LIMIT);

        if ($page >= 1 && $page <= $maxPage) {
            $series = $serieRepository->findBestSeries($page);
        } else {
            throw $this->createNotFoundException("Oops ! Page not found motha fucka");
        }


        dump($series);

        return $this->render('serie/list.html.twig', [
            //on envoie les données à la vue
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        dump($serie);
        //TODO Créer un formulaire d'ajout de sériel
        return $this->render('serie/show.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(
        SerieRepository $serieRepository,/* EntityManagerInterface $entityManager,*/ Request $request, Uploader $uploader):
    Response
    {
        //renvoie 403
        $this->createAccessDeniedException("You shall not pass !");

        $serie = new Serie();

        //création d'une instance de form lié à une instance de série
        $serieForm = $this->createForm(SerieType::class, $serie);

        //méthode qui extrait les éléments de formul'air de la raquête
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            //Upload photo file
            /**
             * @var UploadedFile $file
             *
             */
            $file = $serieForm->get('poster')->getData();
            //appel de l'uploader
            $newFileName = $uploader->upload(
                $file,
                $this->getParameter('upload_serie_poster'),
                $serie->getName());

            //Création d'un nouveau nom
            $newFileName = $serie->getName() . "-" . uniqid() . "." . $file->guessExtension();
            //copy du fichier dans le répertoire
            $file->move('img/poster/posters/series', $newFileName);
            //set le nouveau nom de la serie
            $serie->setPoster($newFileName);


            //sauvegarde en BDD la nouvelle série
            $serieRepository->save($serie, true);

            $this->addFlash("success", "serie added");


            //redirige vers la page de détail de la série
            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }


        dump($serie);

        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(int $id, SerieRepository $serieRepository)
    {
        $serie = $serieRepository->find($id);

        if ($serie) {
            //je le supprime
            $serieRepository->remove($serie, true);
            $this->addFlash("warining", "Serie deleted");
        } else {
            //ou sinon je lance une exception
            throw $this->createNotFoundException(("There's a problem with the delete !"));
        }

        return $this->redirectToRoute('serie_list');

    }


}