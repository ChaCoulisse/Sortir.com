<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripDeleteType;
use App\Form\TripType;
use App\Repository\PlaceRepository;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        EntityManagerInterface $entityManager,
        PlaceRepository $placeRepository
    ): Response
    {
        //afficher le formulaire
        $trip = new Trip();
        //$trip->setState('créée');
        //$trip->setCampus(app.user->getCampus()->getId());
        //$trip->setOrganizer(app.user->getId());
        $tripForm = $this->createForm(TripType::class, $trip);


        //insérer les données dans l'instance
        $tripForm->handleRequest($request);


        //traiter le formulaire
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            $entityManager->persist($trip);
            $entityManager->flush();

            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été créee');
        }
        return $this->render('trip/create.html.twig', [
            'tripForm'=>$tripForm->createView(),
            'placeDisplay' =>$placeRepository->displayPlace(1)
        ]);
    }

    /**
     * @Route("/display/{id}", name="display")
     */

    public function display(int $id, TripRepository $tripRepository): Response
    {

        $trip=$tripRepository->find($id);

        if (!$trip) {
            throw $this->createNotFoundException('La sortie n\'existe pas');
        }
        foreach ($trip->getParticipant() as $participants) {
           $participants->getUserName();
           $participants->getFirstName();
            $participants->getLastname();
        }
        return $this->render('trip/display.html.twig', [
            'controller_name' => 'TripController',
            'tripDisplay' =>$tripRepository->displayTrip($id),
            'listParticipants'=>$trip->getParticipant() ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */

    public function delete(
        int $id,
        Request $request,
        TripRepository $tripRepository,
        EntityManagerInterface $entityManager)
    {
        $trip = new Trip();

        if (!$trip) {
            throw $this->createNotFoundException('La sortie n\'existe pas');
        }
        $tripDeleteForm = $this->createForm(TripDeleteType::class, $trip);

        //insérer les données dans l'instance
        $tripDeleteForm->handleRequest($request);


        //traiter le formulaire
        if ($tripDeleteForm->isSubmitted() && $tripDeleteForm->isValid()) {

//            $entityManager->persist($trip);
//            $entityManager->flush();

            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été annulée');
        }

        return $this->render('trip/delete.html.twig', [
            'controller_name' => 'TripController',
            'tripDeleteForm'=>$tripDeleteForm->createView(),
            'tripDisplay' =>$tripRepository->displayTrip($id),
//
        ]);
    }






    /**
     * @Route("/edit/{id}", name="edit")
     */

    public function edit( int $id, Request $request,EntityManagerInterface $em) : Response {
        $trip = $em->getRepository(Trip::class)->find($id);
        if (!$trip) {
            throw $this->createNotFoundException("Cette sortie est inconnue");
        }
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $em->persist($trip);
            $em->flush();
            $this->addFlash("success", "La sortie est bien modifiée.");
            return $this->redirectToRoute("trip_display", ["id"=> $trip->getId()]);
        }
        return $this->render('trip/edit.html.twig', [
            'tripForm'=>$tripForm->createView(),
        ]);


    }
}
        

