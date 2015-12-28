<?php
class USER
{
    private $db;
 
    function __construct($DB_con)
    {
      $this->db = $DB_con;
    }
 
    public function register($name,$surn, $email,$pass)
    {
       try
       {
           $new_password = password_hash($pass, PASSWORD_DEFAULT);
   
           $stmt = $this->db->prepare("INSERT INTO user(id,name,surname,email,password,Rights_id) 
                                                       VALUES(NULL,:uname,:surn ,:umail, :upass,1)");
              
           $stmt->bindparam(":uname", $name);
           $stmt->bindparam(":surn", $surn);
           $stmt->bindparam(":umail", $email);
           $stmt->bindparam(":upass", $new_password);            
           $stmt->execute(); 
   
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }
    public function update($name, $surn, $email,$pass, $rights, $iduser)
    {
       try
       {
        echo "lllll";
           $new_password = password_hash($pass, PASSWORD_DEFAULT);
   
           $stmt = $this->db->prepare("UPDATE `user` SET `name`=:uname,`surname`=:surn ,`email`=:umail, `password`=:upass,`Rights_id`=:rig WHERE `id`=:ida");
              
           $stmt->bindparam(":uname", $name);
           $stmt->bindparam(":surn", $surn);
           $stmt->bindparam(":umail", $email);
           $stmt->bindparam(":upass", $new_password);            
           $stmt->bindparam(":rig", $rights); 
           $stmt->bindparam(":ida", $_SESSION['user_session']); 
           $stmt->execute(); 
            echo "ooooo";
           return $stmt; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
    }
 
   public function login($email,$upass)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM user WHERE email=:email");
          $stmt->bindparam(":email", $email);
          $stmt->execute();
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
            
             if(password_verify($upass, $userRow['password']))
             {
              
                $_SESSION['user_session'] = $userRow['id'];
                $_SESSION['time'] = Time()+1800;
                return true;
             }
             else
             {
                return false;
             }
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   public function is_loggedin()
   {
      if(isset($_SESSION['user_session']))
      {
          $time = $_SESSION['time'];
          if ($time <Time()) {
               $this->logout();  
               $this->redirect("index.php?timeout");     
          }else{
             $_SESSION['time'] = Time()+1800;
          }
         return true;
        }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
 
   public function logout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
        unset($_SESSION['time']);
        return true;
   }
}
?>