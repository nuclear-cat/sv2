<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HotelBundle\Entity\Room;
use ImageBundle\Entity\Image;
use HotelBundle\Entity\Post;
use HotelBundle\Entity\Category;
use ImageBundle\Entity\ImageProvider;
use HotelBundle\Entity\Feedback;
use ImageBundle\Form\ImageProviderType;
use ImageBundle\Repository\ImageProviderRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AdminBundle\Form\Object\FileObject;
use Doctrine\Common\Collections\ArrayCollection;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;


class RoomBookingAdminController extends Controller
{
    public function bookingsAction(Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $bookings       = $em->getRepository(\HotelBundle\Entity\RoomBooking::class)->findBy([], [ 'id' => 'DESC']);
        $paginator      = $this->get('knp_paginator');

        return $this->render('@Admin/room_booking/bookings.html.twig', ['bookings' =>  $bookings ]);
    }

    public function bookingEditorAction(Request $request, $bookingId = null)
    {
        $em = $this->getDoctrine()->getManager();

        if($bookingId > 0)
        {
            if(!$booking = $em->getRepository(\HotelBundle\Entity\RoomBooking::class)->findOneBy(['id' => $bookingId]))
            {
                die('Booking not found');
            }
        }
        else
        {
            $booking = new \HotelBundle\Entity\RoomBooking;
            $booking->setArrivalDate(new \DateTime(date('Y-m-d')));
            $leavingDate = new \DateTime(date('Y-m-d'));
            $leavingDate->modify('+1 day');
            $booking->setLeavingDate($leavingDate);
        }

        $form = $this->createFormBuilder($booking)
            ->add('physicalRoom', EntityType::class,
            [
                'placeholder'   => 'Выберите номер',
                'label'         => 'Номер',
                'class'         => \HotelBundle\Entity\PhysicalRoom::class,
                'choice_label'  => function($physicalRoom)
                {
                    if($physicalRoom->getRoom())
                    {
                        return $physicalRoom->getRoom()->getTitle() . ' ' . $physicalRoom->getNumber();
                    }
                    return $physicalRoom->getNumber();
                },
                'group_by'      => function($room)
                {
                    if($room->getRoom())
                    {
                        return $room->getRoom()->getTitle();
                    }
                    return '---';
                }
            ])
            ->add('arrivalDate', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,
            [
                'label'       => 'Дата заезда',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('leavingDate', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class,
            [
                'label'       => 'Дата выезда',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])

            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($booking);
            $em->flush();
            return $this->redirectToRoute('admin_room_booking_editor', ['bookingId' => $booking->getId()]);
        }

        return $this->render('@Admin/room_booking/booking_editor.html.twig',
        [
            'booking'       => $booking,
            'form'          => $form->createView(),
        ]);
    }

    /**
    * @param Request $request
    * @param int $bookingId
    */
    public function bookingRemoveAction(Request $request, $bookingId, $redirectUrl = null)
    {
        $em = $this->getDoctrine()->getManager();
        if($booking = $em->getRepository(\HotelBundle\Entity\RoomBooking::class)->findOneBy(['id' => $bookingId]))
        {
            $em->remove($booking);
            $em->flush();
        }

        if($redirectUrl = $request->query->get('redirectUrl'))
        {
            return $this->redirect(urldecode($redirectUrl));
        }
        return $this->redirectToRoute('admin_room_bookings');
    }

    /**
    * @param Request $request
    * @param int $bookingId
    */
    public function bookingConfirmAction(Request $request, $bookingId, $redirectUrl = null)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \HotelBundle\Entity\RoomBooking $booking */
        if($booking = $em->getRepository(\HotelBundle\Entity\RoomBooking::class)->findOneBy(['id' => $bookingId]))
        {
            $booking->setIsConfirmed(true);
            $em->persist($booking);
            $em->flush();
        }

        if($redirectUrl = $request->query->get('redirectUrl'))
        {
            return $this->redirect(urldecode($redirectUrl));
        }

        return $this->redirectToRoute('admin_room_bookings');
    }











}
