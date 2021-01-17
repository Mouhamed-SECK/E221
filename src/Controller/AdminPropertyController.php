<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;


class AdminPropertyController extends AbstractController
{
    private $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @Route("/admin/property", name="admin.property.index")
     */
    public function index(): Response
    {
        return $this->render('admin/property/index.html.twig', [
            'controller_name' => 'AdminPropertyController',
        ]);
    }
}
