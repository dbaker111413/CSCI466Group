<?php

require_once("connection.php");
require_once("globalFunctions.php");
require_once("Therapist.php");
require_once("Patient.php");

// therapist object for managing therapist data
$therapist = new Therapist($conn);

// strings for the therapist select box
$therapistSelectDefault = "-- Select Therapist --";

// strings for notifying the user whether this is an update or create
$labelText = "Insert New Therapist:";
$submitText = "Create";

// used to fill the html patient table
$patientTable = "";
$count = 0;

// get the count of available patients
$pCount = 0;
$countSQL = "select count(*) as count from Patient";
try{
  foreach($conn->query($countSQL) as $row){
    $pCount = $row['count'];
  }
}
catch(PDOException $e){ showAlert($e->getMessage()); }
catch(Exception $e){ showAlert($e->getMessage()); }


// handle a post
if($_SERVER['REQUEST_METHOD'] == "POST"){

  // create an array of PIDs checked by the user
  $newCounter = 0;
  $PIDs = array();
  while($newCounter < $pCount){
    if(isset($_POST['check'.$newCounter])) { array_push($PIDs, $_POST['id'.$newCounter]);}
    $newCounter++;
  }

  // check if user wants to delete the therapist
  if(isset($_POST['therapistDelete']) && $_POST['therapistDelete'] == "1"){
    // therapist must be selected for this even to occur
    $therapist->setTherapist($_POST['therapistSelect']);

    // to delete this therapist, all "Has" rows must be deleted first
    // as well as any appointments
    $hasDeleteSQL = "delete from Has where TID=?";
    $apptDeleteSQL = "delete from Appointment where TID=?";
    $deleteSQL = "delete from Therapist where TID=?";
    try{
      // first, delete the 'Has' relationships
      $hasDelete = $conn->prepare($hasDeleteSQL);
      $hasDelete->execute(array($therapist->TID));

      // next, delete the appointments
      $apptDelete = $conn->prepare($apptDeleteSQL);
      $apptDelete->execute(array($therapist->TID));

      // finally, delete the therapist
      $stmt = $conn->prepare($deleteSQL);
      $stmt->execute(array($therapist->TID));
      showAlert("Therapist Deleted");
      $therapist = new Therapist($conn);
    }
    catch(PDOException $e) { showAlert($e->getMessage()); }
    catch(Exception $e) { showAlert($e->getMessage()); }
  }
  
  // check if the user wants to insert new or update existing
  if(isset($_POST['therapistSelected']) && $_POST['therapistSelected'] == "1"){
    // this is an update instead of an insert
    $labelText = "Update Existing Therapist:";
    $submitText = "Update";

    // get the therapist ID from the select post value
    $therapist->setTherapist($_POST['therapistSelect']);

  }

  // check if the user clicked submit
  if(isset($_POST['therapistUpdateAdd']) && $_POST['therapistUpdateAdd'] == "1"){
    // determine if this is an update or an insert
    if($_POST['therapistSelected'] == "1"){
      // update
      $therapist->setTherapist($_POST['therapistSelect']);
      $therapist->tFirstName = $_POST['fName'];
      $therapist->tLastName = $_POST['lName'];
      $therapist->phone = $_POST['phone'];
      $therapist->numPatients = count($PIDs);

      // before updating the database, the 'Has' relationships must be updated
      $deleteSQL = "delete from Has where TID=?";
      $insertSQL = "insert into Has (TID, PID) values (?, ?)";
      try{
        // first delete the old relationships
        $deleteStmt = $conn->prepare($deleteSQL);
	$deleteStmt->execute(array($therapist->TID));

        // now, insert the new relationships
	foreach($PIDs as $PID){
          $insertStmt = $conn->prepare($insertSQL);
	  $insertStmt->execute(array($therapist->TID, $PID));
        }
      }
      catch(PDOException $e){
        showAlert("Error Occurred while trying to update therapist/patient status\n".$e->getMessage());
      }

      $therapist->updateDatabase();
      showAlert("Therapist Updated: ".$therapist->tFirstName." ".$therapist->tLastName);
    }
    else{
      // insert
      $therapist->tFirstName = $_POST['fName'];
      $therapist->tLastName = $_POST['lName'];
      $therapist->phone = $_POST['phone'];
      $therapist->numPatients = count($PIDs);      
      $therapist->addToDatabase();

      // now create the therapist/patient relationships
      $insertSQL = "insert into Has (TID, PID) values((select max(TID) from Therapist), ?)";
      try{
      	foreach($PIDs as $PID){
          $stmt = $conn->prepare($insertSQL);
	  $stmt->execute(array($PID));
      	} 
      }
      catch(PDOException $e){
        showAlert("Error Occurred while trying to update therapist/patient status\n".$e->getMessage());
      }
      
      showAlert("Therapist Added: ".$therapist->tFirstName." ".$therapist->tLastName);
      $therapist = new Therapist($conn); // clear the output
    }
  }
}

// check if therapist has been selected. If so, determine which patients it is connected to.
$currentPatients = array();
if($therapist->TID != 0){
  $tSelectSQL = "select PID from Has where TID=".$therapist->TID;
  try{
    foreach($conn->query($tSelectSQL) as $row){
      array_push($currentPatients, $row['PID']);
    }
  }
  catch(PDOException $e){}
  catch(Exception $e){}
}

$rowColor0 = "#EBB8C1";
$rowColor1 = "#EBA8B1";
// query the patients and fill the table
$patientSQL = "select PID, concat(pLastName, ', ', pFirstName) as name from Patient";
try{
  foreach ($conn->query($patientSQL) as $row){
    $color = "";
    $checked = "";
    if($count % 2 == 0) { $color = $rowColor0; }
    else { $color = $rowColor1; }

    // check if the current PID is already connected to the selected therapist (if applicable)
    if(in_array($row['PID'], $currentPatients)){
      $checked = "checked";
    }
    
    $patientTable .= "<tr style='background-color: ".$color."'>";
    $patientTable .= "<td><input type='checkbox' name='check".$count."' id='check".$count."' ".$checked."></input></td>";
    $patientTable .= "<td><label>".$row['name']."</label>";
    $patientTable .= "<input type='hidden' name='id".$count."' id='id".$count."' value='".$row['PID']."'></input></td>";
    $patientTable .= "</tr>";
    $count++;
  } 
}
catch(PDOException $e){}
catch(Exception $e){}


// the select options for the therapist
$therapistSelect = generateSelectOptions("select concat(TID, '. ', tLastName, ', ', tFirstName) as therapist from Therapist", array("therapist"), $conn);

$pageTitle = "Therapists";
include("../html/header.html");
include("../html/create_therapist_body.html");
include("../html/footer.html");
?>
