<?php

if($user->is_loggedin() ==""){
    $user->redirect("index.php");
} 
 $user_id = $_SESSION['user_session'];
   $stmt = $DB_con->prepare("SELECT * FROM hainj_user WHERE id=:id");
   $stmt->execute(array(":id"=>$user_id));
   $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
   $stmt =$DB_con->prepare("SELECT * FROM hainj_rights WHERE id=:rightsid");
   $stmt->execute(array(":rightsid"=>$userRow['Rights_id']));
   $rights=$stmt->fetch(PDO::FETCH_ASSOC);
 $error;
if(isset($_POST['changeuser']))
{
    $name = $_POST['jmeno'];
   $surn = $_POST['prijmeni'];
   $email = $_POST['email'];
   $pass = $_POST['heslo']; 
   $pass2 = $_POST['heslo2']; 
   $right;
   if($rights['rights']==10){
        $right = $_POST['prava'];
   }
   else{
        $right=$userRow['Rights_id'];
   }
   $id= $_POST['iduser'];
   if(trim($name)=="") {

       $error = "Není vyplněno jméno";
   }
   else if(trim($surn)=="") {

       $error = "Není vyplněno příjmení";
   }
   else if(trim($email)=="") {

      $error = "Není vyplněn email";
   }
   else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   
      $error = "Zadejte platnou emailovou adresu";
   }
   else if($pass=="") {
    
      $error = "Heslo je prázdné";
   }
   else if($pass != $pass2){
        $error = "Hesla se neshodují";
   }
   else
   {
      
      try
      {
         $stmt = $DB_con->prepare("SELECT id, email FROM hainj_user WHERE email=:umail");
         $stmt->execute(array(':umail'=>$email));
         $row=$stmt->fetch(PDO::FETCH_ASSOC);
    
         if($row['email']==$email ) {
          if($id != $row['id']){

              $error = "Email již někdo používá";
            } else
         {
            if($user->update($name,$surn, $email,$pass,$right ,$id)) 
            {
               
              if ($rights['rights'] == 10) {
               $user->redirect("editprofile.php?updated");
              }else{
               $user->redirect("profile.php?updated");
                }
            }
         }
         }
         else
         {
            if($user->update($name,$surn, $email, $pass, $right, $id)) 
            {
             
                if ($rights['rights'] == 10) {
                $user->redirect("editprofile.php?updated");
              }else{
                $user->redirect("profile.php?updated");
                }
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



<?php 
$user_id = $_SESSION['user_session'];
$stmt = $DB_con->prepare("SELECT * FROM hainj_user WHERE id=:id");
$stmt->execute(array(":id"=>$user_id));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
$stmt =$DB_con->prepare("SELECT * FROM hainj_rights WHERE id=:rightsid");
$stmt->execute(array(":rightsid"=>$userRow['Rights_id']));
$rights=$stmt->fetch(PDO::FETCH_ASSOC);
if ($rights['rights'] == 10) {
    if (isset($_POST['edit'])) {
        $useridd = $_POST['edit'];
        $stmt=$DB_con->prepare("SELECT * FROM hainj_user WHERE id=$useridd");
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<div class="container" style="width: 50%; padding-top: 25px;">
    <?php
if(isset($error)){
?>
    <div class="text">
        <div class="alert alert-danger">
            <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
        </div>
    </div>
<?php
}else if(isset($_GET['updated'])){
?>
                  <div class="text">
                  <div class="alert alert-success">
                      <i class="glyphicon glyphicon-ok"></i> &nbsp; <?php echo "Změna osobních údajů proběhla úspěšně"; ?>
                  </div>
                  </div>
                  <?php
}
?>




      <?php 
        if ($rights['rights']==10) {
        ?>
            <form class="form-horizontal" style="width: 50%; margin:0 auto;" method="POST" action="#">
             <div class="form-group">
           
           
                <label class="col-lg-3 col-sm-3">Uživatel</label>
                <select name="edit" style="width: 50%; display: inline;" class="form-control" onchange="this.form.submit()">
                    <?php
                        $prep = $DB_con->prepare("SELECT * FROM hainj_user");
                        $prep->execute();
                        $allusers = $prep->fetchAll();
                        foreach ($allusers as $row) {

                            $uname = "$row[name]"." "."$row[surname]";
                            if(isset($_POST['edit'])) {
                                 $useridd = $_POST['edit'];
                                 if ($useridd==$row['id']) {
                                      echo "<option selected value=\"$row[id]\">$uname</option>";
                                 }else{
                                     echo "<option value=\"$row[id]\">$uname</option>";
                                 }
                             }else{
                                echo "<option value=\"$row[id]\">$uname</option>";
                            }
                        }

                    ?>
                </select>
                </div>
            </form>
        <?php
        }
     ?>

     <?php
     $rightprep = $DB_con->prepare("SELECT * FROM hainj_rights WHERE id=$userRow[Rights_id]");
     $rightprep->execute();
     $rightsuser = $rightprep->fetch(PDO::FETCH_ASSOC);
     ?>
    <form class="form-horizontal" style="width: 50%; margin:0 auto;" method="POST" action="#">
        <?php echo"<input type=\"hidden\" name=\"iduser\" value=\"$userRow[id]\">";?>

        <div class="form-group">
                     
            <label class="col-lg-3 col-sm-3">Jméno</label>
            <input class="form-control" style="width: 50%; display: inline;" type="text" name="jmeno" value="<?php echo $userRow['name']?>" tabindex="2" required>
        </div>
        <div class="form-group">
            <label class="col-lg-3 col-sm-3">Příjmení</label>
            <input class="form-control" style="width: 50%; display: inline;" type="text" value="<?php echo $userRow['surname']?>" name="prijmeni" tabindex="3" required>
        </div>
        <div class="form-group">
            <label class="col-lg-3 col-sm-3">Email</label>
            <input class="form-control" style="width: 50%; display: inline;" type="email" value="<?php echo $userRow['email']?>" name="email" tabindex="4" required>
        </div>
        <div class="form-group">
            <label class="col-lg-3 col-sm-3">Heslo</label>
            <input class="form-control" style="width: 50%; display: inline;" type="password" name="heslo" tabindex="1" required>
        </div>
        <div class="form-group">
            <label class="col-lg-3 col-sm-3">Heslo znovu</label>
            <input class="form-control" style="width: 50%; display: inline;" type="password" name="heslo2" tabindex="1" required>
        </div>
        <?php if ($rights['rights'] == 10) {
        ?> 
        <div class="form-group">
            <label class="col-lg-3 col-sm-3">Práva</label>
            <select class="form-control" style="width: 50%; display: inline;" id="sel1" name="prava">
            <?php

                if ($rightsuser['rights'] == 1) {
                  echo"<option value=\"1\" selected>Uživatel</option>";
               }
               else{
                echo"<option value=\"1\">Uživatel</option>";
               }
                if ($rightsuser['rights'] == 5) {
                  echo"<option value=\"2\" selected>Recenzent</option>";
               }
               else{
                echo"<option value=\"2\">Recenzent</option>";
               }
                if ($rightsuser['rights'] == 10) {
                  echo"<option value=\"3\" selected>Admin</option>";
               }
               else{
                echo"<option value=\"3\">Admin</option>";
               }
            ?>
             </select>
        </div><?php
        }?>
        <button type="submit" name="changeuser" class="btn btn-default">Submit</button>
    </form>   
</div>