<?php

$host="students";
$user="cs566208";
$password="d2LIJgGv59";
$db="cs566208";
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);

try{
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
  echo 'ERROR: '.$e->getMessage();
}

?>