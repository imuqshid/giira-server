<?php
require(APPPATH.'/libraries/REST_Controller.php');

class team extends REST_Controller{

    public function __construct()
    {
        parent::__construct();

//        $this->load->helper('url');

        $this->load->model('teammodel');

    }

    function  addteam_post()
    {
        $data=array(
        'tname' => $this->post('tname'),
        'description' => $this->post('description'),
        'member1' => $this->post('member1'),
        'member2' => $this->post('member2'),
        'member3' => $this->post('member3'),
        'member4' => $this->post('member4'),
        'member5' => $this->post('member5'),
        'member6' => $this->post('member6'),
        'member7' => $this->post('member7'),
        'member8' => $this->post('member8'),
        'member9' => $this->post('member9'),
        'member10' => $this->post('member10'),

        );

      $val = $this->teammodel->inserttable('giira_team',$data);


      $result= array();

      if($val== TRUE)
      {
          $result=array('response'=>TRUE);

      }
      else
      {
         $result=array('response'=>FALSE);
      }
      $this->response($result);

    }

    function  getteam_get()
    {

        $data=array('tname'=>  $this->get('tname'));

        $val = $this->teammodel->retrivedata($data,'giira_team');


        $result=array('response'=>TRUE,
              'team'=>team);
        $this->response($result);

    }
  }
