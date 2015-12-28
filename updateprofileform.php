 <div class="container" style="width: 30%; padding-top: 25px;">
           <form class="form-horizontal" method="POST" action="updateprofile.php">
           <div class="form-group">
             <label class="control-label">Jméno</label>
             <input class="form-control" style="width: 50%; display: inline;" type="text" name="jmeno" value="<?php echo $userRow['name']?>" tabindex="2" required>
             </div>
             <div class="form-group">
             <label class="control-label">Příjmení</label>
              <input class="form-control" style="width: 50%; display: inline;" type="text" value="<?php echo $userRow['surname']?>"name="prijmeni" tabindex="3" required>
              </div>
              <div class="form-group">
             <label class="control-label">Email</label>
             <input class="form-control" style="width: 50%; display: inline;" type="email" value="<?php echo $userRow['email']?>" name="email" tabindex="4" required>
             </div>
             <div class="form-group">
             <label class="control-label">Heslo</label>
             <input class="form-control" style="width: 50%; display: inline;" type="password" name="heslo" tabindex="1" required>
             </div>
             <div class="form-group">
             <label class="control-label">Heslo znovu</label>
             <input class="form-control" style="width: 50%; display: inline;" type="password" name="heslo2" tabindex="1" required>
             </div>
             <?php if ($rights['rights'] == 10) {
              ?> 
              <div class="form-group">
              <label class="control-label">Práva</label>
             <select class="form-control" style="width: 50%; display: inline;" id="sel1" name="prava">
                <option <?php if ($rights['rights'] == 1) {
                  echo"selected";
               }?>  value="1">Uživatel</option>
                <option<?php if ($rights['rights'] == 5) {
                   echo("selected");
               }?>  value="2">Recenzent</option>
                <option <?php if ($rights['rights'] == 10) {
                   echo("selected");
              }?>  value="3">Admin</option>
             </select>
             </div><?php
             }?>
             <button type="submit" name="submit"class="btn btn-default">Submit</button>
           </form>   

		</div>