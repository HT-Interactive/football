<form class="form-signin" action="register.php" method="post">
  <h2>Please sign in</h2> 
  <label for="inputEmail" class="sr-only">Email address</label>
  <input type="email" id="inputEmail" name="username" class="form-control" placeholder="Email address" required autofocus>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
  <label for="inputPassword2" class="sr-only">Re-enter Password Again</label>
  <input type="password" id="inputPassword2" name="password2" class="form-control" placeholder="Re-enter Password" required>
  <label for="inputDisplayName" class="sr-only">Display Name</label>
  <input type="text" id="inputDisplayName" name="displayname" class="form-control" placeholder="Display Name" required>
  <button class="btn btn-lg btn-primary btn-block" type="submit" id="registerButton" name="register" value="Register">Register</button>
</form>