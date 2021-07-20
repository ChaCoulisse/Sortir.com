<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\SearchTripType;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main_home")
     */
    public function index(Request $request, TripRepository $tripRepository): Response
    {
        $trip = new Trip();
        $searchTripForm = $this->createForm(SearchTripType::class, $trip);
        $searchTripForm->handleRequest($request);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'tripList' => $tripRepository->findAllTrip(),
            'searchTripForm' => $searchTripForm->createView(),
        ]);
    }
}
