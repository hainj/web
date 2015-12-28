<?php
require_once 'dbconfig.php';
$user_id;
$userRow;
$posts;
if ($user->is_loggedin()!="") {
  $user_id = $_SESSION['user_session'];
  $stmt = $DB_con->prepare("SELECT * FROM user WHERE id=:id");
  $stmt->execute(array(":id"=>$user_id));
  $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

  $stmt = $DB_con->prepare("SELECT * FROM Post");
  $stmt->execute();
  $posts=$stmt->fetchAll();
}else{
    $user->redirect("index.php");
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
         </div>
            <?php
          
            include("posttable.php");
            ?>
            
            
       
    </body>
</html>