<?php

namespace App\HelpersClass;

class Membership 
{
    protected $identity_card;
    protected $basic_service_image;
    protected $approved;
    protected $rechazed;
    protected $status_attendance = 'pendiente';//pendiente, aprovado, rechazado

    public function __construct($identity_card = null, $basic_service_image = null){
        $this->identity_card = $identity_card;
        $this->basic_service_image = $basic_service_image;
        $this->approved = [
            'who'=>null,//usuario que aprobó la solicitud
            'date'=>null,//fecha de aprovación
        ];
        $this->rechazed = [
            'who' =>null,
            'reason'=>null,//razón del rechazo
            'date'=>null,
        ];
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

    public function getApproved() 
    {
        return $this->approved;
    }

    public function setApproved($approved) 
    {
        $this->approved = array_merge($this->approved, $approved);
        
    }

    // public function getRechazedReason() 
    // {
    //     return $this->rechazed_reason;
    // }

    // public function setRechazedReason($rechazed_reason) 
    // {
    //     $this->rechazed_reason = $rechazed_reason;
    // }

    public function getRechazed() 
    {
        return $this->rechazed;
    }

    public function setRechazed($rechazed) 
    {
        $this->rechazed = array_merge($this->rechazed, $rechazed);
    }

    public function getStatusAttendance() 
    {
        return $this->status_attendance;
    }

    public function setStatusAttendance($status_attendance) 
    {
        $this->status_attendance = $status_attendance;
    }

    public function getAll()
    {
        return 
        [
            'identity_card'   => $this->getIdentityCard(),
            'basic_service_image' => $this->getBasicServiceImage(),
            'approved' => $this->getApproved(),
            'rechazed' => $this->getRechazed(),
            'status_attendance' => $this->getStatusAttendance(),
        ];
    }
}