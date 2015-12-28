<?php
require_once 'dbconfig.php';

if($user->is_loggedin()!="")
{
 $user->redirect('index.php');
}

if(isset($_POST['submit']))
{
 $email = $_POST['email'];
 $upass = $_POST['heslo'];
  
 if($user->login($email,$upass))
 {
  $user->redirect("index.php?success");
 }
 else
 {
  $error = "Wrong Details !";
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
    <title>Přihlášení KIV/WEB Semestrální práce</title>
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
      		<div>
      		  <!--login error message-->
      		  <?php
      		  if(isset($error))
            {
               
                  ?>
                  <div class="text">
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                  </div>
                  </div>
                  <?php
               
            }
            ?>
      		</div>
            <div class="login">
                <br>
                <form action="" method="post">
                	<label>E-mail*:</label>
                	<input type="email" name="email" tabindex="4" required><br>
                	<br>
                	<label>Heslo*:</label>
               		<input type="password" name="heslo" tabindex="5" required><br>
               		<br>
               		<input type="submit" value="Přihlásit" name ="submit" class="btn btn-default">
               		<a class="btn btn-default" href="registration.php">Registrace</a>
                </form>
            </div>
    
    </div>   
</body>
</html>