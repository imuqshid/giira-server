<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class placemodel extends CI_Model{
    
    public  function inserttable($table,$data)
    {
      return  $this->db->insert($table, $data);
    }


    function retrivedata($table) {

        $this->db->from($table);
        $query = $this->db->get();
        return $result = $query->result();
    }
    function retriveparticulardata($data,$table) {
        $this->db->where($data);
        $this->db->from($table);
        $query = $this->db->get();
        return $result = $query->result();
    }
    
    function retrivelikedata($data,$table)
    {
         $this->db->like($data);
        
        $this->db->from($table);
        $query = $this->db->get();
        return $result = $query->result();
    }

    public  function updatetable($whrdata,$upddata,$table)
    {

        $this->db->where($whrdata);
        $this->db->update($table,$upddata);

    }
    
    public  function uploadfile($table,$data)
    {
      return  $this->db->insert($table, $data);
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

