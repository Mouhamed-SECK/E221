<?php

namespace App\Controller;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;


class BookingController extends AbstractController
{

    private $repository;
    private $manager;


    public function __construct(PropertyRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }
    /**
     * @Route("/biens/{slug}-{id}/book", name="booking.create", requirements={"slug": "[a-z0-9\-]*"})
     * @IsGranted("ROLE_USER")
     */
    public function book(int $id): Response
    {
        $booking  = new Booking();
        $property = $this->repository->find($id);
        $user = $this->getUser();

        $booking->setBooker($user)->setProperty($property);
        $booking->setBookingStatus(0);

        $this->manager->persist($booking);
        $this->manager->flush();

        return $this->redirectToRoute('account.index');
    }
}