<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class eventmodel extends CI_Model{

    public  function inserttable($table,$data)
    {
      return  $this->db->insert($table, $data);
    }

    public  function uploadfile($table,$data)
    {
      return  $this->db->insert($table, $data);
    }

    function retrievedata($table) {

        $this->db->from($table);
        $query = $this->db->get();
        return $result = $query->result();
    }

    function retrieveparticulardata($data,$table) {
        $this->db->select('time,name,event_id');
        $this->db->where($data);
        $this->db->from($table);
        $query = $this->db->get();
        return $result = $query->result();
    }

    function retrieveselecteddata($data,$table) {
      $this->db->where($data);
      $this->db->from($table);
      $query = $this->db->get();
      return $result = $query->result();

}
}
