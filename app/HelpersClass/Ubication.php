<?php

namespace App\HelpersClass;

class Ubication 
{
    protected $address;
    protected $latitude;
    protected $longitude;
    protected $description;

    public function __construct($address = '', $latitude = '', $longitude = '', $description = ''){
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->description = $description;
    }
    
    public function getAddress() 
    {
        return $this->address;
    }

    public function setAddress($address) 
    {
        $this->address = $address;
    }

    public function getLatitude() 
    {
        return $this->latitude;
    }

    public function setLatitude($latitude) 
    {
        $this->latitude = $latitude;
    } 

    public function getLongitude() 
    {
        return $this->longitude;
    }

    public function setLongitude($longitude) 
    {
        $this->longitude = $longitude;
    }

    public function getDescription() 
    {
        return $this->description;
    }

    public function setDescription($description) 
    {
        $this->description = $description;
    }

    public function getAll()
    {
        return 
        [
            'address'   => $this->getAddress(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
            'description' => $this->getDescription(),
        ];
    }
}