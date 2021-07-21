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
     * @throws \Exception
     */
    public function index(Request $request, TripRepository $tripRepository): Response
    {
        $trip = new Trip();
        $searchTripForm = $this->createForm(SearchTripType::class, $trip);
        $searchTripForm->handleRequest($request);

        $this->updateAllStateTrip($tripRepository);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'tripList' => $tripRepository->findAllTrip(),
            'searchTripForm' => $searchTripForm->createView(),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function updateAllStateTrip(TripRepository $tripRepository){

        $today=new \DateTime('now');

        $trips = $tripRepository->findAllTrip();
        foreach ($trips as $trip) {
            if ($trip->getState()->getId() == 2 && $trip->getLimitDate() == $today) {
                $tripRepository->updateState($trip->getId(), 3);
            } elseif ($trip->getState()->getId()  == 3 && $trip->getStartHour() == $today) {
                $tripRepository->updateState($trip->getId(), 4);
            } elseif ($trip->getState()->getId()  == 4 && $trip->getStartHour()->add(new \DateInterval('PT' . $trip->getDuration()->getTimestamp() . 'S')) > $today) {
                $tripRepository->updateState($trip->getId(), 5);
            } elseif ($trip->getState()->getId()  >= 5 && $today > $trip->getStartHour()->add(new \DateInterval('PT1M'))) {
                $tripRepository->updateState($trip->getId(), 7);

            }
        }

}
}
