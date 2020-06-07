<?php

namespace App\HelpersClass;

class Membership 
{
    protected $identity_card;
    protected $basic_service_image;
    protected $approved_by;
    protected $rechazed_by;
    protected $rechazed_reason;

    public function __construct($identity_card = '', $basic_service_image = '', $approved_by = '', $rechazed_by = '', $rechazed_reason = ''){
        $this->identity_card = $identity_card;
        $this->basic_service_image = $basic_service_image;
        $this->approved_by = $approved_by;
        $this->rechazed_by = $rechazed_by;
        $this->rechazed_reason = $rechazed_reason;
    }
    
    public function getIdentityCard() 
    {
        return $this->identity_card;
    }

    public function setIdentityCard($identity_card) 
    {
        $this->identity_card = $identity_card;
    }

    public function getBasicServiceImage() 
    {
        return $this->basic_service_image;
    }

    public function setBasicServiceImage($basic_service_image) 
    {
        $this->basic_service_image = $basic_service_image;
    } 

    public function getApprovedBy() 
    {
        return $this->approved_by;
    }

    public function setApprovedBy($approved_by) 
    {
        $this->approved_by = $approved_by;
    }

    public function getRechazedReason() 
    {
        return $this->rechazed_reason;
    }

    public function setRechazedReason($rechazed_reason) 
    {
        $this->rechazed_reason = $rechazed_reason;
    }

    public function getRechazedBy() 
    {
        return $this->rechazed_by;
    }

    public function setRechazedBy($rechazed_by) 
    {
        $this->rechazed_by = $rechazed_by;
    }

    public function getAll()
    {
        return 
        [
            'identity_card'   => $this->getIdentityCard(),
            'basic_service_image' => $this->getBasicServiceImage(),
            'approved_by' => $this->getApprovedBy(),
            'rechazed_by' => $this->getRechazedBy(),
            'rechazed_reason' => $this->getRechazedReason(),
        ];
    }
}