<?php
require_once 'dbconfig.php';
if($user->is_loggedin() ==""){
	$user->redirect("index.php");
} 
$error;
$success;
if ($rights['rights']==10) {
	
	if (isset($_POST['approve'])) {
		$postid= $_POST['id'];
		if (trim($postid)=="") {
			$error = "Prázdné id článku";
		}elseif (!is_numeric($postid)) {
			$error = "Id článku neni číslo";
		}
		else{
			$checkrev = $DB_con->prepare("SELECT * FROM hainj_review WHERE Post_id=$postid");
			$checkrev->execute();
			if ($checkrev->rowCount()==3) {
				$pre= $DB_con->prepare("UPDATE hainj_post SET approve=1 WHERE id=$postid");
				$pre->execute();
				$success = "Článek schválen";
			}else{
				$error="Článek nebyl 3x recenzován";
			}
		}
		
	}
	if(isset($_POST['addrev'])){
		
		$postid= $_POST['id'];
		$idrev= $_POST['idrev'];
		if (trim($postid) == "") {
			$error = "Prázdné id uživatele";
			
		}elseif (trim($idrev)=="") {
			
			$error = "Prázdné id článku";
		}
		elseif (!is_numeric($postid)) {
			
			$error = "Id uživatele neni číslo";
		}elseif (!is_numeric($idrev)) {
		

			$error = "Id článku neni číslo";
		}else{
			$check = $DB_con->prepare("SELECT * FROM hainj_review WHERE User_id=$idrev AND Post_id=$postid");
			$check->execute();
			$rowcount = $check->rowCount();
			if ($rowcount > 0) {
				$error="Tato recenze již byla přiřazena";
			
			}else{
				
				$addrev= $DB_con->prepare("INSERT INTO hainj_review(topic,technology,original,grammar,User_id,Post_id) VALUES(0,0,0,0,$idrev,$postid)");
				$addrev->execute();
				$success = "Recenze úspěšně přiřazena";
			}
		}
	}

}

if (isset($_POST['delete'])) {
	$id=$_POST['id'];
	if (trim($id)=="") {
			$error = "Prázdné id článku";
		}elseif (!is_numeric($id)) {
			$error = "Id článku neni číslo";
	}else{
		$check = false;
		if($rights['rights'] != 10){
			$postcheck = $DB_con->prepare("SELECT id, User_id FROM hainj_post WHERE id=$id");
			$postcheck->execute();
			$row = $postcheck->fetch(PDO::FETCH_ASSOC);
			if ($_SESSION['user_session'] == $row['User_id']) {
				$check = true;
			}
		}else{
			$check = true;
		}
		if($check == true){
			$delrevs = $DB_con->prepare("DELETE FROM hainj_review WHERE Post_id=$id");
			$delrevs->execute();
			$delpost = $DB_con->prepare("DELETE FROM hainj_post WHERE id=$id");
			$delpost->execute();
			$success = "Článek a náležící recenze smazány";
		}
	}
}

if ($rights['rights']==10) {
	$stmt = $DB_con->prepare("SELECT * FROM hainj_Post");
	$stmt->execute();
	$posts=$stmt->fetchAll();
}else{
	$stmt = $DB_con->prepare("SELECT * FROM hainj_Post WHERE User_id=$user_id");
	$stmt->execute();
	$posts=$stmt->fetchAll();
}
?>
<?php
if(isset($error)){
?>
    <div class="text">
    	<div class="alert alert-danger">
    		<i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
    	</div>
    </div>
<?php
}
?>
<?php
if(isset($success)){
?>
    <div class="text">
    	<div class="alert alert-success">
    		<i class="glyphicon glyphicon-ok"></i> &nbsp; <?php echo $success; ?>
    	</div>
    </div>
<?php
}
?>
<div class="container" style="width: 80%;padding-top:25px;">
<table class="table table-striped" style="border: 0px;">

	<thead>
	<tr>
		<th rowspan="2" >Id příspěvku</th>
		<th class="col-md-3" style="overflow: hidden" rowspan="2" >Název</th>
		<th rowspan="2">Autor</th>
		<th rowspan="2">Detail</th>
		<th rowspan="2">Pdf dokument</th>
		<th colspan="6">Recenze</th>
		<th rowspan="2">Schváleno</th>
		<th rowspan="2">Odstranit</th>
			
	</tr>
	<tr>
		<th>Recenzent</th>
		<th>Originalita</th>
		<th>Téma</th>
		<th>Technologie</th>
		<th>Gramatika</th>
		<th>Průměr</th>
		
		</tr>
	</thead>
	<tbody id="myTable">
