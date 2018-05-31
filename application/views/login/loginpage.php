<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="<?php echo 'css/style.css'; ?>">
  <title>Loginpage</title>
</head>
<body>
	<?php 
	    if($this->session->has_userdata('disabledAccount'))
	    {
			$text = $this->session->userdata('disabledAccount');
			echo "<script type='text/javascript'>alert('$text');</script>";
	    }
		elseif($this->session->has_userdata('loginStatus'))
		{
			$text = $this->session->userdata('loginStatus');
			echo "<script type='text/javascript'>alert('$text');</script>";
		}
	?>
	<div class="login">
		<div class="banner"></div>
		<form class="form" method="POST" action="Login/checkAccount">
			<div class="wrapper">
				<div class="row">
					<div class="label">Username</div>
					<input type="text" name="username">
				</div>
				<div class="row">
					<div class="label">Password</div>
					<input type="password" name="password">
				</div>
				<div class="row">
					<button>Login</button>
				</div>  
			</div><!-- 
			<div class="signup">
				Don't have an account? <a href="#">Signup</a>
			</div>  -->   
		</form>
	</div>
</body>
</html>