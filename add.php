<?php 
require_once 'dbconfig.php';
if ($user->is_loggedin()=="") {
	$user->redirect("index.php?notlog");
}
if(isset($_POST['submit']))
{
	$user_id = $_SESSION['user_session'];
	$DB_con->prepare("SELECT * FROM user where id=$user_id");
	$stmt = $DB_con->prepare("SELECT * FROM user WHERE id=:id");
	$stmt->execute(array(":id"=>$user_id));
	$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
	$login = $userRow['name'].$userRow['surname'];
    $name = $_POST['nazev'];
    $author = $_POST['autor'];
    $abstract = $_POST['abstrakt'];
	$allowedExts = array("pdf");
	$temp = explode(".", $_FILES["soubor"]["name"]);
	$extension = end($temp);
	$upload = false;

    if(trim($name) ==""){
    	$user->redirect('addpost.php?error=Není vyplněno jméno článku');
    } else if(trim($author)==""){
		$user->redirect('addpost.php?error=Není vyplněno jméno autor článku');
    } else if(trim($abstract)==""){
		$user->redirect('addpost.php?error=Není vyplněn abstrakt článku');
    } 
    if(!file_exists("upload")){
    	mkdir("upload/");
    }
    $stmt = $DB_con->prepare("SELECT * FROM Post WHERE name=:uname");
    $stmt->execute(array(':uname'=>$name));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['name']==$name) {
   		$user->redirect('addpost.php?error=Existuje článek se stejným názvem');
    }

    if (!file_exists("upload/$login/")) {
			mkdir("upload/$login/");
	}
	if (($_FILES["soubor"]["type"] == "application/pdf") && ($_FILES["soubor"]["size"] < 2000000)){
		
			
			if ($_FILES["soubor"]["error"] > 0){
				$text = $_FILES["soubor"]["error"];
				$user->redirect('addpost.php?error=Chyba: $text');
			}
			
			else{
				//test nazvu souboru
				if (file_exists("upload/" . $_FILES["soubor"]["name"])){
					
					$user->redirect('addpost.php?error=Pdf s tímto názvem již existuje');
				}
			
				//zmeni nazev souboru a ulozi
				else{
					move_uploaded_file($_FILES["soubor"]["tmp_name"],
					"upload/$login/$name"."_" . $_FILES["soubor"]["name"]);
					$upload = true;

				}
			}
	}
    else{
		$user->redirect('addpost.php?error=Pouze pdf');
    }
    if($upload == true){
	$pdf = "upload/$login/$name"."_" . $_FILES["soubor"]["name"]; 
	$stmt = $DB_con->prepare("INSERT INTO post (id,name,author,abstract,pdf,User_id) 
		VALUES (NULL, :uname, :uauthor, :uabstract, :updf, :uuser_id)");
	$stmt->bindparam(":uname", $name);
    $stmt->bindparam(":uauthor", $author);
    $stmt->bindparam(":uabstract", $abstract);
    $stmt->bindparam(":updf", $pdf);
    $stmt->bindparam(":uuser_id", $user_id);  
	$stmt->execute();
	$user->redirect("addpost.php?success");
	}

}
?>