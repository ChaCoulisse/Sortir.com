<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trip", name="trip_")
 */

class TripController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     */

    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        //afficher le formulaire
        $trip = new Trip();
        $trip->setState('créée');
        //$trip->setCampus();
        //$trip->setOrganizer();
        $tripForm = $this->createForm(TripType::class, $trip);

        //insérer les données dans l'instance
        dump($trip);
        $tripForm->handleRequest($request);
        dump($trip);

        //traiter le formulaire
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            $entityManager->persist($trip);
            $entityManager->flush();

            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été créee');
        }
            //redirection
            return $this->redirectToRoute('trip_display', ['id' => $trip->getId()]);
        }

        /**
         * @Route("/display/{id}", name="display")
         */

        public
        function display(int $id, TripRepository $tripRepository): Response
        {
            $trip = $tripRepository->find($id);

            if (!$trip) {
                throw $this->createNotFoundException('La sortie n\'existe pas');
            }

            dd($trip);
            return $this->render('trip/dislay.html.twig', [
                "trip" => $trip]);
        }
    }
        

