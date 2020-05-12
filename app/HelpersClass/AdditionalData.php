<?php

namespace App\HelpersClass;

class AdditionalData 
{
    protected $emergency;
    protected $event;
    protected $problem;
    protected $activity;
    protected $post;
   
    public function __construct() 
    {
        $this->emergency = [
            "attended_by" => null,
            'rechazed_by' => null,
            'rechazed_reason' => null
        ];
        $this->event = [
            "responsible" => null,
            "range_date" => [
                'start_date' => date("Y-m-d"),
                'end_date' => date("Y-m-d",strtotime(date("Y-m-d")."+ 1 week")),
                'start_time' => date("H:i:s"),
                'end_time' => date("H:i:s", strtotime('+3 hours', strtotime(date("H:i:s")))) 
            ]
        ];
        $this->problem = null;
        $this->activity = null;
        $this->post = [
            "approved_by" => null
        ];
    }
    
    public function getInfoEmergency() 
    {
        return $this->emergency;
    }

    public function setInfoEmergency($info_emergency) 
    {
        $this->emergency = $info_emergency;
    }
    
    public function getInfoEvent() 
    {
        return $this->event;
    }

    public function setInfoEvent($info_event) 
    {
        $this->event = $info_event;
    }

    public function getInfoSocialProblem() 
    {
        return $this->problem;
    }

    public function setInfoSocialProblem($info_social_problem) 
    {
        $this->problem = $info_social_problem;
    }

    public function getInfoActivity() 
    {
        return $this->activity;
    }

    public function setInfoActivity($info_activity) 
    {
        $this->activity = $info_activity;
    }

    public function getInfoPost() 
    {
        return $this->post;
    }

    public function setInfoPost($info_post) 
    {
        $this->post = $info_post;
    }

    public function getAll()
    {
        return 
        [
            'emergency'   => $this->getInfoEmergency(),
            'event' => $this->getInfoEvent(),
            'problem' => $this->getInfoSocialProblem(),
            'activity' => $this->getInfoActivity(),
            'post' => $this->getInfoPost(),
        ];
    }
}