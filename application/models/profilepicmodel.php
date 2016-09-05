<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class profilepicmodel extends CI_Model{

  public  function uploadfile($table,$data)
  {
    return  $this->db->insert($table, $data);
  }
}
