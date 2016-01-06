<?php
require_once 'dbconfig.php';
$user_id;
$userRow;
$rights;
if ($user->is_loggedin()!="") {
  if (isset($_GET['logout'])){
    $user->logout();
  }
  else{
  $user_id = $_SESSION['user_session'];
  $stmt = $DB_con->prepare("SELECT * FROM hainj_user WHERE id=:id");
  $stmt->execute(array(":id"=>$user_id));
  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
  $stmt =$DB_con->prepare("SELECT * FROM hainj_rights WHERE id=:rightsid");
	$stmt->execute(array(":rightsid"=>$userRow['Rights_id']));
	$rights=$stmt->fetch(PDO::FETCH_ASSOC);
  }
}
?>
<!doctype HTML>
<html>
    <head>
        <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
     <link rel="stylesheet" href="style.css">
     
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <title>Homepage KIV/WEB Semestrální práce</title>
    </head>
    <body>
        <div class="text-center">
           <?php
            include("header.php");

            ?>
         
        <div> 
       <?php
            include("menu.php");
            ?>
           
        </div>
          <?php
              
          if(isset($_GET['register']))
            {
              ?>
              <div class="text">
                <div class="alert alert-success">
                  <i class="glyphicon glyphicon-ok"></i>&nbsp; Registrace proběhla úspěšně
                </div>
              </div>
            <?php
            }else if(isset($_GET['success'])&& isset($_SESSION['user_session']))
            {
                ?>
              <div class="text">
                <div class="alert alert-success">
                  <i class="glyphicon glyphicon-ok"></i>&nbsp; Vítejte uživateli <?php echo "$userRow[name] $userRow[surname] s právy $rights[name]" ?>
                </div>
              </div>
              

            <?php
          }else if(isset($_GET['logout']))
            {
                ?>
              <div class="text">
                <div class="alert alert-success">
                  <i class="glyphicon glyphicon-ok"></i>&nbsp; Ohlášen úspěšně
                </div>
              </div>
              

            <?php
          }else if(isset($_GET['timeout']))
            {
                ?>
              <div class="text">
                <div class="alert alert-success">
                  <i class="glyphicon glyphicon-ok"></i>&nbsp; Odhlášen z důvodu neaktivity po více než 30 minut
                </div>
              </div>
              

            <?php
          }
            include("home.php");
            ?>
            
            
        </div>
    </body>
</html>