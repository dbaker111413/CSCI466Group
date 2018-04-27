<?php
/*
  Owners: Daniel Baker, Jackie Salim, Tanner Martin, & Kevin Tee
  CSCI 466
  Spring 2018

  Group Project # 8
  Purpose of file: Contain object data for an exercise.

*/

  require_once("globalFunctions.php");

  class Exercise {
    public $eID = 0;
    public $bodyPart = "";
    public $bandColor = "";
    public $numReps = 0;

    private $conn;

    // constructor takes a PDOConnection object
    function __construct($conn){
      $this->conn = $conn;
    }

    // Fetch exercise by EID
    public function setExercise($eID) {
      // create a sql select statement to query the data
      $query = "select * from Exercise where EID = ".$eID;

      // now wrap the query in a try catch block
      try {
        $queryResult = $this->conn->query($query);
        // make sure there is only one row in the array
        if(count($queryResult) != 1){
          showAlert("An invalid number of rows were returned, bad query!");
        }
        else {
          foreach ($queryResult as $row){
            $this->eID = $row['EID'];
            $this->bodyPart = $row['bodyPart'];
            $this->bandColor = $row['bandColor'];
            $this->numReps = $row['numReps'];
          }
        }
      }
      catch (PDOException $e){
        showAlert($e->getMessage());
      }
      catch(Exception $e){
        showAlert($e->getMessage());
      }
    }
  }
?>

