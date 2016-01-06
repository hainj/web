<?php 
require_once 'dbconfig.php';
if ($user->is_loggedin()=="") {
	$user->redirect("index.php?notlog");
}
if(isset($_POST['submit']))
{
   $name = $_POST['jmeno'];
   $surn = $_POST['prijmeni'];
   $email = $_POST['email'];
   $pass = $_POST['heslo']; 
   $pass2 = $_POST['heslo2']; 
   $rights = $_POST['prava'];
   $id= $_SESSION['user_session'];

    $user_id = $_SESSION['user_session'];
  	$stmt = $DB_con->prepare("SELECT * FROM hainj_user WHERE id=:id");
  	$stmt->execute(array(":id"=>$user_id));
  	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
  	$stmt =$DB_con->prepare("SELECT * FROM hainj_rights WHERE id=:rightsid");
	$stmt->execute(array(":rightsid"=>$userRow['Rights_id']));
	$rights=$stmt->fetch(PDO::FETCH_ASSOC);
  if(trim($name)=="") {

       $user->redirect('editprofile.php?error=Provide name!');
   }
   else if(trim($surn)=="") {

       $user->redirect('editprofile.php?error=Provide surname!');
   }
   else if(trim($email)=="") {

      $user->redirect('editprofile.php?error=Provide email!');
   }
   else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   
      $user->redirect('editprofile.php?error=Please enter a valid email address!');
   }
   else if($pass=="") {
   	
      $user->redirect('editprofile.php?error=provide password!');
   }
   else if($pass != $pass2){
        $user->redirect('editprofile.php?error=passwords do no match!');
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

            	$user->redirect('editprofile.php?error=Email already in use');
            } else
         {
            if($user->update($name,$surn, $email,$pass,$rights ,$id)) 
            {
            	if ($rights['rights']==10) {
            		$user->redirect('editprofile.php?updated');
            	}else{
                $user->redirect('profile.php?updated');
                }
            }
         }
         }
         else
         {
            if($user->update($name,$surn, $email, $pass, $rights, $id)) 
            {
            	
                if ($rights['rights']==10) {
            		$user->redirect('editprofile.php?updated');
            	}else{
                $user->redirect('profile.php?updated');
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