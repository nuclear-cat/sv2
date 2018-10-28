<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhysicalRoom
 *
 * @ORM\Table(name="physical_room")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\PhysicalRoomRepository")
 */
class PhysicalRoom
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="floor", type="integer", nullable=true)
     */
    private $floor;

    /**
     * @var bool
     *
     * @ORM\Column(name="number", type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="RoomBooking", mappedBy="physicalRoom")
     */
    private $bookings;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="physicalRooms")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    private $room;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bookings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set floor
     *
     * @param integer $floor
     *
     * @return PhysicalRoom
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor
     *
     * @return integer
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return PhysicalRoom
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Add booking
     *
     * @param \HotelBundle\Entity\RoomBooking $booking
     *
     * @return PhysicalRoom
     */
    public function addBooking(\HotelBundle\Entity\RoomBooking $booking)
    {
        $this->bookings[] = $booking;

        return $this;
    }

    /**
     * Remove booking
     *
     * @param \HotelBundle\Entity\RoomBooking $booking
     */
    public function removeBooking(\HotelBundle\Entity\RoomBooking $booking)
    {
        $this->bookings->removeElement($booking);
    }

    /**
     * Get bookings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * Set room
     *
     * @param \HotelBundle\Entity\Room $room
     *
     * @return PhysicalRoom
     */
    public function setRoom(\HotelBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \HotelBundle\Entity\Room
     */
    public function getRoom()
    {
        return $this->room;
    }
}
