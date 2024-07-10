<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('db file');
include('user.php file');
global $mysqli;
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /Forum/index.php");
    exit();
}

$threadId = $_GET['id'];
$threadQuery = "SELECT ForumThreads.ThreadId, ForumThreads.BoardId, ForumThreads.Title, ForumThreads.Content, ForumThreads.Author, ForumThreads.CreatedAt, ForumThreads.LastUpdated, ForumUsers.OnlineStatus
                FROM ForumThreads
                LEFT JOIN ForumUsers ON ForumThreads.Author = ForumUsers.ForumUsername
                WHERE ForumThreads.ThreadId = $threadId";

$threadResult = $mysqli->query($threadQuery);

if (!$threadResult || $threadResult->num_rows == 0) {
    header("Location: /Forum/.php");
    exit();
}

$thread = $threadResult->fetch_assoc();
$repliesQuery = "SELECT * FROM ForumReplies WHERE ThreadId = $threadId";
$repliesResult = $mysqli->query($repliesQuery);
$loggedInUserId = isset($_SESSION['ForumUserId']) ? $_SESSION['ForumUserId'] : null;
$isAllowedToDelete = ($loggedInUserId && $loggedInUserId == 2);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_type'])) {
        if ($_POST['action_type'] === 'add_reply') {
            if (isset($_POST['reply_content'])) {
                $replyContent = $_POST['reply_content'];
                $replyAuthor = isset($_SESSION['ForumUsername']) ? $_SESSION['ForumUsername'] : 'Guest';
                $insertReplyQuery = "INSERT INTO ForumReplies (ThreadId, Content, Author)
                                    VALUES ($threadId, '$replyContent', '$replyAuthor')";
                $mysqli->query($insertReplyQuery);
                header("Location: /Forum/ShowPost.php?id=$threadId");
                exit();
            }
        } elseif ($_POST['action_type'] === 'delete_thread') {
            $deleteRepliesQuery = "DELETE FROM ForumReplies WHERE ThreadId = $threadId";
            $mysqli->query($deleteRepliesQuery);
            $deleteThreadQuery = "DELETE FROM ForumThreads WHERE ThreadId = $threadId";
            $mysqli->query($deleteThreadQuery);
            header("Location: /Forum/");
            exit();
        }
    }
}

$totalRepliesQuery = "SELECT COUNT(*) AS total FROM ForumReplies WHERE ThreadId = $threadId";
$totalRepliesResult = $mysqli->query($totalRepliesQuery);
$totalReplies = ($totalRepliesResult) ? $totalRepliesResult->fetch_assoc()['total'] : 0;
$repliesPerPage = 4;
$totalPages = ceil($totalReplies / $repliesPerPage);
$currentPage = (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $totalPages) ? $_GET['page'] : 1;
$offset = ($currentPage - 1) * $repliesPerPage;
$paginatedRepliesQuery = "SELECT ForumReplies.*, ForumUsers.OnlineStatus
                          FROM ForumReplies
                          LEFT JOIN ForumUsers ON ForumReplies.Author = ForumUsers.ForumUsername
                          WHERE ForumReplies.ThreadId = $threadId
                          LIMIT $repliesPerPage OFFSET $offset";

$paginatedRepliesResult = $mysqli->query($paginatedRepliesQuery);

function formatCreatedAt($createdAt) {
    return date('M j, g:i A', strtotime($createdAt));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title><?php echo htmlspecialchars($thread['Title']); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/filter.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/status.js" type="text/javascript"></script>
    <meta content="<?php echo htmlspecialchars($thread['Title']); ?>" property="og:title" />
    <meta content="<?php echo nl2br(htmlspecialchars($thread['Content'])); ?>" property="og:description" />
    <?php
    $validColors = array("#ff0800", "#5203fc", "#f8cd07");
    $randomColor = $validColors[array_rand($validColors)];
    echo '<meta content="' . $randomColor . '" data-react-helmet="true" name="theme-color" />';
    ?>
    <style>
        #threadTable div {
            display: inline-block;
        }

        #Thread {
            max-width: 193.33px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            cursor: help;
            display: inline-block;
        }

        #threadTable span {
            word-break: break-word;
        }

        #Reply {
            vertical-align: top;
        }

        @media only screen and (max-width: 1000px) {
            #Reply {
                vertical-align: bottom;
                bottom: 0;
                right: 0;
            }
        }
    </style>
</head>

