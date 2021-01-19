<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;



class AdminPropertyController extends AbstractController
{
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }
    /**
     * @Route("/admin/property", name="admin.property.index")
     */
    public function index(): Response
    {
        $properties = $this->propertyRepository->findAcceptedProperty();

        return $this->render('admin/property/index.html.twig', [
            'controller_name' => 'AdminPropertyController',
            'properties' => $properties

        ]);
    }

    /**
     * @Route("/admin/demande-de-gestion", name="admin.property.management")
     */
    public function getPropertyManagementRequest(): Response
    {
        $properties = $this->propertyRepository->findPropertyManagementRequest();
        return $this->render('/admin/property/request.html.twig', [
            'properties' => $properties

        ]);
    }
    /**
     * @Route("/admin/validate/{slug}-{id}", name="admin.property.validate", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function validate(int $d, string $slug): Response
    {

        return $this->redirectToRoute('admin.property.management');
    }
}