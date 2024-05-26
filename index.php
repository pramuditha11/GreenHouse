<!doctype html>
<html lang="en">
  <head>
  	<title>Login 10</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	</head>
    
    <div style="text-align: center; padding-top: 20px;">
        <img src="images/logo.png" style="width: 120px; height: 110px;">
    </div>

	<body class="img js-fullheight" style="background-image: url(images/wp.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
                    
					<h2 class="heading-section"><i class="fa-solid fa-user fa-bounce fa-lg" style="color: #ffffff;"></i> Login</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
                    <?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are correct
    if ($_POST['username'] === 'greenhouse' && $_POST['password'] === 'greenhouse') {
        // Authentication successful, redirect to esp-weather-station.php
        header("Location: esp-weather-station.php");
        exit();
    } else {
        // Authentication failed, display error message
        $error = true;
    }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="signin-form" method="post">
    <div class="form-group">
        <input type="text" class="form-control"  placeholder=" Username" name="username" required>
    </div>
    <div class="form-group">
        <input id="password-field" type="password" class="form-control" placeholder="Password" name="password" required>
        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
    </div>
    <div class="form-group">
        <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
    </div>
    <?php 
        if(isset($error)){
            echo "<span style='color:red;'>Username or password is incorrect</span>";
        }
    ?>
</form>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>



  <footer>
<div style="position: fixed; left: 0; bottom: 0; width: 100%;height: 50px; background-color: #333; color: white; text-align: center; padding: 10px 5px 10px 5px;">
<marquee><p><b>Copyright © 2024 All rights reserved.</b> | Lanka Minerals & Chemicals (Pvt) Ltd</p></marquee>
</div></footer> 
	</body>
</html>

