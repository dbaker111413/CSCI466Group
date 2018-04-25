<?php
/*
  Owners: Daniel Baker, Jackie Salim, Tanner Martin, & Kevin Tee
  CSCI 466
  Spring 2018

  Group Project # 8
  Purpose of file: Contain object data for an appointment

*/

  require_once("globalFunctions.php");

  class Appointment {
    public $AID = 0;
    public $TID = 0;
    public $PID = 0;
    public apptDate = "";
    public apptTime = "";

    private $conn;

    // constructor takes a PDOConnection object
    function __construct($conn){
      $this->conn = $conn;
    }

  }
?>

