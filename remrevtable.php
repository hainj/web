<?php 
if($user->is_loggedin() ==""){
    $user->redirect("index.php");
} 
	$error;
	if (isset($_POST['delete']) && $rights['rights']==10) {
		$delpostid = $_POST['idpost'];
		$deluserid = $_POST['idrev'];
		if (trim($deluserid) == "") {
			$error = "Prázdné id uživatele";
		}elseif (trim($delpostid)=="") {
			$error = "Prázdné id článku";
		}
		elseif (!is_numeric($deluserid)) {
			$error = "Id uživatele neni číslo";
		}elseif (!is_numeric($delpostid)) {
			$error = "Id článku neni číslo";
		}else{
			$del = $DB_con->prepare("DELETE FROM hainj_review WHERE Post_id=$delpostid AND User_id=$deluserid");
			$del->execute();
		}
	}

 ?>
<div class="container" style=" width:60%">
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
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th>ID</th>
			<th>Název</th>
			<th>Recenzent</th>
			<th>Téma</th>
			<th>Technologie</th>
			<th>Originalita</th>
			<th>Gramatika</th>
			<th>Odstranit</th>
		</tr>
	</thead>
	<tbody id="myTable">
		<?php 
		$pre = $DB_con->prepare("SELECT * FROM hainj_review");
		$pre->execute();
		$reviews = $pre->fetchall();
		$id = 0;
		foreach ($reviews as $row) {
			$postid = $row['Post_id'];
			$revid = $row['User_id'];
			$postprep = $DB_con->prepare("SELECT name FROM hainj_post WHERE id=$postid");
			$postprep->execute();
			$postname = $postprep->fetch(PDO::FETCH_ASSOC);

			$userprep = $DB_con->prepare("SELECT name,surname FROM hainj_user WHERE id=$revid");
			$userprep->execute();
			$revname = $userprep->fetch(PDO::FETCH_ASSOC);

			$rev = $revname['name']." ".$revname['surname'];
			echo "<tr>";
				echo "<td>$id</td>";
				echo "<td>$postname[name]</td>";
				echo "<td>$rev</td>";
				echo "<td>$row[topic]</td>";
				echo "<td>$row[technology]</td>";
				echo "<td>$row[original]</td>";
				echo "<td>$row[grammar]</td>";
				echo "<td>
						<form method=\"post\" action=\"#\">
							<input type=\"hidden\" name=\"idpost\" value=\"$row[Post_id]\">
							<input type=\"hidden\" name=\"idrev\" value=\"$row[User_id]\">
							<button  class=\"btn btn-danger\" style=\"display: block; width: 100%;\" type=\"submit\" name=\"delete\">
							<span class=\"glyphicon glyphicon-remove\"></span></button>
						 </form>
					</td>";
			echo "</tr>";
			$id = $id +1;
		}
		?>
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
    
  $('#myTable').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:20});
    
});
        
</script>
