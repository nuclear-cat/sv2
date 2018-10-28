<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoomBooking
 *
 * @ORM\Table(name="room_booking")
 * @ORM\Entity(repositoryClass="HotelBundle\Repository\RoomBookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class RoomBooking
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
     * @var \DateTime
     *
     * @ORM\Column(name="arrival_date", type="datetime")
     */
    private $arrivalDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="leaving_date", type="datetime")
     */
    private $leavingDate;

    /**
     * @ORM\ManyToOne(targetEntity="PhysicalRoom", inversedBy="bookings")
     * @ORM\JoinColumn(name="physical_room_id", referencedColumnName="id")
     */
    private $physicalRoom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=true)
     */
    private $datetime;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255, nullable=true)
     */
    private $ipAddress;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_readed", type="boolean", nullable=true)
     */
    private $isReaded;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;


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
     * Set arrivalDate
     *
     * @param \DateTime $arrivalDate
     *
     * @return RoomBooking
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * Set leavingDate
     *
     * @param \DateTime $leavingDate
     *
     * @return RoomBooking
     */
    public function setLeavingDate($leavingDate)
    {
        $this->leavingDate = $leavingDate;

        return $this;
    }

    /**
     * Get leavingDate
     *
     * @return \DateTime
     */
    public function getLeavingDate()
    {
        return $this->leavingDate;
    }

    /**
     * Set physicalRoom
     *
     * @param \HotelBundle\Entity\PhysicalRoom $physicalRoom
     *
     * @return RoomBooking
     */
    public function setPhysicalRoom(\HotelBundle\Entity\PhysicalRoom $physicalRoom = null)
    {
        $this->physicalRoom = $physicalRoom;

        return $this;
    }

    /**
     * Get physicalRoom
     *
     * @return \HotelBundle\Entity\PhysicalRoom
     */
    public function getPhysicalRoom()
    {
        return $this->physicalRoom;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Feedback
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \DateTime $datetime
     *
     * @return RoomBooking
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @ORM\PrePersist
     */
    public function createDatetime()
    {
        $this->datetime = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function createIpAddress()
    {
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Room
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * Set isReaded
     *
     * @param boolean $isReaded
     *
     * @return Feedback
     */
    public function setIsReaded($isReaded)
    {
        $this->isReaded = $isReaded;

        return $this;
    }

    /**
     * Get isReaded
     *
     * @return boolean
     */
    public function getIsReaded()
    {
        return $this->isReaded;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return Feedback
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Feedback
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

}
