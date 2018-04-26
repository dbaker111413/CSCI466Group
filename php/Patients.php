 <?php
/*
  Owners: Daniel Baker, Jackie Salim, Tanner Martin, & Kevin Tee
  CSCI 466
  Spring 2018

  Group Project # 8
  Purpose of file: display a form to create and update patients

*/
  require_once("globalFunctions.php");
  require_once("connection.php");
  require_once("Therapist.php");
  require_once("Patient.php");

  $patientSelectDefault = "-- Select Patient --";
  $submitText = "Add";
  $labelText = "Add new Patient";


  $patientSQLSelect = "select * from Patient,Appointment where Appointment.PID =";


  $patient = new Patient($conn);

  // check if there has been a post
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //if the Update/Create button was pressed
    if(isset($_POST['patientUpdateAdd']) && $_POST['patientUpdateAdd'] == "1"){

     // if inserting a new Patient
     if($_POST['isInsert'] == "0"){
      if(isset($_POST['patientSelect'])){
         $insertSQL = "insert into Patient (pFirstName, pLastName) values (?, ?)";
         $pFirstName = $_POST['fName'];
         $pLastName = $_POST['lName'];
       }

       try{
        $stmt = $conn->prepare($insertSQL);
        $stmt->execute(array($pFirstName, $pLastName));
        showAlert("NEW Patient Added: ".$_POST['fName']." ".$_POST['lName']);
       }
       catch(PDOException $e){
         //showAlert($e->getMessage());
         echo $e->getMessage();
       }
       catch(Exception $e){
         //showAlert($e->getMessage());
         echo $e->getMessage();
       }
     }
     //if updating an existing Patient
     else if ($_POST['isInsert'] == "1"){

       if(isset($_POST['patientSelect'])){
         $updateSQL = "update Patient set pFirstName = '".$_POST['fName']."', pLastName = '".$_POST['lName']."' where PID=?";
         list($PID, $blah) = explode(".", $_POST['patientSelect']);
       }

       try{
         $stmt = $conn->prepare($updateSQL);
         $stmt->execute(array($PID));
         showAlert("Patient Updated: ".$_POST['fName']." ".$_POST['lName']);
       }
       catch(PDOException $e){
        //$showAlert($e->getMessage());
        echo $e->getMessage();
       }
       catch(Exception $e){
        //$showAlert($e->getMessage());
        echo $e->getMessage();
       }

     }//end else if

   }//end if Create/update button

    if((isset($_POST['patientSelected']) && $_POST['patientSelected'] == "1") ||
       (isset($_POST['patientSelect']) && $_POST['patientSelect'] != "-- Select Patient --")){

       $patient->setPatient($_POST['patientSelect']);
       $labelText = "Update ".$patient->pFirstName." ".$patient->pLastName;
       $submitText = "Update";

    }

  }

  if(isset($_POST['isDelete']) && $_POST['isDelete'] == "1"){
     $deleteSQL = "delete from Patient where PID=?";
     list($PID, $blah) = explode(".", $_POST['patientSelect']);

     try{
       $stmt = $conn->prepare($deleteSQL);
       $stmt->execute(array($PID));
       showAlert("Patient Deleted: ".$_POST['fName']." ".$_POST['lName']);
     }
     catch(PDOException $e){
      showAlert($e->getMessage());
      //echo $e->getMessage();
     }
     catch(Exception $e){
      showAlert($e->getMessage());
      //echo $e->getMessage();
     }

     $patient = new Patient($conn);
     $labelText = "Add new Patient";
     $submitText = "Add";
  }

  $patientSelect = generateSelectOptions("select concat(PID, '. ', pLastName, ', ', pFirstName) as name from Patient", array("name"), $conn);


  $pageTitle = "Patients";
  include("../html/header.html");
  include("../html/patient_body.html");
  include("../html/footer.html");
?>
