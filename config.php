<?php
   $conn = mysqli_connect("localhost", "root", "", "cart-system");

   if(!$conn){
       die("Connection failed :( ".mysqli_connect_error());
   }
?>