<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PropertyRepository $propertyRepository): Response
    {
        $properties = $propertyRepository->findLatest();
        return $this->render('home/index.html.twig', [
            'properties' =>  $properties,
            'current_menu' => 'home'
        ]);
    }
}