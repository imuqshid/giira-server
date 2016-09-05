<?php
 header('Content-type : bitmap; charset=utf-8');
 
 if(isset($_POST["encoded_string"])){
 	
	$encoded_string = $this->post("encoded_string");
	$image_name = $this->post("image_name");
	
	$decoded_string = base64_decode($encoded_string);
	
	$path = 'images/'.$image_name;
	
	$file = fopen($path, 'wb');
	
	$is_written = fwrite($file, $decoded_string);
	fclose($file);
	
	if($is_written > 0) {
		
		$connection = mysqli_connect('localhost', 'root', '','testing');
		$query = "INSERT INTO testimage(imagename,imageurl) values('$image_name','$path');";
		
		$result = mysqli_query($connection, $query) ;
		
		if($result){
			echo "success";
		}else{
			echo "failed";
		}
		
		mysqli_close($connection);
	}
 }
?>

