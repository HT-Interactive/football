<form class="form-signin" action="login.php" method="post">
  <h2>Please sign in</h2> 
  <label for="inputEmail" class="sr-only">Email address</label>
  <input type="email" id="inputEmail" name="user_email" class="form-control" placeholder="Email address"<?php if(isset($this_user_email)) { echo ' value="'.urldecode($this_user_email).'"'; } ?> required autofocus>
  <div class="input-group">
  	<label for="inputPassword" class="sr-only">Password</label>
  	<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
  	<span class="input-group-btn">
  		<button class="btn btn-default btn-forgot" type="submit" name="forgot" value="pass" onclick="clearPassword()"><small>Forgot?</small></button>
  	</span>
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit" id="loginButton" name="login" value="Login">Sign in</button>
</form>
<center>or <a href="index.php?register=yes">Register</a></center>
