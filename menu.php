
<?php if($user->is_loggedin() == "") {
	?>
	<a class="btn btn-default" href="index.php" role="button">Home</a>
	<a class="btn btn-default" href="registration.php" role="button">Registrace</a>
	<a class="btn btn-default" href="login.php" role="button">Přihlásit</a>
	<?php
	}
	elseif ($user->is_loggedin() != "") { 
		$stmt =$DB_con->prepare("SELECT * FROM hainj_rights WHERE id=:rightsid");
		$stmt->execute(array(":rightsid"=>$userRow['Rights_id']));
		$rights=$stmt->fetch(PDO::FETCH_ASSOC);?>
		<a class="btn btn-default" href="posts.php" role="button">Příspěvky</a>
		<a class="btn btn-default" href="addpost.php" role="button">Přidat příspěvek</a>
		<?php if ($rights['rights'] == 5) {?>
			<a class="btn btn-default" href="review.php" role="button">Recenzovat</a>
		<?php
		}?>
		<?php if ($rights['rights'] == 10) {?>
			<a class="btn btn-default" href="removerev.php" role="button">Smazat recenze</a>
		<?php
		}?>
	
		<a class="btn btn-default" href="profile.php" role="button">Profil</a>
		<a class="btn btn-default" href="index.php?logout=true" role="button">Odhlásit</a>
	<?php
 	
	/*admin*/
	if ($rights['rights'] == 10) {?>
		<a class="btn btn-default" href="editprofile.php" role="button">Upravit profily</a>
		<?php
		}

	} 
?>

