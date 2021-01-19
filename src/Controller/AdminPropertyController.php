<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookingRepository;


class AdminPropertyController extends AbstractController
{
    private $propertyRepository;
    private $manager;
    private $bookingRepository;

    public function __construct(PropertyRepository $propertyRepository, BookingRepository $bookingRepository, EntityManagerInterface $manager)
    {
        $this->propertyRepository = $propertyRepository;
        $this->bookingRepository = $bookingRepository;
        $this->manager = $manager;

    }
    /**
     * @Route("/admin/property/{page<\d+>?1}", name="admin.property.index")
     */
    public function index($page): Response
    {
        $limit = 10;
        $start = $page * $limit - $limit;
        $total = count($this->propertyRepository->findAll());

        $pages = ceil($total / $limit);

        return $this->render('admin/property/index.html.twig', [
            'properties' =>$this->propertyRepository->findBy([] , [], $limit, $start),
            'pages' => $pages,
            'page' => $page,
            'current_page' => 'propertyList'

        ]);
    }

    /**
     * @Route("/admin/demande-de-gestion/", name="admin.property.management")
     */
    public function getPropertyManagementRequest(): Response
    {
        $properties = $this->propertyRepository->findPropertyManagementRequest();
        return $this->render('/admin/property/request.html.twig', [
            'properties' => $properties,
            'current_page' => 'propertyManagement'

        ]);
    }

  
    /**
     * @Route("/admin/{action}/{slug}-{id}/", name="admin.property.response", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function givePropertyManagementRequestResponse(string $action, int $id, string $slug): Response
    {
        $property= $this->propertyRepository->find($id);

        if ($action == 'validate')  
        {
            $property->setStatus(1);
        } 
        else 
        {
            $property->setStatus(2);
        }
        $this->manager->flush();

        return $this->redirectToRoute('admin.property.management');
    }

}