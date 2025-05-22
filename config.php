<?php
   $conn = mysqli_connect("localhost", "root", "", "cara");

   if(!$conn){
       die("Connection failed :( ".mysqli_connect_error());
   }
?>
