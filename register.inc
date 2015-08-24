<form class="form-signin" action="register.php" method="post">
  <h2>Please register</h2> 
  <label for="inputEmail" class="sr-only">Email address</label>
  <input type="email" id="inputEmail" name="user_email" class="form-control" placeholder="Email address" required autofocus>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
  <label for="inputPassword2" class="sr-only">Re-enter Password Again</label>
  <input type="password" id="inputPassword2" name="password2" class="form-control" placeholder="Re-enter Password" required>
  <label for="inputUserName" class="sr-only">User Name</label>
  <input type="text" id="inputUserName" name="user_name" class="form-control" placeholder="User Name" required>
  <label for="selectGroup">Select a Group to Join</label> 
  <select id="selectGroup" class="form-control" name="group">
  <?php
  	foreach(getPublicGroups($db) as $group) {
  		echo '<option value="'.$group['group_id'],'">'.$group['group_name'].'</option>';
  	}
  ?>
	</select> 
  <button class="btn btn-lg btn-primary btn-block" type="submit" id="registerButton" name="register" value="Register">Register</button>
</form>