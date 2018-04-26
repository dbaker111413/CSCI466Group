<?php
/*
  Owners: Daniel Baker, Jackie Salim, Tanner Martin, & Kevin Tee
  CSCI 466
  Spring 2018

  Group Project # 8
  Purpose of file: Contain object data for a therapist

*/

  require_once("globalFunctions.php");
  
  class Therapist {
    public $TID = 0;
    public $tFirstName = "";
    public $tLastName = "";
    public $numPatients = 0;
    public $phone = "";

    private $conn;

    // constructor takes a PDOConnection object
    function __construct($conn){
      $this->conn = $conn;
    }

    // uses the first and last name to get the entire therapist column data.
    // expects the name to be in "ID. LastName, FirstName" format
    public function setTherapist($lNameCommafName){
      // divide the input string
      list($ID, $Name) = explode('.', $lNameCommafName);

      // create a sql select statement to query the data
      $selectSQL = "select * from Therapist where TID=".$ID;

      // now wrap the query in a try catch block
      try{
        $dataArray = $this->conn->query($selectSQL);
	// make sure there is only one row in the array
	if(count($dataArray) != 1){
          showAlert("An invalid number of rows were returned, bad query!");
        }
	else{
          foreach ($dataArray as $data){
	    $this->TID = $data['TID'];
	    $this->tFirstName = $data['tFirstName'];
	    $this->tLastName = $data['tLastName'];
	    $this->numPatients = $data['numPatients'];
	    $this->phone = $data['phone'];
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

    // updates the therapist object using the ID
    public function updateDatabase(){
      $updateSQL = "update Therapist set tFirstName=?, tLastName=?, numPatients=?, phone=? where TID=?";

      // wrap update in a try-catch block
      try{
        $stmt = $this->conn->prepare($updateSQL);
        $stmt->execute(array($this->tFirstName, $this->tLastName, $this->numPatients, $this->phone, $this->TID));
      }
      catch (PDOException $e){
        showAlert($e->getMessage());
      }
      catch(Exception $e){
        showAlert($e->getMessage());
      }
    }

    // inserts a new Therapist into the database
    public function addToDatabase(){
      $insertSQL = "insert into Therapist (tFirstName,tLastName,numPatients,phone) values(?,?,?,?)";

      // wrap insert in a try-catch block
      try{
        $stmt = $this->conn->prepare($insertSQL);
        $stmt->execute(array($this->tFirstName, $this->tLastName, $this->numPatients, $this->phone));
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