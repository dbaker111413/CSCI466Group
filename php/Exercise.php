<?php
/*
  Owners: Daniel Baker, Jackie Salim, Tanner Martin, & Kevin Tee
  CSCI 466
  Spring 2018

  Group Project # 8
  Purpose of file: Contain object data for a therapist

*/

  require_once("globalFunctions.php");

  class Patient {
    public $PID = 0;
    public $pFirstName = "";
    public $pLastName = "";

    private $conn;

    // constructor takes a PDOConnection object
    function __construct($conn){
      $this->conn = $conn;
    }

    // uses the first and last name to get the entire patient column data.
    // expects the name to be in "LastName, FirstName" format
    public function setPatient($lNameCommafName){
      // divide the input string
      list($ID, $Name) = explode('.', $lNameCommafName);

      // create a sql select statement to query the data
      $selectSQL = "select * from Patient where PID = ".$ID;

      // now wrap the query in a try catch block
      try{
        $dataArray = $this->conn->query($selectSQL);
        // make sure there is only one row in the array
        if(count($dataArray) != 1){
          showAlert("An invalid number of rows were returned, bad query!");
        }
        else{
          foreach ($dataArray as $data){
            $this->PID = $data['PID'];
            $this->pFirstName = $data['pFirstName'];
            $this->pLastName = $data['pLastName'];
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

