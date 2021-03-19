<?php
// Initialize the session
session_start();
 

 
// Include config file
require_once "config.php";
include 'link.php';
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
       
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE user SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>



 <!DOCTYPE html>
<html>
<head>
  
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="reset_password">
        <div class="input-group mb-3 <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
          <input type="password" name="new_password" class="form-control" placeholder="Password" value="<?php echo $new_password; ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <span class="help-block"><?php echo $new_password_err; ?></span>
        <div class="input-group mb-3 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <span class="help-block"><?php echo $confirm_password_err; ?></span>
        <div class="row">
          <div class="col-12">
           <a href="welcom.php"> <button type="submit" class="btn btn-primary btn-block" value="Submit">Change password</button></a>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="login.php">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

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
        $("#reset_password").validate({
            rules: {
                new_password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    minlength: 6
                },
            },
            messages: {
              new_password: {
                    required: "*Please provide a NewPassword",
                    minlength: "*Your password must be at least 6 characters long"
                },
                confirm_password: {
                    required: "*Please provide a ConfirmPassword",
                    minlength: "*Your password must be at least 6 characters long"
                },
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


