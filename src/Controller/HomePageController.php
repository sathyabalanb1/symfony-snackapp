<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="app_home_page")
     */
    public function index(): Response
    {
        return $this->render('home_page/welcomeinfo.html.twig',['info'=>"Welcome to DiligentSquad Home Page"]);
       
    }
    /**
     * @Route("/home/frontpage", name="app_snackapphome_frontpage")
     */
    public function displayFrontpage():Response
    {
        return $this->render('home_page/welcomeinfo.html.twig',['info'=>"Welcome to DiligentSquad Home Page"]);
    }
}
