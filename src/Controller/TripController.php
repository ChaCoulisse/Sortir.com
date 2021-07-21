<?php
namespace App\Controller;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\Trip;
use App\Form\PlaceFormType;
use App\Form\Trip1Type;
use App\Form\CityFormType;
use App\Form\TripCreationFormTypeType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

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
        $trip =  new Trip();
        $place= new Place();
        $city= new City();
        $user=$this->getUser();

        $form1= $this->createForm(Trip1Type::class, $trip);
        $form2= $this->createForm(PlaceFormType::class, $place);
        $form3 = $this->createForm(CityFormType::class, $city);

        $form1->handleRequest($request);
        $form2->handleRequest($request);
        $form3->handleRequest($request);

        //traiter le formulaire

        if($form2->isSubmitted() && $form2->isValid()){
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();
        }

        if($form3->isSubmitted() && $form3->isValid()){
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();
        }

        if ($form1->isSubmitted() && $form1->isValid()) {
            $trip->setOrganizer($user);
            $trip->setPlace($place);
            $entityManager= $this->getDoctrine()->getManager();
            $entityManager->persist($trip);
            $entityManager->flush();


            //afficher un message flash
            $this->addFlash('success', 'Votre sortie a bien été créee');

            return $this->redirectToRoute('main_home');
        }
        return $this->render('trip/create.html.twig', [
            'CityFormType' => $form3->createView(),
            'Trip1Type'=>$form1->createView(),
           'PlaceFormType'=>$form2->createView(),
        ]);
    }

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
     * @Route("/delete", name="delete")
     */
    public function delete(Trip $trip, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($trip);
        $entityManager->flush();
        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/edit", name="edit")
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
    
    
  
  

