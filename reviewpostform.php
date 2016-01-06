<?php 

if($user->is_loggedin() ==""){
  $user->redirect("index.php");
}
 	$stmt = $DB_con->prepare("SELECT * FROM hainj_review WHERE User_ID=$user_id");
	$stmt->execute();
	$review=$stmt->fetchAll();
	$error;
 if (isset($_GET['review'])) {
 	$id = $_GET['id'];
 	$orig =$_GET['orig'];
 	$tema = $_GET['tema'];
 	$tech = $_GET['tech'];
 	$gram = $_GET['gram'];
 	if (!is_numeric($orig)) {
 		$error = "Hodnoceni originality není číslo";
 	}elseif (!is_numeric($tema)) {
 		$error = "Hodnocení téma není číslo";
 	}elseif (!is_numeric($tech)) {
 		$error = "Hodnocení technologie není číslo";
 	}elseif (!is_numeric($gram)) {
 		$error = "Hodnoceni gramtiky není číslo";
 	}
 	$stmt = $DB_con->prepare("SELECT * FROM `hainj_review` WHERE `User_id`=$user_id AND `Post_id`=$id");
	$stmt->execute();
	$rowCount = $stmt->rowCount();
	if ($rowCount == 1) {
		$stmt = $DB_con->prepare("UPDATE `hainj_review` SET `original`=$orig, `topic`=$tema, `technology`=$tech, `grammar`=$gram WHERE `User_ID`=$user_id AND `Post_id`=$id");
		$stmt->execute();
		$user->redirect("review.php");
	}else{
		$error = "Tato recenze není přiřazena";
	}
 }
?>
 <div class="container" style="width: 50%; padding-top: 25px; margin: auto; ">
 	
    <form class="form-horizontal" ENCTYPE="multipart/form-data" method="get" action="#">
        <div class="form-inline">
        	<label for="id_clanku" class="col-lg-3 control-label">Vyberte článek:</label>
			<select name="id_clanku" id="id_clanku" class="col-lg-5 form-control"  style=" min-width: 250px; text-overflow: ellipsis; max-width: 350px;">
				<?php 
					foreach ($review as $row) {
						$stmt = $DB_con->prepare("SELECT * FROM hainj_post WHERE id=$row[Post_id]");
						$stmt->execute();
						$post=$stmt->fetch(PDO::FETCH_ASSOC);

						echo "<option style=\"text-overflow: hidden;\" value =\"$row[Post_id]\">$post[name]</option>";
					} 
				?>
			</select>
			 <button type="submit" tabindex="6" name="post_select" class="btn btn-default">Submit</button>
		</div>	
	</form>
 </div>


 <?php if (isset($_GET['post_select'])) {
 	?>
 		 <div class="container" style="width: 30%; padding-top: 25px; margin: auto; ">
           <form class="form-horizontal" ENCTYPE="multipart/form-data" method="get" action="#" id="newpost">
            <?php 
            	 echo "<input type=\"hidden\" name=\"id\" value=\"$_GET[id_clanku]\">";
            	 ?>
             <div class="form-group">
              <label class="col-lg-5 col-sm-3">Hodnocení článku</label>
             </div>
            <div class="form-group">
                <label for="orig" class="col-lg-3 col-sm-3">Originalita</label>
                <div class="col-lg- col-sm-7">
                    <select class="form-control" name="orig" id="orig" required="required">
                    	<option value="">Vyberte hodnocení</option>
                    	<option>1</option>
                    	<option>2</option>
                    	<option>3</option>
                    	<option>4</option>
                    	<option>5</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="tema" class="col-lg-3 col-sm-3">Téma</label>
                <div class="col-lg-7 col-sm-7">
                    <select class="form-control" name="tema" id="tema" required="required">
                    	<option value="">Vyberte hodnocení</option>
                    	<option>1</option>
                    	<option>2</option>
                    	<option>3</option>
                    	<option>4</option>
                    	<option>5</option>
                    </select>
              </div>
            </div>

            <div class="form-group">
                <label for="tech" class="col-lg-3">Technologie</label>
                <div class="col-lg-7 col-sm-7">
                     <select class="form-control" name="tech" id="tech" required="required">
                     	<option value="">Vyberte hodnocení</option>
                     	<option>1</option>
                    	<option>2</option>
                    	<option>3</option>
                    	<option>4</option>
                    	<option>5</option>
                     </select>
                </div>
            </div>
            <div class="form-group">
                <label for="gram" class="col-lg-3">Gramatika</label>
                <div class="col-lg-7 col-sm-7">
                     <select name="gram" id="gram" class="form-control" required="required">
                     	<option value="">Vyberte hodnocení</option>
                     	<option>1</option>
                    	<option>2</option>
                    	<option>3</option>
                    	<option>4</option>
                    	<option>5</option>
                	</select>
                </div>
            </div>
            <button type="submit" tabindex="6" name="review" class="btn btn-default">Submit</button>
           </form>   

		</div>
 	<?php 
 }

 ?>