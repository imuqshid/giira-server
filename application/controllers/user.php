<?php
require(APPPATH.'/libraries/REST_Controller.php');

class user extends REST_Controller{


  public function __construct()
  {
      parent::__construct();

//        $this->load->helper('url');

      $this->load->model('usermodel');

  }


function login_get()
{
  $email=$this->get('email');
  $password=$this->get('password');

  $user=$this->usermodel->login($email,$password);

  $result= array('response'=>true,
      'user'=>$user
      );


  $this->response($result);
}

function register_post()
{

  $data=array(
  'name' => $this->post('name'),
  'email' => $this->post('email'),
  'encrypted_password' => $this->post('password')
  );

  $val = $this->usermodel->inserttable($data);
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

}
