<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class friendsmodel extends CI_Model{


  public function inserttable($data)
  {
    return  $this->db->insert('giira_friends', $data);
  }


  function retrivedata() {

      $this->db->from('giira_friends');
      $query = $this->db->get();
      return $result = $query->result();
  }
  function retriveparticulardata($data) {
      $this->db->where($data);
      $this->db->from('giira_friends');
      $query = $this->db->get();
      return $result = $query->result();
  }

  function retrivecountdata($data)
  {
      $this->db->select('COUNT(*) as total');
      $this->db->where($data);
      $this->db->from('giira_friends');
      $query = $this->db->get();
      return $result = $query->result();
  }

  public function updatetable($whrdata,$upddata)
  {

      $this->db->where($whrdata);
      return $this->db->update('giira_friends',$upddata);

  }

  function retrivedatato($data) {
    $this->db->select('user_to as user');
    $this->db->where($data);
    $this->db->from('giira_friends');
    $query = $this->db->get();
    return $result = $query->result();
  }

  function retrivedatafrom($data) {
    $this->db->select('user_from as user');
    $this->db->where($data);
    $this->db->from('giira_friends');
    $query = $this->db->get();
    return $result = $query->result();
  }

  function retrivestatusto($data,$data) {
    $this->db->select('status');
    $this->db->where($data);
    $this->db->from('giira_friends');
    $query = $this->db->get();
    return $result = $query->result();
  }

  function retrivestatusfrom($data,$data) {
    $this->db->select('status');
    $this->db->where($data);
    $this->db->from('giira_friends');
    $query = $this->db->get();
    return $result = $query->result();
  }

}