<body>
    <div id="Reply" style="width: 245px; border: solid 1px #000; padding: 8px; margin: 7px; auto; position: absolute; float: left; background: #eee;">
        <?php
        if (isset($_SESSION['ForumUsername'])) {
            echo '<form action="/Forum/ShowPost.php?id=' . $threadId . '" method="post">
                        <label for="reply_content">Reply:</label><br/>
                        <textarea id="reply_content" name="reply_content" required oninput="filterThread(event)" minlength="2" maxlength="150" style="max-width:235px;max-height:60px;"></textarea>
                        <br/>
                        <input type="hidden" name="action_type" value="add_reply">
                        <input type="submit" value="Submit Reply">
                    </form>';
        } else {
            echo '<p><b>Please log in to post a reply.</b></p>';
        }
        ?>
    </div>
    <div id="Tablet">
        <div id="Header">
            <h2><span class="formatMenu">
                    <a href="/board/">DGS</a>
                </span></h2>
        </div>
        <div class="div">
            <div style="width: 100%; text-align: center; margin: 5px 0;"><a href="/Forum/">Go back...</a></div>
            <div style="width: 100%; text-align: center; margin: 5px 0;"><a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>"><?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></a></div>
            <table id="threadTable" cellpadding="4" style="width: 475px; margin:0 auto; border-collapse: collapse; border: solid 1px #000; background: #eee;">
                <tbody>
                    <tr>
                        <td style="width:30%;display:block;float:left;">
                            <span><?php echo htmlspecialchars($thread['Author']); ?></span>
                            <br/>
                            <span><?php echo ($thread['OnlineStatus'] == 1 ? 'Online' : 'Offline'); ?></span>
                        </td>
                        <td style="width:60%;display:block;float:left;">
    <span id="Thread" title="<?php echo htmlspecialchars($thread['Title']); ?>">
        <b><?php echo htmlspecialchars($thread['Title']); ?></b>
    </span>
    <br/>
    <?php echo nl2br(htmlspecialchars($thread['Content'])); ?>
<br/>
                              <span style="float:right;">
<a href="/Forum/Report/?type=thread&id=<?php echo $threadId; ?>"><img class="right" src="/Forum/CONFIG/report.png" alt="Report" />Report Abuse</a>
    </span>

    <br/>
    <span style="font-size:10px; float: right;">Created at: <?php echo formatCreatedAt($thread['CreatedAt']); ?></span>
</td>
                        <td style="width:20%;display:block;float:left;">
                            <?php
                            if ($isAllowedToDelete) {
                                echo '<form action="" method="post">
                                    <input type="hidden" name="action_type" value="delete_thread">
                                    <input type="submit" value="Delete Thread">
                                </form>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $paginatedRepliesQuery = "SELECT ForumReplies.*, ForumUsers.OnlineStatus
                          FROM ForumReplies
                          LEFT JOIN ForumUsers ON ForumReplies.Author = ForumUsers.ForumUsername
                          WHERE ForumReplies.ThreadId = $threadId
                          LIMIT $repliesPerPage OFFSET $offset";

                    $paginatedRepliesResult = $mysqli->query($paginatedRepliesQuery);
                    while ($reply = $paginatedRepliesResult->fetch_assoc()) {
                        echo '<tr style="border-top: solid 1px #000;">
            <td style="width:30%;display:block;float:left;">
                <span>' . htmlspecialchars($reply['Author']) . '</span>
                <br/>
                <span>' . (isset($reply['OnlineStatus']) ? ($reply['OnlineStatus'] ? 'Online' : 'Offline') : '') . '</span>
            </td>
            <td style="width:60%;display:block;float:left;">
                <span>' . nl2br(htmlspecialchars($reply['Content'])) . '</span>
    <br/>
<a style="float:right;" href="/Forum/Report/?type=reply&id=' . $reply['ReplyId'] . '"><img class="right" src="/Forum/CONFIG/report.png" alt="Report" />Report Abuse</a>
                <br/>
                <span style="font-size:10px; float: right;">Created at: ' . formatCreatedAt($reply['CreatedAt']) . '</span>
            </td>
            <td style="width:20%;display:block;float:left;"></td>
        </tr>';
                    }
                    echo '<div style="text-align: center; margin-top: 10px; background: #ccc;">';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $isActive = ($i == $currentPage) ? 'style="font-weight: bold;"' : '';
                        echo '<a ' . $isActive . ' href="/Forum/ShowPost.php?id=' . $threadId . '&page=' . $i . '">' . $i . '</a>';
                        echo ' ';
                    }
                    echo '</div>';
                    ?>
                </tbody>
            </table>

        </div>
    </div>
    <div id="Footer"></div>
</body>

</html>
