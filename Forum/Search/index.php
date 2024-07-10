<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db file');
include('CONFIG > User.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $threadsPerPage = 12;
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($current_page - 1) * $threadsPerPage;

    $searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
    $condition = empty($searchQuery) ? '1' : "ForumThreads.Title LIKE '%$searchQuery%'";

    $threadsQuery = "SELECT ForumThreads.ThreadId, ForumThreads.Title, ForumThreads.Author, ForumThreads.CreatedAt,
                            COUNT(ForumReplies.ReplyId) AS ReplyCount
                    FROM ForumThreads
                    LEFT JOIN ForumReplies ON ForumThreads.ThreadId = ForumReplies.ThreadId
                    WHERE ForumThreads.BoardId = 1 AND $condition
                    GROUP BY ForumThreads.ThreadId
                    ORDER BY ForumThreads.LastUpdated DESC
                    LIMIT $threadsPerPage OFFSET $offset";

    $threadsResult = $mysqli->query($threadsQuery);

    if (!$threadsResult) {
        echo "Error: " . $mysqli->error;
        exit;
    }
} else {
    echo "Invalid request method";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Search</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/filter.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/status.js" type="text/javascript"></script>
    <style>
        #thread {
            max-width: 230px;
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
            <h2><span class="formatMenu"><a href="/board/">DGS</a></span></h2>
        </div>
        <div style="width: 100%; text-align: center; font-size: 11px">
            <?php
            if (isLoggedIn()) {
                $username = getUsername();
                echo "<b>Logged in as $username</b><a href='/Forum/Logout/' style='float:right;margin-right:5px;'>Log out</a>";
            } else {
                echo '<a style="padding:0 10px;" href="/Forum/Login/New.php">Sign Up</a><a style="padding:0 10px;" href="/Forum/Login/">Log in</a>';
            }
            ?>
        </div>
        <div class="div">
            <form method="get" action="/Forum/Search/index.php">
                <input type="text" name="q" placeholder="Search threads..." value="<?php echo htmlspecialchars($searchQuery); ?>" />
                <input type="submit" value="Search" />
            </form>
<table style="border-collapse: collapse; border: solid 1px #000; width: 400px; margin: 10px auto; background: #eee;" cellpadding="6">
    <thead style="background: steelblue; border-bottom: solid 1px #000;">
        <th style="color: #fff;" width="50">Thread</th>
        <th style="color: #fff;border-left: solid 1px #fff;border-right: solid 1px #fff;" width="20">Replies</th>
        <th style="color: #fff;" width="40">Author</th>
    </thead>
    <tbody>
        <?php
        while ($thread = $threadsResult->fetch_assoc()) {
            echo '<tr>
                    <td style="color: #333;" width="50"><a id="thread" href="/Forum/ShowPost.php?id=' . $thread['ThreadId'] . '" title="' . htmlspecialchars($thread['Title']) . '">' . htmlspecialchars($thread['Title']) . '</a></td>
                    <td style="color: #333;border-left: solid 1px #fff;border-right: solid 1px #fff;" width="20"><span id="repliesCounter">' . $thread['ReplyCount'] . '</span></td>
                    <td style="color: #333;" width="40">' . htmlspecialchars($thread['Author']) . '</td>
                </tr>';
        }
        ?>
    </tbody>
</table>

            <div style="text-align: center; margin-top: 10px; background: #ccc;">
                <?php
                $totalThreadsQuery = "SELECT COUNT(*) AS total FROM ForumThreads WHERE BoardId = 1 AND $condition";
                $totalThreadsResult = $mysqli->query($totalThreadsQuery);
                $totalThreads = ($totalThreadsResult) ? $totalThreadsResult->fetch_assoc()['total'] : 0;
                $totalPages = ceil($totalThreads / $threadsPerPage);
                for ($i = 1; $i <= $totalPages; $i++) {
                    $isActive = ($i == $current_page) ? 'style="font-weight: bold;"' : '';
                    echo '<a ' . $isActive . ' href="/Forum/Search/search.php?page=' . $i . '&q=' . urlencode($searchQuery) . '">' . $i . '</a>';
                    echo ' ';
                }
                ?>
            </div>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>
