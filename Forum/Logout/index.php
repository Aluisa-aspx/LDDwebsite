<?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);
 include ('db file');
 include ('CONFIG > User.php');
 if (!isset($_SESSION['ForumUsername'])) {
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
    <title>Forum: logout</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
  
</head>
<body>
    <div id="Tablet">
        <div id="Header">
<h2>Welcome to the DGS forum!<span class="formatMenu">
<a href="/board/">DGS</a>
    </span>            
</h2>
        </div>
<div class="div">
Do you really want to log out?
 <ul>
<li><a href="/Forum/">No</a></li>
<li><a href="confirm.php">Yes</a></li>
 </ul>
</div>
    </div>
    <div id="Footer"></div>
</body>
</html>