<?php
namespace App\Controller;
use App\Entity\State;
use App\Entity\Trip;
use App\Form\TripType;
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
        $trip = new Trip();
        //$trip->setState('créée');
        //$trip->setCampus();
        //$trip->setOrganizer();
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
            ]);
        }
    /**
     * @Route("/display/{id}", name="display")
     */
    public function display(int $id, TripRepository $tripRepository): Response
    {
            $trip = new Trip();
            if (!$trip) {
                throw $this->createNotFoundException('La sortie n\'existe pas');
            }
            return $this->render('trip/display.html.twig', [
                "tripDisplay" => $tripRepository->find($id)]);
        }
    /**
     * @Route("/delete", name="delete")
     */
    public function delete(Trip  $trip, EntityManagerInterface $entityManager) {
            $entityManager->remove($trip);
            $entityManager->flush();
            return $this->redirectToRoute('main_home');
        }
    /**
     * @Route("/edit", name="edit")
     */
    public function edit( TripRepository $tripRepository ) {
       $trip = new Trip();
        $tripForm = $this->createForm(TripType::class, $trip);
//        $trip=$tripRepository->find(1);
//
//        if(!$trip){
//            throw $this->createNotFoundException('La sortie n\'existe pas');
//        }
        return $this->render('trip/edit.html.twig', ['tripForm'=>$tripForm->createView(),
        ]);
    }


    // A voir si on ne met pas tous les updates des status dans un SERVICE ?

    /**
     * @Route("/update/{id}/{stateId}", name="update")
     */

    public function updateTripState(int $id, int $stateId, TripRepository $tripRepository)
    {
        return $this->render('trip/edit.html.twig', ['tripRepo'=>$tripRepository->updateState($id, $stateId)]);


        }

    /**
     * @throws \Exception
     */
    public function updateAllStateTrip(TripRepository $tripRepository){

        $today=new \DateTime('now');

        $trips = $tripRepository->findAllTrip();
        foreach ($trips as $trip){
            if ($trip->getState() == 2 && $trip->getLimitDate() == $today ){
                $tripRepository ->updateState($trip->getId(), 3);
            }elseif ($trip->getState()==3 && $trip->getStartHour() == $today){
                $tripRepository ->updateState($trip->getId(), 4);
            }elseif($trip->getState()==4 && $trip->getStartHour()->add(new \DateInterval('PT'.$trip->getDuration()->timestamp().'S')) > $today){
                $tripRepository ->updateState($trip->getId(), 5);
            }elseif($trip->getState()>=5 && $today > $trip->getStartHour()->add(new \DateInterval('PT1M'))){
                $tripRepository ->updateState($trip->getId(), 7);

        }


    }

  /*  public function updateStateTripArchived(TripRepository $tripRepository, EntityManagerInterface $entityManager){
        $entityManager=$this->getDoctrine()->getManager();
        $trip = $entityManager ->getRepository(Trip::class)->findTripFinishedAndCancel();
        if ()
    }*/
}}
    
    
  
  

