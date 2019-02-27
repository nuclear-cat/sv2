<?php

namespace HotelBundle\Controller;

use HotelBundle\Entity\Category;
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
use Symfony\Component\HttpFoundation\Response;


class SiteMapController extends Controller
{
    public function siteMapAction(Request $request)
    {
        $urls = [];
        $urls[]['loc'] = $this->generateUrl('hotel_homepage');
        $urls[]['loc'] = $this->generateUrl('hotel_contacts');
        $urls[]['loc'] = $this->generateUrl('hotel_rooms');
        $urls[]['loc'] = $this->generateUrl('hotel_gallery');
        $urls[]['loc'] = $this->generateUrl('hotel_reviews');

        $rooms = $this->getDoctrine()->getManager()->getRepository(Room::class)->findAll();
        $posts = $this->getDoctrine()->getManager()->getRepository(Post::class)->findAll();
        $postCategories = $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();

        /** @var Room $room */
        foreach ($rooms as $room) {
            $urls[]['loc'] = $this->generateUrl('hotel_room', [ 'roomId' => $room->getId() ]);
        }

        /** @var Post $post */
        foreach ($posts as $post) {
            $urls[]['loc'] = $this->generateUrl('hotel_posts', [ 'slug' => $post->getSlug() ]);
        }

        /** @var Category $category */
        foreach ($postCategories as $category) {
            $urls[]['loc'] = $this->generateUrl('hotel_posts', [ 'slug' => $category->getSlug() ]);
        }

        $response = new Response(
            $this->renderView('@Hotel/Default/sitemap.xml.twig', array( 'urls' => $urls,
                'hostname' => $request->getSchemeAndHttpHost())),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }

}
