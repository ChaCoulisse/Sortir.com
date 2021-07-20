<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripCancelType;
use App\Form\TripDeleteType;
use App\Form\TripType;
use App\Repository\PlaceRepository;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
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

 //#2002: Créer une sortie:
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        PlaceRepository $placeRepository,
        UserRepository $userRepository
    ): Response
    {
        //afficher le formulaire
        $trip = new Trip();
        $currentUsername = $this->getUser()->getUserIdentifier();
        //$trip->setState(1);
        //$trip->setCampus(campus.id);
        //$trip->setOrganizer($currentUsername);
        $tripForm = $this->createForm(TripType::class, $trip);


        //insérer les données dans l'instance
        $tripForm->handleRequest($request);
dump($request);

            //traiter le formulaire
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($trip);
            $entityManager->flush();

            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été créee');

            //redirection
            return $this->redirectToRoute('trip_display',['id' =>$trip->getId()]);
        }

        return $this->render('trip/create.html.twig', [
            'tripForm'=>$tripForm->createView(),
            'placeDisplay' =>$placeRepository->displayPlace(1),
            'campusOrganizer'=>$userRepository->NameCampusByIdUser(22)
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
            'listParticipants'=>$trip->getParticipant() ,

        ]);
    }

    /**
     * @Route("/cancel/{id}", name="cancel")
     */

    public function cancel(
        int $id,
        Request $request,
        TripRepository $tripRepository,
        EntityManagerInterface $entityManager)
    {
        $trip = $entityManager->getRepository(Trip::class)->find($id);

        if (!$trip) {
            throw $this->createNotFoundException('La sortie n\'existe pas');
        }
        $tripCancelForm = $this->createForm(TripCancelType::class, $trip);

        //traiter le formulaire
        if ($tripCancelForm->isSubmitted() && $tripCancelForm->isValid()) {

            $trip->setStates();
            $entityManager->flush();

            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été annulée');
        }

        return $this->render('trip/cancel.html.twig', [
            'controller_name' => 'TripController',
            'tripCancelForm'=>$tripCancelForm->createView(),
            'tripDisplay' =>$tripRepository->displayTrip($id),
//
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */

    public function edit( int $id, Request $request,EntityManagerInterface $entityManager) : Response {
        $trip = $entityManager->getRepository(Trip::class)->find($id);
        if (!$trip) {
            throw $this->createNotFoundException("Cette sortie est inconnue");
        }
        $tripForm = $this->createForm(TripType::class, $trip);
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $entityManager>persist($trip);
            $entityManager->flush();
            $this->addFlash("success", "La sortie est bien modifiée.");
            return $this->redirectToRoute("trip_display", ["id"=> $trip->getId()]);
        }
        return $this->render('trip/edit.html.twig', [
            'tripForm'=>$tripForm->createView(),
        ]);


    }
}
        

