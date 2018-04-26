<?php

require_once("connection.php");
require_once("globalFunctions.php");
require_once("Therapist.php");
require_once("Patient.php");

// These strings are used to populate the select fields
$therapistSelect = generateSelectOptions("select concat(TID, '. ', tLastName, ', ', tFirstName) as name from Therapist", array("name"), $conn);
$patientSelect = "";//generateSelectOptions("select concat(PID, '. ', pLastName, ', ', pFirstName) as name from Patient", array("name"), $conn);
$exerciseSelect = generateSelectOptions("select concat(EID, '. ', bodyPart, ' - ', bandColor, ' - ', numReps) as exercise from Exercise", array("exercise"), $conn);

// This is used to fill the table of appointments for the selected Therapist
$patientSQLSelect = "select pFirstName, pLastName, bodyPart, bandColor, numReps, apptDate, apptTime from Patient, Therapist, Exercise, Appointment where Appointment.TID = Therapist.TID and Appointment.PID = Patient.PID and Appointment.EID = Exercise.EID and Therapist.TID=";

// This is the string that is filled with the table html and data from patientSQLSelect
$apptDisplay = "";

// this is used to set the visibility of the create appointment and the appointment table
$createApptVisibility = "visibility: hidden";
$apptTableVisibility = "visibility: hidden";

// this is the therapist who has been selected. Values are blank when none is selected
$therapist = new Therapist($conn);

// colors for the grid rows
$rowColor0 = "#B7D8B6";
$rowColor1 = "#CCCBC6";

// check if there has been a post
if($_SERVER['REQUEST_METHOD'] == 'POST'){

  // first, check if an appointment was added. Data is validated client-side, so this cannot
  // occur without all fields having appropriate data entered
  if(isset($_POST['apptAdd']) && $_POST['apptAdd'] == "1"){
    // insert the new appointment into the database
    $insertSQL = "insert into Appointment (TID, PID, EID, apptDate, apptTime) values (?, ?, ?, ?, ?)";
    list($TID, $blah) = explode(".", $_POST['selectTherapist']);
    list($PID, $blah1) = explode(".", $_POST['patientSelect']);
    list($EID, $blah2) = explode(".", $_POST['exerciseSelect']);
    $apptDate = $_POST['apptDate'];
    $apptTime = $_POST['apptTime'];
    
    try{
      $stmt = $conn->prepare($insertSQL);
      $stmt->execute(array($TID, $PID, $EID, $apptDate, $apptTime));
    }
    catch(PDOException $e){
      $showAlert($e->getMessage());
    }
    catch(Exception $e){
      $showAlert($e->getMessage());
    }
  }

  if((isset($_POST['tChange']) && $_POST['tChange'] == "1")
      || (isset($_POST['selectTherapist']) && $_POST['selectTherapist'] != "-- Select Therapist --")){
    $createApptVisibility = "";
    $apptTableVisibility = "";
    $therapist->setTherapist($_POST['selectTherapist']);

    // show patients available to this therapist
    $patientSelect = generateSelectOptions("select concat(Patient.PID, '. ', pLastName, ', ', pFirstName) as name from Patient, Therapist, Has where Therapist.TID=Has.TID and Patient.PID=Has.PID and Therapist.TID=".$therapist->TID, array("name"), $conn);

    // appointment list needs to be generated
    $patientSQLSelect .= $therapist->TID;
    try{
      $results = $conn->query($patientSQLSelect);
      $count = 0;
      foreach($results as $row){
        $color = "";
	if($count % 2 == 0) { $color = $rowColor0; }
	else { $color = $rowColor1; }
	$count++;
        $apptDisplay .= "<tr style='background-color: ".$color."'>";
	$apptDisplay .= "<td>".$row['pFirstName']."</td>";
	$apptDisplay .= "<td>".$row['pLastName']."</td>";
	$apptDisplay .= "<td>".$row['bodyPart']."</td>";
	$apptDisplay .= "<td>".$row['bandColor']."</td>";
	$apptDisplay .= "<td>".$row['numReps']."</td>";
	$apptDisplay .= "<td>".$row['apptDate']."</td>";
	$apptDisplay .= "<td>".$row['apptTime']."</td>";
	$apptDisplay .= "</tr>";
      }
    }
    catch(PDOException $e){
      $showAlert($e->getMessage());
    }
    catch(Exception $e){
      $showAlert($e->getMessage());
    }
  }
}

$pageTitle = "Appointments";
include("../html/header.html");
include("../html/therapist_body.html");
include("../html/footer.html");
?>
