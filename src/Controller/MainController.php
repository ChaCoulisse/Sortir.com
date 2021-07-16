<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use SymfonyBundle\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    /**
     * @Route("/", name="main_home")
     */
    public function home(){

        return $this->render('main/home.html.twig');
    }

}