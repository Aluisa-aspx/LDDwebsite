<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: /board/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
            <h2> DGS | Signup!
            </h2>
        </div>
<div class="div">
If you want to sign in then <a href="mailto:nilb.serv00.net?subject=Sign%20Up&body=Name%3A%20Your_name%0D%0ALang%3A%20Lang_you_speak%20(has%20to%20be%20either%20eng%20or%20pt-br)%0D%0A">
  contact us</a>!
  
</div>
    </div>
    <div id="Footer"></div>
</body>
</html>
