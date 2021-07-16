<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\SearchTripType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */


    public function home(): Response
    {
//        //afficher le formulaire
//        $trip= new Trip();
//        $searchTripForm= $this->createForm(SearchTripType::class, $trip);


            //redirection
            return $this->render('main/home.html.twig');
        }
}
