<?php
 error_reporting(E_ALL);
ini_set('display_errors', 1);
 include ('db file');
 include ('User.php');
global $mysqli;
$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
if (!isset($_SESSION['ForumUsername'])) {
    header("Location: /Forum/Login/");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'], $_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $boardId = 1; 
        $author = $_SESSION['ForumUsername'];
        $insertQuery = "INSERT INTO ForumThreads (BoardId, Title, Content, Author)
                        VALUES ($boardId, '$title', '$content', '$author')";

        if ($mysqli->query($insertQuery)) {
            $newThreadId = $mysqli->insert_id;
            header("Location: /Forum/ShowPost.php?id=$newThreadId");
            exit();
        } else {
            $errorMessage = urlencode($mysqli->error);
            header("Location: /Forum/createThread.php?error=$errorMessage");
            exit();
        }
    }
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Forum | 1 | Create thread...</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
  <script src="/Forum/CONFIG/filter.js" type="text/javascript"></script>
   <script src="/Forum/CONFIG/status.js" type="text/javascript"></script>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
<h2><span class="formatMenu">
<a href="/board/">DGS</a>
    </span>            
</h2>
        </div>
<div class="div">
            <h3>Create a New Thread</h3>
            <form action="createThread.php" method="post">
                <label for="title">Thread Title:</label>
                <input type="text" id="title" name="title" required oninput="filterEnabled(event)" minlength="2" maxlength="100" />
<br/><br/>
                <label style="margin-bottom:5px;display:block;" for="content">Thread Content:</label>
<textarea id="content" name="content" required style="display:block;min-width:400px;max-width:400px;min-height:200px;max-height:200px;margin: 0 auto;"  oninput="filterThread(event)" minlength="2" maxlength="250"></textarea>
<br/><br/>
                <input type="hidden" name="board" value="1">

                <input type="submit" value="Create Thread">
            </form>
</div>
    </div>
    <div id="Footer"></div>
</body>
</html>