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
    <title>Forum: log into an account</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
  <script src="/filter.js" type="text/javascript"></script>
  
<style>
#ERROR1, #ERROR2, #ERROR3, #ERROR4 {display:none;}
#ERROR1:target, #ERROR2:target, #ERROR3:target, #ERROR4:target {display:black;color:red;font-size:12px;}
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
<a href="New.php">Sign Up...</a> <a href="/Forum/">Cancel</a>
</div>
<div class="div">
<form action="login_process.php" method="post">
            <label for="username">Your Username:</label>
            <input type="text" id="username" name="username" required oninput="filterEnabled(event)" />
            <br/><br/>

            <label for="password">Your Password:</label>
            <input type="password" id="password" name="password" required oninput="filterEnabled(event)" />
            <br/><br/>

            <input type="checkbox" id="remember_me" name="remember_me" oninput="filterEnabled(event)" />
            <label for="remember_me">Remember Me</label>
            <br/><br/>

            <input type="submit" value="Log in">
        </form>
<div id="ERROR1">Incorrect password</div>
<div id="ERROR2">User not found!</div>
<div id="ERROR3">Invalid Input.</div>
<div id="ERROR4">Invalid request method.</div>
</div>
    </div>
    <div id="Footer"></div>
</body>
</html>