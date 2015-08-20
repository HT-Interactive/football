<form class="form-signin" action="login.php" method="post">
  <h2>Please enter a new password</h2> 
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
  <label for="inputPassword2" class="sr-only">Re-enter Password Again</label>
  <input type="password" id="inputPassword2" name="password2" class="form-control" placeholder="Re-enter Password" required>
  <input type="hidden" name="user_email" value="<?php echo $this_user_email; ?>">
  <input type="hidden" name="code" value="<?php echo $this_code; ?>">
  <button class="btn btn-lg btn-primary btn-block" type="submit" id="changePassButton" name="forgot" value="password">Change Password</button>
</form>