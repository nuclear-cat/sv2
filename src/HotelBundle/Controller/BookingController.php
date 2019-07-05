<?php

namespace HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HotelBundle\Entity\Room;
use HotelBundle\Entity\Feedback;
use ImageBundle\Entity\Image;
use ImageBundle\Entity\ImageProvider;
use HotelBundle\Entity\Post;
use HotelBundle\Entity\Setting;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\RedirectResponse;


class BookingController extends Controller
{   
    /**
     * @param Request $request
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function roomBookingPreviewAction(Request $request)
    {
        $em       = $this->getDoctrine()->getManager();
        $dateCome = (new \DateTime(date('Y-m-d')));
        $dateLeft = (new \DateTime(date('Y-m-d')))->modify('+ 1 day');
        
        $filterForm = $this->createForm(\HotelBundle\Form\BookingFilterForm::class, null, 
        [
            'date_come' => $dateCome,
            'date_left' => $dateLeft,
        ]);

        $filterForm->handleRequest($request);


        if($filterForm->isSubmitted() && $filterForm->isValid())
        {
            if($filterForm->getData()['dateCome'])
            {
                $dateCome = new \DateTime($filterForm->getData()['dateCome']);
            }

            if($filterForm->getData()['dateLeft'])
            {
                $dateLeft = new \DateTime($filterForm->getData()['dateLeft']);
            }
        }

        $roomTypes  = $this->getDoctrine()->getRepository(\HotelBundle\Entity\Room::class)->getRooms($dateCome, $dateLeft);
        $setting    = $em->getRepository(Setting::class)->getSetting();

        return $this->render('@Hotel/Default/room_booking_preview.html.twig',
        [
            'discount'    => $setting->getDiscountToday(),
            'filter_form' => $filterForm->createView(),
            'room_types'  => $roomTypes,
            'date_come'   => $dateCome,
            'date_left'   => $dateLeft,
        ]);
    }

    /**
     * @param Request $request
     * @param string $dateCome
     * @param string $dateLeft
     * @param string $physicalRoomId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function roomBookingAction(Request $request, $dateCome, $dateLeft, $physicalRoomId)
    {
        try
        {
            $dateCome = new \DateTime(date($dateCome));
            $dateLeft = (new \DateTime(date($dateLeft)));
        }
        catch (\Exception $e)
        {
            throw $this->createNotFoundException('Date format is not correct');
        }

        if(!$physicalRoom = $this->getDoctrine()->getRepository(\HotelBundle\Entity\PhysicalRoom::class)->find($physicalRoomId))
        {
            throw $this->createNotFoundException('Room not found');
        }

        $em         = $this->getDoctrine()->getManager();
        $setting    = $em->getRepository(Setting::class)->getSetting();

        $filterForm = $this->createForm(\HotelBundle\Form\BookingFilterForm::class, null,
        [
            'date_come' => $dateCome,
            'date_left' => $dateLeft,
        ]);

        $booking         = new \HotelBundle\Entity\RoomBooking();
        $booking         ->setPhysicalRoom($physicalRoom);
        $booking         ->setArrivalDate($dateCome);
        $booking         ->setLeavingDate($dateLeft);

        $roomBookingForm = $this->createForm(\HotelBundle\Form\RoomBookingForm::class, $booking);
        $roomBookingForm ->handleRequest($request);

        if($roomBookingForm->isSubmitted())
        {
            if($roomBookingForm->isValid())
            {
                $em->persist($booking);
                $em->flush();
                return $this->redirect($this->generateUrl('hotel_room_booking_success', [ 'bookingId' => $booking->getId() ]));
            }
        }

        return $this->render('@Hotel/Default/room_booking.html.twig',
        [
            'discount'      => $setting->getDiscountToday(),
            'filter_form'   => $filterForm->createView(),
            'booking_form'  => $roomBookingForm->createView(),
            'date_come'     => $dateCome,
            'date_left'     => $dateLeft,
            'physical_room' => $physicalRoom,
        ]);
    }

    /**
     * @param string $date
     * @param string $format
     *
     * @return bool
     */
    protected function isDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }


    public function roomBookingSuccessAction(Request $request, $bookingId)
    {
        return $this->render('@Hotel/Default/room_booking_success.html.twig');
    }


}
