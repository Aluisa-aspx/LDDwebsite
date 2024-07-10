<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('db file');
global $mysqli;
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$membersQuery = "SELECT Id, ForumUsername, LastSeen FROM ForumUsers";
$membersResult = $mysqli->query($membersQuery);

if (!$membersResult) {
    die("Error retrieving members: " . $mysqli->error);
}

$members = $membersResult->fetch_all(MYSQLI_ASSOC);

function getPostCount($userId) {
    global $mysqli;
    $totalPostsQuery = "SELECT COUNT(*) AS total FROM ForumReplies WHERE Author = ?";
    $totalPostsStmt = $mysqli->prepare($totalPostsQuery);

    if (!$totalPostsStmt) {
        die("Error preparing statement: " . $mysqli->error);
    }

    $totalPostsStmt->bind_param("s", $userId);
    $totalPostsStmt->execute();

    $totalPostsResult = $totalPostsStmt->get_result();

    if (!$totalPostsResult) {
        die("Error executing statement: " . $totalPostsStmt->error);
    }

    $totalPostsRow = $totalPostsResult->fetch_assoc();

    if ($totalPostsRow === null) {
        die("Error fetching result: " . $totalPostsResult->error);
    }

    return $totalPostsRow['total'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Member List</title>
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
            <h3>Member List</h3><span><a href="/Forum/">Go back...</a></span>
            <?php
            if (!empty($members)) {
                echo '<table style="border: solid 1px #000; border-collapse: collapse; width: 400px;" cellpadding="3">
                        <thead>
                            <tr style="background:steelblue;border-bottom:solid 1px #000;text-align:left;color:#fff;">
                                <th>#</th>
                                <th>Username</th>
                                <th>Last Seen</th>
                                <th>Posts</th>
                            </tr>
                        </thead>
                        <tbody>';
                foreach ($members as $member) {
                    echo '<tr>
                            <td style="text-align:center;"><b>' . $member['Id'] . '</b></td>
                            <td><a href="/Forum/User.php?id=' . $member['Id'] . '">' . htmlspecialchars($member['ForumUsername']) . '</a></td>
                            <td>' . formatLastSeen($member['LastSeen']) . '</td>
                            <td style="text-align:center;">' . getPostCount($member['Id']) . '</td>
                        </tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p>No members found.</p>';
            }
            ?>

        </div>
    </div>
    <div id="Footer"></div>

    <?php
    function formatLastSeen($lastSeen){return date("d M Y", strtotime($lastSeen));}
    ?>
</body>

</html>
