<?php

namespace App\HelpersClass;

class ResponisbleMembership
{
    protected $approved;
    protected $rechazed;

    public function __construct()
    {
        $this->approved = [
            'who'=>null,//morador que aprobó la solicitud
            'date'=>null,//fecha de aprobación
        ];
        $this->rechazed = [
            'who' =>null, //morador que rechazó la solicitud
            'reason'=>null,//razón del rechazo
            'date'=>null,//fecha de rechazo
        ];
    }

    public function getApproved() 
    {
        return $this->approved;
    }

    public function setApproved($approved) 
    {
        $this->approved = array_merge($this->approved, $approved);
        
    }
    public function getRechazed() 
    {
        return $this->rechazed;
    }

    public function setRechazed($rechazed) 
    {
        $this->rechazed = array_merge($this->rechazed, $rechazed);
    }

    public function getAll()
    {
        return 
        [
            'approved' => $this->getApproved(),
            'rechazed' => $this->getRechazed(),
        ];
    }
}
