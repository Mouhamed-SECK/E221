<?php

namespace App\Controller;

use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;


    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/biens", name="property.index")
     */
    public function index(): Response
    {
        $property = $this->repository->findAllVisible();
        $property[0]->setIsLoan(false);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        dump($property);

        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Property $property, string $slug): Response
    {
        if ($property->getSlug() == $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        return $this->render('property/show.html.twig', [
            'current_menu' => 'dd',
            'property' => $property
        ]);
    }
}