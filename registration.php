<?php
require_once 'dbconfig.php';

if($user->is_loggedin()!="")
{
    $user->redirect('index.php');
}

if(isset($_POST['submit']))
{
   $name = $_POST['jmeno'];
   $surn = trim($_POST['prijmeni']);
   $email = trim($_POST['email']);
   $pass = $_POST['heslo']; 
   $pass2 = $_POST['heslo2']; 
   if(trim($name)=="") {
      $error[] = "provide name !"; 
   }
   else if(trim($surn)=="") {
      $error[] = "provide surname !"; 
   }
   else if(trim($email)=="") {
      $error[] = "provide email id !"; 
   }
   else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error[] = 'Please enter a valid email address !';
   }
   else if($pass=="") {
      $error[] = "provide password !";
   }
   else if($pass != $pass2){
        $error[] = "passwords do no match!";
   }
   else
   {
      try
      {
         $stmt = $DB_con->prepare("SELECT email FROM user WHERE email=:umail");
         $stmt->execute(array(':umail'=>$email));
         $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
         if($row['email']==$email) {
            $error[] = "sorry email already taken !";
         }
         else
         {
            if($user->register($name,$surn, $email,$pass)) 
            {
                $user->redirect('index.php?register');
            }
         }
     }
     catch(PDOException $e)
     {
        echo $e->getMessage();
     }
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
        <div class="center">
            <div class="registration">
                <br>
                <form  method ="post">
                <label>Jméno*:</label>
                <input type="text" name="jmeno" value="Jméno" tabindex="2" required><br>
                <br>
                 <label>Příjmení*:</label>
                <input type="text" name="prijmeni" tabindex="3" required><br>
                <br>
                <label>E-mail*:</label>
                <input type="email" name="email" tabindex="4" required><br>
                <br>
                 <label>Heslo*:</label>
                <input type="password" name="heslo" tabindex="5" required><br>
                <br>
                <label>Heslo znovu*:</label>
                <input type="password" name="heslo2" tabindex="1" required><br><br>
                <input type="submit" value="Registrovat" name ="submit" class="btn btn-default"></input>
                </form>
            </div>
        </div>
    </div>   
</body>
</html>