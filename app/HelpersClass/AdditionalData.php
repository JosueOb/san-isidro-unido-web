<?php
namespace App\HelpersClass;

class AdditionalData 
{
    protected $emergency;
    protected $event;
    protected $social_problem;
    protected $activity;
    protected $post;
   
    public function __construct() 
    {
        $this->social_problem = [
            "approved" => [
                'who'=>null,//usuario que aprobó el problema
                'date'=>null,//fecha de aprobación
            ],
            "rechazed"=>[
                'who'=>null,//usuario que rechazó el problema social
                'reason'=>null,//razón del rechazo del problema social
                'date'=>null,//fecha de rechado
            ],
            "attended"=>[
                'who'=>null,//usuario que cambió a solucionado el problema
                'date'=>null,//fecha en la que se atendió al problema
            ],
            "status_attendance" => 'pendiente' //aprobado, rechazado, atendido(solucionado), pendiente
        ];
        $this->emergency = [
            // "published" => [
            //     'who'=>null,// moderador que aprobó la publicación de la emergencia
            //     'date'=>null,//fecha de aprobación
            // ],
            "rechazed"=>[
                'who'=>null,//policía que rechazó la emergencia
                'reason'=>null,//razón del rechazo
                'date'=>null,//fecha de rechado
            ],
            "attended"=>[
                'who'=>null,//policía que atendió la emergencia
                'date'=>null,//fecha en la que se atendió
            ],
            "status_attendance" => 'pendiente' //pendiente, atendido, rechazado
        ];
        $this->event = [
            "responsible" => null,
            "range_date" => [
                'start_date' => date("Y-m-d"),
                'end_date' => date("Y-m-d",strtotime(date("Y-m-d")."+ 1 week")),
                'start_time' => date("H:i:s"),
                'end_time' => date("H:i:s", strtotime('+3 hours', strtotime(date("H:i:s")))) 
            ],
            "approved" => [
                'who'=>null,//usuario que aprobó el problema
                'date'=>null,//fecha de aprobación
            ],
            "status_attendance" => 'pendiente' //atendido, rechazado, pendiente
        ];
        // $this->activity = [
        //     "approved" => [
        //         'who'=>null,//usuario que aprobó el problema
        //         'date'=>null,//fecha de aprobación
        //     ],
        //     "status_attendance" => 'pendiente' //atendido, rechazado, pendiente
        // ];
    }
    
    public function getInfoSocialProblem() 
    {
        return $this->social_problem;
    }

    public function setInfoSocialProblem($info_social_problem) 
    {
        $this->social_problem = array_merge($this->social_problem, $info_social_problem);
    }
    public function getInfoEmergency() 
    {
        return $this->emergency;
    }

    public function setInfoEmergency(array $info_emergency) 
    {
        $this->emergency = array_merge($this->emergency, $info_emergency);
    }
    
    public function getInfoEvent() 
    {
        return $this->event;
    }

    public function setInfoEvent($info_event) 
    {
        $this->event = array_merge($this->event, $info_event);
    }

    

    // public function getInfoActivity() 
    // {
    //     return $this->activity;
    // }

    // public function setInfoActivity($info_activity) 
    // {
    //     $this->activity = array_merge($this->activity, $info_activity);
    // }

    public function getAll()
    {
        return 
        [
            'emergency'   => $this->getInfoEmergency(),
            'event' => $this->getInfoEvent(),
            'problem' => $this->getInfoSocialProblem(),
            // 'activity' => $this->getInfoActivity(),
        ];
    }

    // public function getEmergencyData()
    // {
    //     return $this->getInfoEmergency();
    // }

    // public function getEventData()
    // {
    //     return $this->getInfoEvent();
    // }

    // public function getProblemData()
    // {
    //     return $this->getInfoSocialProblem();
    // }

    // public function getActivityData()
    // {
    //     return $this->getInfoActivity();
    // }
}