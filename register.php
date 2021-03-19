<?php
// Include config file
require_once "config.php";
include 'link.php';
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = $firstname = $lastname ="";
$username_err = $password_err = $confirm_password_err = $email_err = $firstname_err = $lastname_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM user WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
            
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
    // Email validation
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";     
    } else{
        $email = trim($_POST["email"]);
    }

    // FirstName validation
    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter Your FirstName.";     
    } else{
        $firstname = trim($_POST["firstname"]);
    }

    // LasttName validation
    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Please enter Your LastName.";     
    } else{
        $lastname = trim($_POST["lastname"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO user (firstname, lastname, username, password, email) VALUES (?, ?, ?, ?, ?)";
       
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_firstname, $param_lastname, $param_username, $param_password, $param_email);
            
            // Set parameters
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
           
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <h1 class="login-box-msg">Register Now</h1>

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registration_form">
      <div class="input-group mb-3 <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
          <input type="text" name="firstname" class="form-control" placeholder="FirstName" value="<?php echo $firstname; ?>">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        <span class="help-block"><?php echo $firstname_err; ?></span>
        <div class="input-group mb-3 <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
          <input type="text" name="lastname" class="form-control" placeholder="LastName" value="<?php echo $lastname; ?>">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        <span class="help-block"><?php echo $lastname_err; ?></span>

      <div class="input-group mb-3 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
          <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        <span class="help-block"><?php echo $username_err; ?></span>
        <div class="input-group mb-3 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
          <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        <span class="help-block"><?php echo $email_err; ?></span>
        <div class="input-group mb-3 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <input type="password" name="password" class="form-control" id="pass_word" placeholder="Password" value="<?php echo $password; ?>">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <span class="help-block"><?php echo $password_err; ?></span>
        <div class="input-group mb-3 <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <input type="password" name="confirm_password" class="form-control" id="con_pass_word" placeholder="Retype password" value="<?php echo $confirm_password; ?>">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <span class="help-block"><?php echo $confirm_password_err; ?></span>
        <div class="row input-group">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agree" name="terms" value="agree">
                <label for="agree">
                    I agree to the <a href="#">terms</a>
                </label>
            </div>
          </div>
          <div class="col-4">
            <a href="login.php"><button type="submit" class="btn btn-primary btn-block" value="Submit">Register</button></a>
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
        $("#registration_form").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                email: {
                    required: true,
                    email: true
                },
                username: {
                    required: true,
                    minlength: 6
                },
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#pass_word"
                },
                terms: {
                    required: true
                },
            },
            messages: {
                firstname: "*Please enter your FirstName",
                lastname: "*Please enter your LastName",
                email: "*Please enter a valid Email address",
                username: {
                    required: "*Please enter a Username",
                    minlength: "*Your username must consist of at least 6 characters"
                },
                password: {
                    required: "*Please provide a password",
                    minlength: "*Your password must be at least 6 characters long"
                },
                confirm_password: {
                    required: "*Please provide a confirmpassword",
                    minlength: "*Your password must be at least 6 characters long",
                    equalTo: "*Please enter the same password as above"
                },
                terms: "*Please accept our policy",
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