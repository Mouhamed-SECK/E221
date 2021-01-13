<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

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
        $properties = $this->repository->findAllVisible();
        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
            'current_menu' => 'properties',
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(int $id, string $slug): Response
    {
        $property = $this->repository->find($id);
        if ($property->getSlug() !== $slug) {
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

    /**
     * @Route("biens/demande-de-gestion", name="property.create")
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        dump($property);

        if ($form->isSubmitted() && $form->isValid()) {
            $property->setUsageType(0);
            $property->setIsLoan(false);
            $manager->persist($property);
            $manager->flush();
        }

        return $this->render('property/new.html.twig', [
            'form' => $form->createView(),
            'current_menu' => 'properties',
        ]);
    }
}