<?php 
	$id = 0;
	foreach($posts as $row) {
		
		$maxRev = 0;
		
		 
			$stmt = $DB_con->prepare("SELECT * FROM hainj_review WHERE Post_id=:urev");
			$stmt->bindparam(":urev", $row["id"]);
			$stmt->execute();
			
			  echo "<tr>
					<td rowspan = \"3\" >$id</td>
					<td class=\"col-md-3\" style=\"overflow: hidden\" rowspan =\"3\">$row[name]</td>
					<td rowspan = \"3\">$row[author]</td>
					<td rowspan = \"3\"><a href=\"detail.php?id=$row[id]&post=\">Detail</a></td>";
					/*echo "$row[pdf]";
				$pdf = urldecode($row['pdf']);*/

			echo "<td  rowspan = \"3\"><a href=\"$row[pdf]\">Stáhnout</a></td>";
		


			while ($review = $stmt->fetch(PDO::FETCH_ASSOC)) {

				$pre = $DB_con->prepare("SELECT * FROM hainj_user WHERE id=:uid");
				$pre->bindparam(":uid", $review["User_id"]);
				$pre->execute();

				$user =$pre->fetch(PDO::FETCH_ASSOC);
				if ($maxRev==0) {
					# code...
				
						echo "<td>$user[name] $user[surname]</td>";
 						echo "<td>$review[original]</td>";
 						echo "<td>$review[topic]</td>";
 						echo "<td>$review[technology]</td>";
 						echo "<td>$review[grammar]</td>";
						$average = ($review['original'] + $review['technology'] + $review['topic'] + $review['grammar'])/4;
 						echo "<td>$average</td>";
 						if ($rights['rights']==10) {


 							if ($row['approve']==1) {
										echo "<td rowspan=\"3\">Schváleno</td>";
									}else{
										echo "<td rowspan = \"3\"><form  method=\"POST\" action=\"#\">
										<input type=\"hidden\" name=\"id\" value=\"$row[id]\"> 
										<button  class=\"btn btn-success\" style=\"display: block; width: 100%;\" type=\"submit\" name=\"approve\">
										<span class=\"glyphicon glyphicon-ok\">
										</span></button> </form> </td>";
									}



 							
 				 		}else{
 				 			if ($row['approve']==1) {
										echo "<td rowspan=\"3\">Schváleno</td>";
									}else{
										echo "<td rowspan = \"3\">Neschváleno</td>";
									}

 				 		}
 				 			echo"<td rowspan = \"3\" ><form method=\"post\" action=\"#\">
 							<input type=\"hidden\" name=\"id\" value=\"$row[id]\"> 
 							<button  class=\"btn btn-danger\" style=\"display: block; width: 100%;\" type=\"submit\" name=\"delete\">
 							<span class=\"glyphicon glyphicon-remove\">
 							</span></button> </form> </td>";
 				 		echo "</tr>";
 						$maxRev = $maxRev+1;
 				}else{

 					echo "<tr><td>$user[name] $user[surname]</td>";
 						echo "<td>$review[original]</td>";
 						echo "<td>$review[topic]</td>";
 						echo "<td>$review[technology]</td>";
 						echo "<td>$review[grammar]</td>";
						$average = ($review['original'] + $review['technology'] + $review['topic'] + $review['grammar'])/4;
 						echo "<td>$average</td></tr>";
 						$maxRev = $maxRev +1;
 				}

				}
				
					$count = 3  -$maxRev;
					for ($i=0; $i < $count; $i++) { 
						
						/*if ($userRow['Rights_id'] !=3) {*/
							if ($count == 3 && $i == 0) {
								
								if ($rights['rights']==10) {

									$prep = $DB_con->prepare("SELECT * FROM hainj_user WHERE Rights_id=2");
									$prep->execute();


									echo "<td colspan=\"6\"><form action=\"#\" method=\"POST\"  class=\"form-inline\"><input type=\"hidden\" name=\"id\" value=\"$row[id]\"> <select style=\"width: 50%\" name=\"idrev\" class=\"form-control\">";
									while ($recenzent = $prep->fetch(PDO::FETCH_ASSOC)) {
										$jmeno = $recenzent['name']." ".$recenzent['surname']; 
										echo "<option value=\"$recenzent[id]\">$jmeno</option>";
									}
									echo "</select>

									<input type=\"submit\" name=\"addrev\" class=\"btn btn-default\">
									</form></td>";
									if ($row['approve']==1) {
										echo "<td rowspan=\"3\">Schváleno</td>";
									}else{
										echo "<td rowspan = \"3\"><form  method=\"POST\" action=\"#\">
										<input type=\"hidden\" name=\"id\" value=\"$row[id]\"> 
										<button  class=\"btn btn-success\" style=\"display: block; width: 100%;\" type=\"submit\" name=\"approve\">
										<span class=\"glyphicon glyphicon-ok\">
										</span></button> </form> </td>";
									}
									
									
								}else{
									echo "<td colspan=\"6\">Neni dostupná recenze</td>";
									if ($row['approve']==1) {
										echo "<td rowspan=\"3\">Schváleno</td>";
									}else{
										echo "<td rowspan = \"3\">Neschváleno</td>";
									}

								}
								echo "<td rowspan = \"3\">";
									
									echo "<form  method=\"post\" action=\"#\">
									<input type=\"hidden\" name=\"id\" value=\"$row[id]\"> 
									<button  class=\"btn btn-danger\" style=\"display: block; width: 100%;\" type=\"submit\" name=\"delete\">
									<span class=\"glyphicon glyphicon-remove\">
									</span></button> </form> </td>";
								echo " </tr>";
							}else{
								if ($rights['rights']==10) {

									$prep = $DB_con->prepare("SELECT * FROM hainj_user WHERE Rights_id=2");
									$prep->execute();


									echo "<tr><td colspan=\"6\"><form action=\"#\" method=\"POST\" class=\"form-inline\"><input type=\"hidden\" name=\"id\" value=\"$row[id]\"> <select name=\"idrev\" class=\"form-control\" style=\"width: 50%\">";
									while ($recenzent = $prep->fetch(PDO::FETCH_ASSOC)) {
										$jmeno = $recenzent['name']." ".$recenzent['surname']; 
										echo "<option value=\"$recenzent[id]\">$jmeno</option>";
									}
									echo "</select>

									<input type=\"submit\" name=\"addrev\" class=\"btn btn-default\">
									</form></td>";
								}else{
								
								echo "<tr><td colspan=\"6\">Neni dostupná recenze</td>";
								}
								echo "</tr>";
							}

						/*}*/
					}


				
		
	
	$id = $id +1;
	

}?>
</tbody>
	
