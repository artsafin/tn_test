<?php

namespace TnTest\Domain;


use Money\Money;

class Ad
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $ownerName;

    /**
     * @var string
     */
    private $ownerPhone;

    /**
     * @var AdStatus
     */
    private $status;

    /**
     * @var int
     */
    private $adType;

    /**
     * @var Money
     */
    private $price;

    /**
     * @var int
     */
    private $sellerType;

    /**
     * @var int
     */
    private $floor;

    /**
     * @var int
     */
    private $totalFloors;

    /**
     * @var int
     */
    private $numRooms;

    /**
     * @var float
     */
    private $totalSquare;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $source;

    /**
     * Ad constructor.
     *
     * @param int      $id
     * @param string   $address
     * @param string   $ownerName
     * @param string   $ownerPhone
     * @param AdStatus $status
     * @param int      $adType
     * @param Money    $price
     * @param int      $sellerType
     * @param int      $floor
     * @param int      $totalFloors
     * @param int      $numRooms
     * @param float    $totalSquare
     * @param string   $description
     * @param string   $source
     */
    public function __construct($id,
                                $address,
                                $ownerName,
                                $ownerPhone,
                                AdStatus $status,
                                $adType,
                                Money $price,
                                $sellerType,
                                $floor,
                                $totalFloors,
                                $numRooms,
                                $totalSquare,
                                $description,
                                $source)
    {
        $this->id          = $id;
        $this->address     = $address;
        $this->ownerName   = $ownerName;
        $this->ownerPhone  = $ownerPhone;
        $this->status      = $status;
        $this->adType      = $adType;
        $this->price       = $price;
        $this->sellerType  = $sellerType;
        $this->floor       = $floor;
        $this->totalFloors = $totalFloors;
        $this->numRooms    = $numRooms;
        $this->totalSquare = $totalSquare;
        $this->description = $description;
        $this->source      = $source;
    }


    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return Money
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @return int
     */
    public function getNumRooms()
    {
        return $this->numRooms;
    }

    /**
     * @return float
     */
    public function getTotalSquare()
    {
        return $this->totalSquare;
    }

    /**
     * @return string
     */
    public function getSourceCode()
    {
        return $this->source;
    }

    public function isOwner()
    {
        return $this->sellerType === AdEnums::SELLERTYPE_OWNER;
    }

    public function isReseller()
    {
        return $this->sellerType == AdEnums::SELLERTYPE_RESELLER;
    }

    public function automoderationPassed()
    {
        $this->status = AdStatus::automoderationPassed();
    }

    public function automoderationFailed()
    {
        $this->status = AdStatus::automoderationFailed();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatusString()
    {
        return (string) $this->status;
    }

    public function ownerPhoneMatches($phone)
    {
        $a = trim($this->ownerPhone);
        $b = trim($phone);

        return $a == $b || ltrim($a, '+') == ltrim($b, '+');
    }

    public function descriptionContains($word)
    {
        return mb_stripos($this->description, $word, 0, 'utf-8');
    }

    /**
     * @return string
     */
    public function getOwnerPhone()
    {
        return $this->ownerPhone;
    }

    /**
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * @return int
     */
    public function getAdType()
    {
        return $this->adType;
    }

    /**
     * @return int
     */
    public function getSellerType()
    {
        return $this->sellerType;
    }

    /**
     * @return int
     */
    public function getTotalFloors()
    {
        return $this->totalFloors;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    public function withId($id)
    {
        return new self($id,
                        $this->address,
                        $this->ownerName,
                        $this->ownerPhone,
                        $this->status,
                        $this->adType,
                        $this->price,
                        $this->sellerType,
                        $this->floor,
                        $this->totalFloors,
                        $this->numRooms,
                        $this->totalSquare,
                        $this->description,
                        $this->source);
    }
}