<?php
require(APPPATH.'/libraries/REST_Controller.php');

class places extends REST_Controller{
    
    public function __construct()
    {
        parent::__construct();
        
//        $this->load->helper('url');
        
        $this->load->model('placemodel');
         


    }


function giiraplaces_post()
    {
        $image_name = $this->post("name");
        $path = 'images/'.$image_name.".JPEG";
       
        
        
        
        
	$encoded_string = $this->post("encoded_string");
        $encoded_string2 = $this->post("encoded_string2");
        $encoded_string3 = $this->post("encoded_string3");
        $encoded_string4 = $this->post("encoded_string4");
        
	$decoded_string = base64_decode($encoded_string);
        $decoded_string2 = base64_decode($encoded_string2);
        $decoded_string3 = base64_decode($encoded_string3);
        $decoded_string4 = base64_decode($encoded_string4);
        
        
	
	$file = fopen($path, 'wb');
	$is_written = fwrite($file, $decoded_string);
	fclose($file);
        
        $fullpath=$path;
        
        if($decoded_string2 != NULL ){ 
            $path2 = 'images/'.$image_name."2.JPEG";
            $file2 = fopen($path2, 'wb');
            fwrite($file2, $decoded_string2);
            fclose($file2);
            $fullpath=$path.",".$path2;
            
        
            if($decoded_string3 != NULL){
                $path3 = 'images/'.$image_name."3.JPEG";
                $file3 = fopen($path3, 'wb');
                fwrite($file3, $decoded_string3);
                fclose($file3);
                $fullpath=$fullpath.",".$path3;
                
        
                    if($decoded_string4 != NULL){
                        $path4 = 'images/'.$image_name."4.JPEG";
                            $file4 = fopen($path4, 'wb');
                            fwrite($file4, $decoded_string4);
                            fclose($file4);
                            $fullpath=$fullpath.",".$path4;
                            
        
                    }
            }
        }
        
        
        if($is_written>0){   
            $data=array(
        'name' => $this->post('name'),
        'description' => $this->post('description'),
        'address' => $this->post('address'),
        'tag' => $this->post('tag'),
        'thumb'=>$path,
        'images'=>$fullpath);
        $val = $this->placemodel->inserttable('giira_places',$data);  
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

        
     function  retriveplaces_get()
     {
       
       
       
        $data=array('name'=> $this->get("place"));
      
       
        $place=$this->placemodel->retrivelikedata($data,'giira_places'); 

        $result= array('response'=>true,
            'place'=>$place
            );
        $this->response($result);
     }
     
     function  retrivecategory_get()
     {
       
      $category=$this->placemodel->retrivedata('giira_category_places'); 

        $result= array('response'=>true,
            'category'=>$category
            );
        $this->response($result);
     }
     
     function  retriveregion_get()
     {
       
      $region=$this->placemodel->retrivedata('giira_region'); 

        $result= array('response'=>true,
            'regions'=>$region
            );
        $this->response($result);
     }
     
     
     
     
     
     
     
     public function getplacedetails_get()
     {
         $data=array('id'=>  $this->get('id'));
         $place=$this->placemodel->retriveparticulardata($data,'giira_places');
         $result=array('response'=>TRUE,
             'places'=>$place);
         $this->response($result);
         
      }
      
      public function getcategoryplaces_get()
     {
         $data=array('category'=>  $this->get('category'));
         $place=$this->placemodel->retriveparticulardata($data,'giira_places');
         $result=array('response'=>TRUE,
             'category'=>$place);
         $this->response($result);
         
      }
      
      public function getregionplaces_get()
     {
         $data=array('region'=>  $this->get('region'));
         $place=$this->placemodel->retriveparticulardata($data,'giira_places');
         $result=array('response'=>TRUE,
             'regions'=>$place);
         $this->response($result);
         
      }
      
      public function getAccommodationdetails_get()
     {
         $data=array('id'=>  $this->get('id'));
         $Acco=$this->placemodel->retriveparticulardata($data,'giira_hotels');
         $result=array('response'=>TRUE,
             'accomodation'=>$Acco);
         $this->response($result);
         
      }
      
      
      function  addreviews_post()
    { 
          
        
        $data=array(
        'comment' => $this->post('comment'),
        'place_id' => $this->post('place_id')
        );

        $val = $this->placemodel->inserttable('giira_rating',$data);  
        
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
    
    public function getcomments_get()
     {
         $data=array('place_id'=>  $this->get('place_id'));
         $rating=$this->placemodel->retriveparticulardata($data,'giira_rating');
         $result=array('response'=>TRUE,
             'Reviews'=>$rating);
         $this->response($result);
         
      }
      
      public function getaccomodation_get()
     {
         $data=array('place_id'=>  $this->get('place_id'));
         $accomodation=$this->placemodel->retriveparticulardata($data,'giira_hotels');
         $result=array('response'=>TRUE,
             'accomodation'=>$accomodation);
         $this->response($result);
         
      }
      
      public function gettravalling_get()
     {
         $data=array('place_id'=>  $this->get('place_id'));
         $travelling=$this->placemodel->retriveparticulardata($data,'giira_travelling');
         $result=array('response'=>TRUE,
             'travelling'=>$travelling);
         $this->response($result);
         
      }
      
      
        
    }

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

