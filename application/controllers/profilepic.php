<?php
require(APPPATH.'/libraries/REST_Controller.php');

class event extends REST_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('profilepicmodel');

    }
  function  uploadimage_post()
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
      'thumb'=>$path,
      'images'=>$fullpath
      );
    }
    $val = $this->profilepicmodel->uploadfile('giira_usres',$data);
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
