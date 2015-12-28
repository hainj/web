<a class="btn btn-default" href="index.php" role="button">Home</a>
<?php if($user->is_loggedin() == "") {
	?>
	<a class="btn btn-default" href="registration.php" role="button">Registrace</a>
	<a class="btn btn-default" href="login.php" role="button">Přihlásit</a>
	<?php
	}
	elseif ($user->is_loggedin() != "") { ?>
		<a class="btn btn-default" href="posts.php" role="button">Příspěvky</a>
		<a class="btn btn-default" href="addpost.php" role="button">Přidat příspěvek</a>
		<a class="btn btn-default" href="profile.php" role="button">Profil</a>
		<a class="btn btn-default" href="index.php?logout=true" role="button">Odhlásit</a>
	<?php
 	$stmt =$DB_con->prepare("SELECT * FROM rights WHERE id=:rightsid");
	$stmt->execute(array(":rightsid"=>$userRow['Rights_id']));
	$rights=$stmt->fetch(PDO::FETCH_ASSOC);
	/*admin*/
	if ($rights['rights'] == 10) {?>
		<a class="btn btn-default" href="editprofile.php" role="button">Upravit profil</a>
		<?php
		}

	} 
?>

