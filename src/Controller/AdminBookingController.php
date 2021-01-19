<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;



class AdminBookingController extends AbstractController
{

    private $bookingRepository;
    private $manager;


    public function __construct( BookingRepository $bookingRepository, EntityManagerInterface $manager)
    {
        $this->bookingRepository = $bookingRepository;
        $this->manager = $manager;

    }
    /**
     * @Route("/admin/reservations/", name="admin.booking.bookings")
     */
    public function getBookings(): Response
    {
        $bookings = $this->bookingRepository->findBy(['bookingStatus' => 0]);
        
        return $this->render('/admin/bookings/bookings.html.twig', [
            'bookings' => $bookings,
            'current_page' => 'propertyBookings'

        ]);
    }

    /**
     * @Route("/admin/bookings/{action}-reservation-{id}/", name="admin.booking.response", requirements={"action": "[a-zA-Z]+", "id": "\d+"})
     */
    public function giveBookingResponse(string $action, int $id ): Response
    {
        $booking= $this->bookingRepository->find($id);

        if ($action == 'validate')  
        {
            $booking->setBookingStatus(1);
           
            $bookingsAssociateToThisProperty = ($booking->getProperty()->getBookings())->toArray();       
            foreach ($bookingsAssociateToThisProperty as $book) 
            {
                if ($book->getId() !== $id) 
                {

                    $book->setBookingStatus(2);
                    $booking->getProperty()->setStaus();
                }
            }
        } 
        else 
        {
            $property->setBookingStatus(2);
        }
        $this->manager->flush();

        return $this->redirectToRoute('admin.booking.bookings');
    }
}