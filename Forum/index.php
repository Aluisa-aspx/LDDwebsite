<?php
include('db file');
include('user.php file');

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $allowedUsernames = ['Aluisa', 'Victor'];
    $isAllowedUser = in_array($username, $allowedUsernames);
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $coins = $row['coins'];
    } else {
        echo "User not found";
        $isAllowedUser = false;
    }
} else {
    $isAllowedUser = false;
}
$boards = array(
    array('id' => 1, 'name' => 'General Discussion', 'description' => 'General discussion about DGS.'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Forum: index</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/status.js" type="text/javascript"></script>
    <style>
        #Thread {
            max-width: 193.33px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            cursor: pointer;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div id="Tablet">
        <div id="Header">
            <h2>Welcome to the DGS forum!<span class="formatMenu"><a href="/board/">DGS</a></span></h2>
        </div>
        <div style="width:100%;text-align: center; font-size: 11px">
            <?php
            if (isLoggedIn()) {
                $username = getUsername();
                echo "Welcome back, $username!<a href='/Forum/Logout/' style='float:right;margin-right:5px;'>Log out</a>
                  <a href='Members.php' style='float:right;margin-right:5px;'>Memberlist</a>";
            } else {
                echo '<a style="padding:0 10px;" href="/Forum/Login/New.php">Sign Up</a><a style="padding:0 10px;" href="/Forum/Login/">Log in</a>
                  <span>&nbsp;</span><a href="Members.php" style="float:right;margin-right:5px;">Memberlist</a>';
            }
            ?>
        </div>
        <div class="div">
            <table style="border-collapse: collapse; border: solid 1px #000; width: 400px; margin: 0 auto; background: #eee;" cellpadding="6">
                <thead style="background: steelblue; border-bottom: solid 1px #000;">
                    <th style="color: #fff;" width="50">Board name</th>
                    <th style="color: #fff;" width="20">Threads</th>
                    <th style="color: #fff;" width="40">Latest Thread</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($boards as $board) {
                        $threadsCountQuery = "SELECT COUNT(*) AS total FROM ForumThreads WHERE BoardId = {$board['id']}";
                        $threadsCountResult = $mysqli->query($threadsCountQuery);
                        $totalThreads = ($threadsCountResult) ? $threadsCountResult->fetch_assoc()['total'] : 0;
                        $latestPostQuery = "SELECT ThreadId, Title, Author, CreatedAt
                                FROM ForumThreads
                                WHERE BoardId = {$board['id']}
                                ORDER BY LastUpdated DESC
                                LIMIT 1";
                        $latestPostResult = $mysqli->query($latestPostQuery);
                        $latestPost = ($latestPostResult) ? $latestPostResult->fetch_assoc() : null;
                        ?>
                        <tr>
                            <td style="color: #333;" width="50"><a href="/Forum/board/<?php echo $board['id']; ?>/"><?php echo $board['name']; ?></a><br /><span style="font-size: 10px;"><?php echo $board['description']; ?></span></td>
                            <td style="color: #333; vertical-align: top;" width="20"><span id="postCounter"><?php echo $totalThreads; ?></span></td>
                            <td style="color: #333; vertical-align: top;" width="40">
                                <?php
                                if ($latestPost && isset($latestPost['ThreadId'])) {
                                    echo '<a id="Thread" href="/Forum/ShowPost.php?id=' . $latestPost['ThreadId'] . '" title="' . htmlspecialchars($latestPost['Title']) . '">';
                                    echo htmlspecialchars($latestPost['Title']) . '</a>';
                                } else {
                                    echo 'No posts yet.';
                                }
                                ?>
                                <img src="thread.png" alt="Go to icon"/>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
<div style="width: 200px; border: solid 1px #000; margin: 20px 0 20px 10px; padding: 8px;">
            <form method="get" action="/Forum/Search/index.php">
                <input type="text" name="q" style="width: 60%;" />
                <input type="submit" value="Search" />
            </form>          
</div>
            <?php if ($isAllowedUser) { ?>
                <div style="text-align:center;margin:10px 0">
                    <a href="/Forum/Report/reports.php">Reports</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>
