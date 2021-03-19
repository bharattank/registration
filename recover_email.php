<?php
// Include config file
require_once "config.php";
include 'link.php';
 
 // Define variables and initialize with empty values
$username = $password = $confirm_password = $email = $firstname = $lastname = $emailcount = "";
$username_err = $password_err = $confirm_password_err = $email_err = $firstname_err = $lastname_err ="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Email validation
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";     
    } else{
        $email = trim($_POST["email"]);
    }

    
     $emailquery = " select `email` from user where `email` = '$email' ";
     $query = mysqli_query($link, $emailquery);

     $emailcount = mysqli_num_rows($query);

      if($emailcount){
       header("Location: reset-password.php");
       }else{
          echo "Email not Found...";
      }

    if($emailcount){

      $userdata = mysqli_fetch_array($query);
      
      $email = $userdata['email'];
      $id = $userdata['id'];
      $subject = "Email Activation";
      $body = "Hi $username. Click here too activate your account
      http://localhost/Registration/reset-password.php?id=$id";
      $header="http://localhost/registration/reset-password.php";
      $sender_email = "From : bharattank903@gmail.com";
      
      if(mail($username, $subject, $body,$header, $sender_email)) {
      $_SESSION['email'] = "check your mail to reset your password $email";
      header('location:login.php');
      }else{
      echo "Email Sending Failed...";
      }
      }else{
      echo "Email not Found...";
      }
}
?> 


<!DOCTYPE html>
<html>
  <head>
  
  </head>
    <body class="hold-transition register-page">
    <div class="register-box">
      <div class="register-logo">
        <a href="../../index2.html"><b>Admin</b>LTE</a>
      </div>

      <div class="card">
        <div class="card-body register-card-body">
          <h1 class="login-box-msg">Recover Account</h1>

          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="recover_email">
            <div class="input-group mb-3 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
              <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <span class="help-block"><?php echo $email_err; ?></span>
            <div class="row">
              <div class="col">
                <a href="login.php"><button type="submit" class="btn btn-primary btn-block" value="Submit">Send Mail</button></a>
              </div>
            </div>
          </form>
          

          <a href="login.php" class="text-center">I already have a membership</a>
        </div>
        <!-- /.form-box -->
      </div><!-- /.card -->
    </div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="./plugin/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugin/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./plugin/adminlte.min.js"></script>
<!-- Validate JS -->
<script src="./plugin/jquery.validate.js"></script>




<script>
    $(document).ready(function() {
        $("#recover_email").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                email: "*Please enter a valid Email address",
            },
                errorElement: 'span',
                errorPlacement : function(error,element){
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                }
        });
    });
</script>
</body>
</html>