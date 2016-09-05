<?php
require(APPPATH.'/libraries/REST_Controller.php');

class friends extends REST_Controller{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('friendsmodel');

    }

    function sendrequest_post()
    {
      $data=array(
      'user_from' => $this->post('user_from'),
      'user_to' => $this->post('user_to'),
      'status' => 0
    );
    $val = $this->friendsmodel->inserttable($data);

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


  function respondrequest_post()
  {
    $whrdata1=array(
    'user_from' => $this->post('user_from'),
    'user_to' => $this->post('user_to')
  );

  $whrdata2=array(
  'user_from' => $this->post('user_to'),
  'user_to' => $this->post('user_from')
);

  $upddata=array(
  'status' => $this->post('response')
);

  $val1 = $this->friendsmodel->updatetable($whrdata1,$upddata);
  $val2 = $this->friendsmodel->updatetable($whrdata2,$upddata);

  $result= array();

  if($val1== TRUE || $val2==TRUE)
  {
      $result=array('response'=>TRUE);

  }
  else
  {
     $result=array('response'=>FALSE);
  }
  $this->response($result);
}

  function friendcount_get()
  {
    $data1=array(
    'user_from' => $this->get('user'),
    'status' =>1
  );

  $data2=array(
  'user_to' => $this->get('user'),
  'status' =>1
);


  $count1 = $this->friendsmodel->retrivecountdata($data1);
  $count2 = $this->friendsmodel->retrivecountdata($data2);

  $val1=$count1[0]->total;
  $val2=$count2[0]->total;

  $result= array();

      $result=array('response'=>TRUE);
      $result=array('count'=>$val1+$val2);


  $this->response($result);
  }

  function friendlist_get()
  {
    $datato=array(
    'user_from' => $this->get('user'),
    'status' =>1
  );

  $datafrom=array(
  'user_to' => $this->get('user'),
  'status' =>1
);


  $listfrom = $this->friendsmodel->retrivedatafrom($datafrom);
  $listto = $this->friendsmodel->retrivedatato($datato);

  $friendlist=array();

  foreach($listfrom as $list)
  {
    array_push($friendlist,$list->user);
  }

  foreach($listto as $list)
  {
    array_push($friendlist,$list->user);
  }



  $result= array();

      $result=array('response'=>TRUE,'list'=>$friendlist);


  $this->response($result);
  }

  function checkstatus_get()
  {
    $result= array();

    {
      $datato=array(
      'user_from' => $this->get('user1'),
      'user_to' => $this->get('user2')
    );

      $datafrom=array(
      'user_to' => $this->get('user1'),
      'user_from' => $this->get('user2')
    );

    $stat1 = $this->friendsmodel->retrivestatusfrom($datafrom,$datato);
    $stat2 = $this->friendsmodel->retrivestatusto($datato,$datafrom);
    $status=array();
    if($stat1==null && $stat2==null)
    {
      $status[0]=2;
    }


    $usrtype=null;
    $status_string=null;
    foreach($stat1 as $list)
    {
      array_push($status,$list->status);
      $usrtype='from';
    }

    foreach($stat2 as $list)
    {
      array_push($status,$list->status);
      $usrtype='to';
    }

    if($status[0]==1)
    {
      $status_string='friends';
    }

    else if($status[0]==2)
    {
      $status_string='not friends';
    }

    else if($status[0]==0)
    {
      if($usrtype=='from')
      {
        $status_string='requested';
      }
      else{
        $status_string='respond';
      }
    }

        $result['response']=TRUE;
        $result['status']=$status_string;



    $this->response($result);
    }
  }
}