</table>
	<div class="col-md-12 text-center">
       <ul class="pagination pagination-sm pager" id="myPager"></ul>
    </div>

</div>
<script type='text/javascript'>
        
    $.fn.pageMe = function(opts){
    var $this = this,
        defaults = {
            perPage: 7,
            showPrevNext: false,
            hidePageNumbers: false
        },
        settings = $.extend(defaults, opts);
    
    var listElement = $this;
    var perPage = settings.perPage; 
    var children = listElement.children();
    var pager = $('.pager');
    
    if (typeof settings.childSelector!="undefined") {
        children = listElement.find(settings.childSelector);
    }
    
    if (typeof settings.pagerSelector!="undefined") {
        pager = $(settings.pagerSelector);
    }
    
    var numItems = children.size();
    var numPages = Math.ceil(numItems/perPage);

    pager.data("curr",0);
    
    if (settings.showPrevNext){
        $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
    }
    
    var curr = 0;
    while(numPages > curr && (settings.hidePageNumbers==false)){
        $('<li><a href="#" class="page_link">'+(curr+1)+'</a></li>').appendTo(pager);
        curr++;
    }
    
    if (settings.showPrevNext){
        $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
    }
    
    pager.find('.page_link:first').addClass('active');
    pager.find('.prev_link').hide();
    if (numPages<=1) {
        pager.find('.next_link').hide();
    }
  	pager.children().eq(1).addClass("active");
    
    children.hide();
    children.slice(0, perPage).show();
    
    pager.find('li .page_link').click(function(){
        var clickedPage = $(this).html().valueOf()-1;
        goTo(clickedPage,perPage);
        return false;
    });
    pager.find('li .prev_link').click(function(){
        previous();
        return false;
    });
    pager.find('li .next_link').click(function(){
        next();
        return false;
    });
    
    function previous(){
        var goToPage = parseInt(pager.data("curr")) - 1;
        goTo(goToPage);
    }
     
    function next(){
        goToPage = parseInt(pager.data("curr")) + 1;
        goTo(goToPage);
    }
    
    function goTo(page){
        var startAt = page * perPage,
            endOn = startAt + perPage;
        
        children.css('display','none').slice(startAt, endOn).show();
        
        if (page>=1) {
            pager.find('.prev_link').show();
        }
        else {
            pager.find('.prev_link').hide();
        }
        
        if (page<(numPages-1)) {
            pager.find('.next_link').show();
        }
        else {
            pager.find('.next_link').hide();
        }
        
        pager.data("curr",page);
      	pager.children().removeClass("active");
        pager.children().eq(page+1).addClass("active");
    
    }
};

$(document).ready(function(){
    
  $('#myTable').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:30});
    
});
        
        </script>
