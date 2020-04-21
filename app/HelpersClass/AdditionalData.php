<?php

namespace App\HelpersClass;

class AdditionalData 
{
    protected $info_emergency;
    protected $info_event;
    protected $info_social_problem;
    protected $info_activity;
    protected $info_post;
    
    // public function __construct(array $data) 
    // {
    //     $this->info_emergency = $data['info_emergency'];
    //     $this->info_event = $data['info_event'];
    //     $this->info_social_problem = $data['info_social_problem'];
    //     $this->info_activity = $data['info_activity'];
    //     $this->info_post = $data['info_post'];
    // }

    public function __construct() 
    {
        $this->info_emergency = [
            "attended_by" => null,
            'rechazed_by' => null,
            'rechazed_reason' => null
        ];
        $this->info_event = [
            "responsable" => null
        ];
        $this->info_social_problem = null;
        $this->info_activity = null;
        $this->info_post = [
            "approved_by" => null
        ];
    }
    
    public function getInfoEmergency() 
    {
        return $this->info_emergency;
    }

    public function setInfoEmergency($info_emergency) 
    {
        $this->info_emergency = $info_emergency;
    }
    
    public function getInfoEvent() 
    {
        return $this->info_event;
    }

    public function setInfoEvent($info_event) 
    {
        $this->info_event = $info_event;
    }

    public function getInfoSocialProblem() 
    {
        return $this->info_social_problem;
    }

    public function setInfoSocialProblem($info_social_problem) 
    {
        $this->info_social_problem = $info_social_problem;
    }

    public function getInfoActivity() 
    {
        return $this->info_activity;
    }

    public function setInfoActivity($info_activity) 
    {
        $this->info_activity = $info_activity;
    }

    public function getInfoPost() 
    {
        return $this->info_post;
    }

    public function setInfoPost($info_post) 
    {
        $this->info_post = $info_post;
    }

    public function getAll()
    {
        return 
        [
            'info_emergency'   => $this->getInfoEmergency(),
            'info_event' => $this->getInfoEvent(),
            'info_social_problem' => $this->getInfoSocialProblem(),
            'info_activity' => $this->getInfoActivity(),
            'info_post' => $this->getInfoPost(),
        ];
    }
}
// $person = new Person(array('id' => 1, 'name' => 'Amir'));
// echo json_encode($person);