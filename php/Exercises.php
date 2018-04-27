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
  require_once("Exercise.php");

  $exerciseSelectDefault = "-- Select Exercise --";
  $submitText = "Add";
  $labelText = "Add New Exercise";


  $query = "SELECT * FROM Exercise WHERE Exercise.EID =";


  $exercise = new Exercise($conn);

  // check if there has been a post
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['exerciseUpdateAdd']) && $_POST['exerciseUpdateAdd'] == "1"){
     // insert the new exercise into the database

     if($_POST['isInsert'] == "0"){
      if(isset($_POST['exerciseSelect'])){
         $insert = "INSERT INTO Exercise (bodyPart, bandColor, numReps) values (?, ?, ?)";
         $bodyPart = $_POST['bodyPart'];
         $bandColor = $_POST['bandColor'];
         $numReps = $_POST['numReps'];
       }

       try {
        $stmt = $conn->prepare($insert);
        $stmt->execute(array($bodyPart, $bandColor, numReps));
        showAlert("NEW Exercise working " . $_POST['bodyPart'] . " with " . $_POST['bandColor'] . " band for " . $_POST['numReps'] . " reps added.");
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
     else if ($_POST['isInsert'] == "1"){

       if(isset($_POST['exerciseSelect'])){
         $update = "UPDATE Exercise SET bodyPart = '" . $_POST['bodyPart'] . "', bandColor = '" . $_POST['bandColor'] . "', numReps = '" . $_POST['numReps'] . "' WHERE EID = ?";
         $EID = $_POST['exerciseSelect'];
       }

       try {
         $stmt = $conn->prepare($update);
         $stmt->execute(array($EID));
         showAlert("Updated exercise working: " . $_POST['bodyPart'] . " with " . $_POST['bandColor'] . " band for " . $_POST['numReps'] . " reps added.");;
       }
       catch(PDOException $e){
        //$showAlert($e->getMessage());
        echo $e->getMessage();
       }
       catch(Exception $e){
        //$showAlert($e->getMessage());
        echo $e->getMessage();
       }

     }

  }


    if((isset($_POST['exerciseSelected']) && $_POST['exerciseSelected'] == "1") ||
       (isset($_POST['exerciseSelect']) && $_POST['exerciseSelect'] != "-- Select Exercise --")){

      $exercise->setExercise($_POST['exerciseSelect']);
      $labelText = "Update exercise working: " . $_POST['bodyPart'] . " with " . $_POST['bandColor'] . " band for " . $_POST['numReps'] . " reps";
      $submitText = "Update";

    }

  }

  $exerciseSelect = generateSelectOptions("SELECT EID, bodyPart, bandColor, numReps FROM Exercise AS exercise",array("exercise"), $conn);


  $pageTitle = "Exercises";
  include("../html/header.html");
  include("../html/exercise_body.php");
  include("../html/footer.html");
?>
