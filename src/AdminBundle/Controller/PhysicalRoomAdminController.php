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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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


class PhysicalRoomAdminController extends Controller
{
    public function physicalRoomsAction(Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $physicalRooms  =  $em->getRepository(\HotelBundle\Entity\PhysicalRoom::class)->findAll();

        return $this->render('@Admin/physical_room/physical_rooms.html.twig', ['physical_rooms' =>  $physicalRooms ]);
    }





    public function physicalRoomEditorAction(Request $request, $physicalRoomId = null)
    {
        $roomId = $request->query->get('roomId');

        $em = $this->getDoctrine()->getManager();

        if($physicalRoomId > 0)
        {
            if(!$physicalRoom = $em->getRepository(\HotelBundle\Entity\PhysicalRoom::class)->findOneBy(['id' => $physicalRoomId]))
            {
                die('Physical room not found');
            }
        }
        else
        {
            $physicalRoom = new \HotelBundle\Entity\PhysicalRoom;
        }

        if($roomId > 0)
        {
            if($room = $em->getRepository(\HotelBundle\Entity\Room::class)->find($roomId))
            {
                $physicalRoom->setRoom($room);
            }
            else
            {
                die('Room not found');
            }
        }

        $form = $this->createFormBuilder($physicalRoom)

            ->add('number', TextType::class, [ 'label' => 'Номер помещения' ])
            ->add('floor', IntegerType::class, [ 'required' => false, 'label' => 'Этаж' ])
            ->add('room', EntityType::class,
            [
                'label'         => 'Номер',
                'required'      => true,
                'placeholder'   => '-- Выберите номер --',
                'class'         => \HotelBundle\Entity\Room::class,
                'choice_label'  => 'title',

            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($physicalRoom);
            $em->flush();
            return $this->redirectToRoute('admin_physical_room_editor', ['physicalRoomId' => $physicalRoom->getId()]);
        }

        return $this->render('@Admin/physical_room/physical_room_editor.html.twig',
        [
            'physical_room' => $physicalRoom,
            'form'          => $form->createView(),
        ]);
    }

    /**
    * @param Request $request
    * @param int $physicalRoomId
    */
    public function physicalRoomRemoveAction(Request $request, $physicalRoomId)
    {
        $roomId = $request->query->get('roomId');


        $em = $this->getDoctrine()->getManager();
        if($physicalRoom = $em->getRepository(\HotelBundle\Entity\PhysicalRoom::class)->findOneBy(['id' => $physicalRoomId]))
        {
            $em->remove($physicalRoom);
            $em->flush();

            if($roomId > 0)
            {
                return $this->redirectToRoute('admin_rooms');
            }
            
            return $this->redirectToRoute('admin_physical_rooms');
        }
    }











}
