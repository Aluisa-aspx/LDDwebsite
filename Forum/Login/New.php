<?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);
 require_once('db file');
if (isset($_SESSION['ForumUsername'])) {
    header("Location: /Forum/");
    exit();
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Forum: create account</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
  <script src="/filter.js" type="text/javascript"></script>
  <script src="/filter2.js" type="text/javascript"></script>
  <script src="/Forum/CONFIG/username.js" type="text/javascript"></script>
<style>
#ERROR1, #ERROR2, #ERROR3, #ERROR4 {display:none;}
#ERROR1:target, #ERROR2:target, #ERROR3:target, #ERROR4:target {display:block;color:red;font-size:12px;}
</style>
</style>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
<h2>Welcome to the DGS forum!<span class="formatMenu">
<a href="/board/">DGS</a>
    </span>            
</h2>
        </div>
<div style="width:100%;text-align: center; font-size: 11px">
<a href="index.php">Log into an account...</a> <a href="/Forum/">Cancel</a> 
</div>
<div class="div">
<form action="signup_process.php" method="post">
            <label for="username">Desired Username:</label>
            <input type="text" id="username" name="username" required oninput="Username(event)" minlength="3" maxlength="20" />
            <br/><br/>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required oninput="Password(event)" minlength="8" maxlength="40" />
            <br/><br/>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required oninput="filterEnabled(event)" minlength="8" maxlength="40" />
            <br/><br/>

            <input type="checkbox" id="remember_me" name="remember_me" />
            <label for="remember_me">Remember Me</label>
            <br/><br/>

            <input type="submit" value="Sign Up" />
        </form>
</div>
<div id="ERROR1">Passwords don't match.</div>
<div id="ERROR2">Please enter text into the inputs.</div>
<div id="ERROR3">Possible error (common):Username already exists<br/>
Possible error (rare):Invalid Request, try again.</div>
    </div>
    <div id="Footer"></div>
</body>
</html>