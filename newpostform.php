<?php if($user->is_loggedin() ==""){
    $user->redirect("index.php");
}  ?>

 <div class="container" style="width: 30%; padding-top: 25px; margin: auto; ">
    <form class="form-horizontal" ENCTYPE="multipart/form-data" method="POST" action="add.php" id="newpost">
        <div class="form-group">
            <label for="nazev" class="col-lg-3 col-sm-3">Název</label>
            <div class="col-lg- col-sm-7">
                <input type="text" class="form-control" name="nazev" id="nazev" required="required">
            </div>
        </div>

        <div class="form-group">
            <label for="autor" class="col-lg-3 col-sm-3">Autoři</label>
            <div class="col-lg-7 col-sm-7">
                <input type="text" class="form-control" name="autor" id="autor" required="required">
            </div>
        </div>

        <div class="form-group">
            <label for="abstrakt" class="col-lg-3">Abstrakt</label>
            <div class="col-lg-10 col-sm-10">
                <textarea class="form-control" name="abstrakt" id="abstrakt" required="required"></textarea>
             </div>
        </div>
        <div class="form-group">
            <label for="soubor" class="col-lg-3">Vyber soubor</label>
            <div class="col-lg-3">
                <input name="soubor" id="soubor" type="file" required="required">
            </div>
        </div>
        <button type="submit" tabindex="6" name="submit" class="btn btn-default">Submit</button>
    </form>   

</div>