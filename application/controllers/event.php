<?php
require(APPPATH.'/libraries/REST_Controller.php');

class event extends REST_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('eventmodel');

    }

  function  addevent_post()
  {
    if($this->post('image_name') != null)
    {
      $image_name = $this->post("name");
      $path = 'images/'.$image_name.".JPEG";
      $encoded_string = $this->post("encoded_string");
      $decoded_string = base64_decode($encoded_string);
      $file = fopen($path, 'wb');
      $is_written = fwrite($file, $decoded_string);
      fclose($file);
      $fullpath=$path;

      $data=array(
      'name' => $this->post('name'),
      'description' => $this->post('description'),
      'location' => $this->post('location'),
      'date' => $this->post('date'),
      'time' => $this->post('time'),
      'team' => $this->post('team'),
      'members' => $this->post('members'),
      'thumb'=>$path,
      'images'=>$fullpath
      );
    }

    else
    {
      $data=array(
      'name' => $this->post('name'),
      'description' => $this->post('description'),
      'location' => $this->post('location'),
      'date' => $this->post('date'),
      'time' => $this->post('time'),
      'team' => $this->post('team'),
      'members' => $this->post('members')
      );
    }

    $val = $this->eventmodel->inserttable('giira_events',$data);
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

  function  getevents_get()
  {
       $data=array('date'=>  $this->get('date'));
      $event=$this->eventmodel->retrieveparticulardata($data,'giira_events');

      $result= array('response'=>true,
          'event'=>$event
          );


      $this->response($result);
  }

  function  geteventdetails_get()
  {

      $data=array('event_id'=>  $this->get('event_id'));

      $event=$this->eventmodel->retrieveselecteddata($data,'giira_events');
      $result=array('response'=>TRUE,
          'event'=>$event);
      $this->response($result);
  }


}
