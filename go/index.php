<?php
  session_start();
  if(isset($_SESSION['username'])) {
    header('Location: /board/');
    exit();
}
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Login</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
    <script src="/filter.js" type="text/javascript"></script>
</head>
<body>
<div id="Tablet">
 <div id="Header">
<h2>
DGS | Go in...
</h2>
 </div>
    <form action="login.php" method="post" id="LoginForm">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required oninput="filterEnabled(event)"><br/>
        <br/>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required oninput="filterEnabled(event)"><br/>
        
        <button type="submit" id="Login">Go in</button>
    </form>
<br/>
<br/>
<br/>
<div style="margin: 8px" class="stuff" id="Terms">
By logging in you will be accepting our <a href="#terms">Terms of Service</a>!
<br/>
<h2>
Rule 1:
</h2>
<p>
No spamming actions on the website, or server.
</p>
<h2>
Rule 2:
</h2>
<p>
No <b>nsfw content, gore or any content that disturbs or harasses someone</b>.
</p>
<h2>
Rule 3:
</h2>
<p>
Be cool, be friendly, talk with others.
</p>
<p>
<strong>Data notice/policy:</strong><em>We do not collect data from users.</em>  
</p>
    <br/>
<p style="color:red;text-align:center;font-weight:bold;font-size:14px;">
It's free!
</p>
<p>
Don't have a account? Then <a href="/signup/index.php" title="Sign up to DGS today">Sign Up</a>! 
</p>
<p>
<a href="/Forum/">Check out the Forums!</a>  
</p>
</div>
</div>
<div id="Footer">

</div>
</body>
</html>
