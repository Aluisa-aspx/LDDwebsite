<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('db file');
include('User.php file (?)');
global $mysqli;
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /Forum/index.php");
    exit();
}

$userId = $_GET['id'];
$userQuery = "SELECT * FROM ForumUsers WHERE Id = ?";
$stmt = $mysqli->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();

if (!$userResult || $userResult->num_rows == 0) {
    header("Location: /Forum/index.php");
    exit();
}

$user = $userResult->fetch_assoc();
$latestThreadsQuery = "SELECT ThreadId, Title, CreatedAt
                      FROM ForumThreads
                      WHERE Author = ?
                      ORDER BY CreatedAt DESC
                      LIMIT 5";
$latestThreadsStmt = $mysqli->prepare($latestThreadsQuery);
$latestThreadsStmt->bind_param("s", $user['ForumUsername']);
$latestThreadsStmt->execute();
$latestThreadsResult = $latestThreadsStmt->get_result();
if (!$latestThreadsResult) {
    $latestThreads = [];
} else {
    $latestThreads = $latestThreadsResult->fetch_all(MYSQLI_ASSOC);
}
$totalThreadsQuery = "SELECT COUNT(*) AS total FROM ForumThreads WHERE Author = ?";
$totalThreadsStmt = $mysqli->prepare($totalThreadsQuery);
$totalThreadsStmt->bind_param("s", $user['ForumUsername']);
$totalThreadsStmt->execute();
$totalThreadsResult = $totalThreadsStmt->get_result();
if ($totalThreadsResult) {
    $totalThreadsRow = $totalThreadsResult->fetch_assoc();
    $totalThreads = $totalThreadsRow['total'];
} else {
    $totalThreads = 0;
}

$totalPostsQuery = "SELECT COUNT(*) AS total FROM ForumReplies WHERE Author = ?";
$totalPostsStmt = $mysqli->prepare($totalPostsQuery);
$totalPostsStmt->bind_param("s", $user['ForumUsername']);
$totalPostsStmt->execute();
$totalPostsResult = $totalPostsStmt->get_result();
if ($totalPostsResult) {
    $totalPostsRow = $totalPostsResult->fetch_assoc();
    $totalPosts = $totalPostsRow['total'];
} else {
    $totalPosts = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title><?php echo htmlspecialchars($user['ForumUsername']); ?>'s Profile</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/filter.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/status.js" type="text/javascript"></script>
</head>

<body>
    <div id="Tablet">
        <div id="Header">
            <h2><span class="formatMenu"><a href="/board/">DGS</a></span></h2>
        </div>
        <div class="div">
<a href="/Forum/">Go back...</a>
            <h3><?php echo htmlspecialchars($user['ForumUsername']); ?>'s Profile</h3>
            <p>Total Threads: <?php echo $totalThreads; ?></p>
            <p>Total Posts: <?php echo $totalPosts; ?></p>
            <p>
<a style="float:right;" href="/Forum/Report/?type=profile&id=<?php echo $userId; ?>"><img class="right" src="/Forum/CONFIG/report.png" alt="Report" />Report Abuse</a>
            </p>
            <h4>Latest Threads:</h4>
            <ul>
                <?php foreach ($latestThreads as $thread) : ?>
                    <li><a href="/Forum/ShowPost.php?id=<?php echo $thread['ThreadId']; ?>"><?php echo htmlspecialchars($thread['Title']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div id="Footer"></div>
</body>

</html>
