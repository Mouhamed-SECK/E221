<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Property;
use App\Form\PropertyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PropertyController extends AbstractController
{

    private $repository;
    private $manager;


    public function __construct(PropertyRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
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
     * @IsGranted("ROLE_USER")
     * @Route("/biens/demande-de-gestion", name="property.create") 
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($property->getImages() as $image) {
                $image->setProperty($property);
                $manager->persist($image);
            }

            $property->setStatus(0);
            $property->setPropertyOwner($this->getUser());

            $manager->persist($property);
            $manager->flush();

            return $this->redirectToRoute('account.index');
        }

        return $this->render('property/new.html.twig', [
            'form' => $form->createView(),
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
     * @Route("/biens/{slug}-{id}/edit" , name="property.edit" , requirements={"slug": "[a-z0-9\-]*"})
     * @Security("is_granted('ROLE_USER') and user === property.getPropertyOwner()", message="Cette annonce ne vous appartient pas vous ne pouvez pas la modifier")
     */
    public function edit(int $id, string $slug, Request $request): Response
    {
        $property = $this->repository->find($id);

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($property->getImages() as $image) {
                $image->setProperty($property);
                $this->manager->persist($image);
            }



            $this->manager->persist($property);
            $this->manager->flush();
        }
        return $this->render('property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }
}