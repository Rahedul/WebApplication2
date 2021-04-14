<?php
    include_once 'session.php';
    include 'database.php';
    include ('smtp/PHPMailerAutoload.php');

class user{
    private $db;
    public function __construct(){
        $this->db = new database();
    }

    public function userRegistration($data)
    {
        $name   = $data['name'];
        $email  = $data['email'];
        $mobile = $data['mobile'];
        $pass   = md5($data['pass']);
        $verification_status = 0;

        $chk_email = $this->emailCheck($email);

        if ($name == "" or $email == "" or $mobile == "" or $pass == "" ) {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Field must not be Empty</div>";
            return $mgs;
        }

        if (strlen($name)<3) {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>User name is too Short!</div>";
            return $mgs;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $mgs = "<div class='alert alert-danger'><strong>Error! </strong>The email address is already Exist!</div>";
            return $mgs;
        }

        if ($chk_email == true) {
            $mgs = "<div class='alert alert-danger'><strong>Error! </strong>The email address is not valid!</div>";
            return $mgs;
        }

        /*
        $con = mysqli_connect("localhost","root","","db_lr");
        mysqli_query($con, "INSERT INTO tabel_user (name, email, pass, mobile, verification_status) values('$name', '$email', '$mobile', '$pass', 0");
        $id = mysqli_insert_id($con);
        */

        $sql = "INSERT INTO tabel_user (name, email, mobile, pass) VALUES(:name, :email, :mobile, :pass)";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':name',$name);
        $query->bindValue(':email',$email);
        $query->bindValue(':mobile',$mobile);
        $query->bindValue(':pass',$pass);
        $result = $query->execute();

        if ($result) {
            $conn = mysqli_connect('localhost', 'root', '', 'db_lr');
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "SELECT id FROM tabel_user";
            $result = mysqli_query($conn, $sql);
            $id = 50;
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) 
                {
                    $id++;
                }
                mysqli_close($conn);
            }

            $mailHtml = "Please confirm your registration by clicking the button bellow: <a href='http://localhost:8080/accountAutomationSystem/verification.php?id=$id'>http://localhost:8080/accountAutomationSystem/verification.php?id=$id</a>";
            
            if($this->smtp_mailer($email, 'Account Varification', $mailHtml) == true)
            {
                $msg = "<div class='alert alert-success'><strong>Success! </strong>We've sent you a confirmation email.</div>";
                return $msg;
            }
        }
        else{
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong> Sorry! There have been problem inserting your details!</div>";
            return $mgs;
        }
    }

    public function smtp_mailer($to,$subject, $msg){
        $mail = new PHPMailer(); 
        $mail->SMTPDebug  = 0;
        $mail->IsSMTP(); 
        $mail->SMTPAuth = true; 
        $mail->SMTPSecure = 'tls'; 
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587; 
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Username = "just.automation.18@gmail.com";
        $mail->Password = "accountautomation@12345";
        $mail->SetFrom("just.automation.18@gmail.com");
        $mail->Subject = $subject;
        $mail->Body =$msg;
        $mail->AddAddress($to);
        $mail->SMTPOptions=array('ssl'=>array(
            'verify_peer'=>false,
            'verify_peer_name'=>false,
            'allow_self_signed'=>false
        ));
        if(!$mail->Send()){
            return false;
        }else{
            return true;
        }
    }

    public function emailCheck($email){
        $sql = "SELECT email FROM tabel_user WHERE email = :email";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email',$email);
        $query->execute();
        if ($query->rowCount()>0) {
            return true;
        }
        else{
            return false;
        }
    }

    public function getLoginUser($email,$pass){
        $sql = "SELECT * FROM tabel_user WHERE email = :email AND pass = :pass LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email',$email);
        $query->bindValue(':pass',$pass);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function userLogin($data){
        $email  = $data['email'];
        $pass   = md5($data['pass']);

        $conn = mysqli_connect('localhost', 'root', '', 'db_lr');

        $chk_email = $this->emailCheck($email);

        if ($email == "" or $pass == "") {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Field must not be Empty</div>";
            return $mgs;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>The email address is already Exist!</div>";
            return $mgs;
        }

        if ($chk_email == false) {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>The email address not exist!</div>";
            return $mgs;
        }
        
        if ($verification_status == false) {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong> Please varify your email first.</div>";
            return $mgs;
        }

        $result = $this->getLoginUser($email, $pass);
        if ($result) {
            session::init();
            session::set("login", true);
            session::set("id", $result->id);
            session::set("name", $result->name);
            session::set("loginmgs", "<div class='alert alert-success'><strong>Success</strong>You are LoggedIn.</div>");
            header("Location: index.php");
        }
        else{
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Data not found!</div>";
            return $mgs;
        }
    }

    public function getuserdata(){
        $sql = "SELECT * FROM tabel_user ORDER BY id ASC";
        $query = $this->db->pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
    public function getuserbyid($id){
        $sql = "SELECT * FROM tabel_user  WHERE id = :id LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':id',$id);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function updateUserData($id, $data){
        $name   = $data['name'];
        $email  = $data['email'];
        $mobile = $data['mobile'];

        if ($name == "" or $email == "" or $mobile == "") {
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Field must not be Empty</div>";
            return $mgs;
        }

        $sql = "UPDATE  tabel_user set 
                    name    = :name,
                    email   = :email,
                    mobile  = :mobile
                WHERE id    = :id";

        $query = $this->db->pdo->prepare($sql);

        $query->bindValue(':name',$name);
        $query->bindValue(':email',$email);
        $query->bindValue(':mobile',$mobile);
        $query->bindValue(':id', $id);
        $result = $query->execute();
        if ($result) {
            $mgs = "<div class='alert alert-success'><strong>Success</strong> User data updated seccessfully.</div>";
            return $mgs;
        }
        else{
            $mgs = "<div class='alert alert-danger'><strong>Error!</strong>Sorry! User data not updated!</div>";
            return $mgs;
        }
    }
}

?>