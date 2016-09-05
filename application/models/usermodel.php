<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class usermodel extends CI_Model{

  public function login($email, $password)
  {
   $this-> db -> select('name,email');
   $this-> db -> from('giira_users');
   $this-> db -> where('email', $email);
   $this-> db -> where('encrypted_password', $password);
   $this-> db -> limit(1);
   $query = $this -> db -> get();
   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
  }

  public  function inserttable($data)
  {
    return  $this->db->insert('giira_users', $data);
  }

}
