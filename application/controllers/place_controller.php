<?php
require(APPPATH.'/libraries/REST_Controller.php');

class place_controller extends REST_Controller{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('url','file');


        $this->load->model('placemodel');
        ;



    }
//    function index()
//    {
//        var_dump('die');die;
//    }
    
    function test_post()
    {
       
 	
	$encoded_string = $this->post("encoded_string");
	$image_name = $this->post("image_name");
	
	$decoded_string = base64_decode($encoded_string);
	
	$path = 'images/'.$image_name;
	
	$file = fopen($path, 'wb');
	
	$is_written = fwrite($file, $decoded_string);
	fclose($file);
	$data=array('response'=>TRUE);
        $this->response($data);
       
//	
//	
 
        
    }
            function  insertplace_post()
    {
      
//        $placename=$this->post('place_name');
//        $placedesc=$this->post('place_description');
//        $lati=$this->post('place_latitude');
//        $longi=$this->post('place_longitude');
//
//
//        $data=array('place_name'=>$placename,
//                    'place_description'=>$placedesc,
//                    'place_latitude'=>$lati,
//                    'place_longitude'=>$longi
//        );
//        $place=$this->placemodel->inserttable('places',$data);
//
//        if(!empty($place))
//        {
//            $reponse=array();
//            $reponse['message']=true;
//
//
//        }
//        else
//        {
//            $reponse=array();
//            $reponse['message']=false;
//
//        }
//        $this->response($place);
        
        $data=array(
        'place_name' => $this->post('place_name'),
        'place_description' => $this->post('place_description'),
        'place_latitude' => $this->post('place_latitude'),
        'place_longitude'=>  $this->post('place_longitude'));

        $val = $this->placemodel->inserttable('places',$data);  
        
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
    
    function  retriveplaces_get()
    {
//        $placename=$this->get('place_name');
//        $placedesc=$this->get('place_description');
//        $lati=$this->get('place_latitude');
//        $longi=$this->get('place_longitude');
//
//
//        $data=array('place_name'=>$placename,
//                    'place_description'=>$placedesc,
//                    'place_latitude'=>$lati,
//                    'place_longitude'=>$longi
//        );
        $place=$this->placemodel->retrivedata('places');
        
        
        
        
//        if(!empty($place))
//        {
//            $reponse=array();
//            $reponse['message']=true;
//
//
//        }
//        else
//        {
//            $reponse=array();
//            $reponse['message']=false;
//
//        }
//        $this->response($place);

        $result= array('response'=>true,
            'place'=>$place
            );

     
        $this->response($result);
    }
    
    
    
    public function upload_post()
        {
//        $this->load->helper(array('url','file'));
                
                $config['upload_path']          = 'http://192.168.56.1/codres/uploads/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 100;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;
                $this->load->library('upload', $config);
                
//                $this->upload_post->do_upload();
                
                if(!$this->upload_post()->do_upload())
 
       {
 
           $result=array('response'=>FALSE);
 
       }
       
       else
 
       {
 
           
                $dataa=array('upload_data'=>  $this->upload_post->dataa());
                $data=array(
        'placename' => $this->post('placename'),
        'placeimage' => $dataa['upload_data']['file_name']
                        );
                $this->placemodel->uploadfile('placeimages',$data);
                
                $result= array();
 
       }
                
                

//                if ( ! $this->upload->do_upload('userfile'))
//                {
//                        $result=array('response'=>FALSE);
//                }
//                else
//                {
//                        $result=array('response'=>TRUE);
//                }
                $this->response($result);
        }
}

