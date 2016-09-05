<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class teammodel extends CI_Model{

    public  function inserttable($table,$data)
    {
      return  $this->db->insert($table, $data);
    }
    
    function retrivedata($table) {

        $this->db->from($table);
        $query = $this->db->get();
        return $result = $query->result();
    }

}
