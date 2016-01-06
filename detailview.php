<?php
if($user->is_loggedin() ==""){
	$user->redirect("index.php");
} 
$row;
if (isset($_GET['post'])) {
	$id = $_GET['id'];

	$stmt = $DB_con->prepare("SELECT * FROM hainj_post WHERE id=:id");
   $stmt->execute(array(":id"=>$id));
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   if($rights['rights']!=10){
   		if ($row['User_id']!=$_SESSION['user_session']) {
   			$user->redirect("index.php");
   		}
   }
}else{
	$user->redirect("index.php");
}
?>


<div class="container" style="width: 40%; text-align:left;padding-top: 20px;">
	<div class="form-group" style="width 40%";>
	<label class"col-lg-3 col-sm-3">Název:    </label>
	<?php echo "$row[name]"; ?>
	</div>
	<div class="form-group" style="width 40%;">
	<label>Autor:    </label>
	<?php echo "$row[author]"; ?>
	</div>
	<div class="form-group" style="width 40%;">
	<label>Abstrakt:    </label>
	<?php echo "$row[abstract]"; ?>
	</div>
	<div class="form-group" style="width 40%;">
	<label>Download:    </label>
	<?php echo "<a href=\"$row[pdf]\">Stáhnout</a>";?>
	</div>
	<div class="form-group" style="width 40%;">
	<label>Schváleno:    </label>
	<?php if ($row['approve']==1) {
			echo "Schváleno";
		}else{
			echo "Neschváleno";
		}?>
	</div>
	<div class="form-group" style="width 40%;">

	<?php 
		$prep = $DB_con->prepare("SELECT * FROM hainj_review WHERE Post_id=$row[id]");
		$prep->execute();
		$review = $prep->fetchAll();


	?>	
	<label>Recenze:    </label>
	<?php 
		if ($prep->rowCount() ==0) {
			echo "Nejsou dostupné žádné recenze;";
		}else{
	 ?>
	<table class="table table-striped table-condensed">
		<thead>
		<tr>
			<th>Recenzent</th>
			<th>Téma</th>
			<th>Technologie</th>
			<th>Originalita</th>
			<th>Gramatika</th>
			<th>Průměr</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				foreach ($review as $rev) {
					$userprep = $DB_con->prepare("SELECT name,surname FROM hainj_user WHERE id=$rev[User_id]");
					$userprep->execute();
					$revname = $userprep->fetch(PDO::FETCH_ASSOC);

					$revname = $revname['name']." ".$revname['surname'];
					
					$average = ($rev['original'] + $rev['technology'] + $rev['topic'] + $rev['grammar'])/4;
					echo "<tr>";
					echo "<td>$revname</td>";
					echo "<td>$rev[topic]</td>";
					echo "<td>$rev[technology]</td>";
					echo "<td>$rev[original]</td>";
					echo "<td>$rev[grammar]</td>";
					echo "<td>$average</td>";
					echo "</tr>";
				}


			 ?>
		</tbody>
	</table>
	<?php } ?>
</div>