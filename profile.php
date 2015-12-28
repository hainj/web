<?php
require_once 'dbconfig.php';
if($user->is_loggedin() ==""){
	$user->redirect("index.php");
} 
$user_id = $_SESSION['user_session'];
$DB_con->prepare("SELECT * FROM user where id=$user_id");
$stmt = $DB_con->prepare("SELECT * FROM user WHERE id=:id");
$stmt->execute(array(":id"=>$user_id));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
$stmt =$DB_con->prepare("SELECT * FROM rights WHERE id=:rightsid");
$stmt->execute(array(":rightsid"=>$userRow['Rights_id']));
$rights=$stmt->fetch(PDO::FETCH_ASSOC);
$stmt =$DB_con->prepare("SELECT * FROM Post WHERE id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));
$stmt->fetch(PDO::FETCH_ASSOC);
$rowcount = $stmt->rowcount();
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
    <title>Registrace KIV/WEB Semestrální práce</title>
</head>
<body>
    <div class="text-center">
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
              if(isset($error))
            {
               foreach ($error as $error) {
                   # code...
               
                  ?>
                  <div class="text">
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                  </div>
                  </div>
                  <?php
               }
            }
            ?>
        <div class="center" style="width: 30%; padding-top: 25px;">
              <ul style="padding: 0px;">
            <li class="list-group-item text-left">Profil</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Jméno</strong></span> <?php echo "$userRow[name]"?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Příjmení</strong></span> <?php echo "$userRow[surname]"?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Email</strong></span> <?php echo "$userRow[email]"?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Počet příspěvků</strong></span> <?php echo "$rowcount"?></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Práva</strong></span> <?php echo "$rights[name]"?></li>
          </ul> 
          <a href="editprofile.php" class="btn btn-default"role="button">Upravit</a>
		</div>
    </div>   
</body>
</html>
















