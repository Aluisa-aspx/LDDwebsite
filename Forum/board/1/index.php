<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('CONFIG > index.php');
include('User.php');

$threadsPerPage = 12;
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($current_page - 1) * $threadsPerPage;
$threadsQuery = "SELECT ForumThreads.ThreadId, ForumThreads.Title, ForumThreads.Author AS ThreadAuthor, ForumThreads.CreatedAt,
                        MAX(ForumReplies.CreatedAt) AS LastReplyTime, ForumReplies.Author AS LastReplyAuthor,
                        COUNT(ForumReplies.ReplyId) AS ReplyCount
                FROM ForumThreads
                LEFT JOIN ForumReplies ON ForumThreads.ThreadId = ForumReplies.ThreadId
                WHERE ForumThreads.BoardId = 1
                GROUP BY ForumThreads.ThreadId
                ORDER BY ForumThreads.ThreadId DESC
                LIMIT $threadsPerPage OFFSET $offset";


$threadsResult = $mysqli->query($threadsQuery);

$totalThreadsQuery = "SELECT COUNT(*) AS total FROM ForumThreads WHERE BoardId = 1";
$totalThreadsResult = $mysqli->query($totalThreadsQuery);
$totalThreads = ($totalThreadsResult) ? $totalThreadsResult->fetch_assoc()['total'] : 0;
$totalPages = ceil($totalThreads / $threadsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Forum: General Discussion</title>
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
    <div id="Tablet" style="height:700px;">
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
            <input value="Create Thread" type="button" onclick="window.location.href='/Forum/board/1/createThread.php'" />
            <span> </span>
            <input value="Go Back" type="button" onclick="window.location.href='/Forum/'" />
            <br/>
            <table style="border-collapse: collapse; border: solid 1px #000; width: 400px; margin: 10px auto; background: #eee;" cellpadding="6">
                <thead style="background: steelblue; border-bottom: solid 1px #000;">
                    <th style="color: #fff;" width="50">Thread</th>
                    <th style="color: #fff;border-left: solid 1px #fff;border-right: solid 1px #fff;" width="20">Replies</th>
                    <th style="color: #fff;" width="40">Last Reply</th>
                </thead>
                <tbody>
                    <?php
                    while ($thread = $threadsResult->fetch_assoc()) {
                        $lastReplyTimeFormatted = ($thread['LastReplyTime']) ? date('D M j, g:i A', strtotime($thread['LastReplyTime'])) : 'N/A';
                        $threadTitle = (!is_null($thread['Title'])) ? htmlspecialchars($thread['Title']) : 'N/A';

                        echo '<tr>
                                <td style="color: #333;" width="50"><a id="thread" href="/Forum/ShowPost.php?id=' . $thread['ThreadId'] . '" title="' . $threadTitle . '">' . $threadTitle . '</a></td>
                                <td style="color: #333;border-left: solid 1px #fff;border-right: solid 1px #fff;" width="20"><span id="repliesCounter">' . $thread['ReplyCount'] . '</span></td>
                                <td style="color: #333;font-size:8px;text-align:center;" width="40">' . $lastReplyTimeFormatted . '<br/>' . htmlspecialchars($thread['LastReplyAuthor'] ?? '') . '</td>
                            </tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div id="PageSelector" style="background-color: #ccc; margin: 0; padding: 4px;">
                <?php
                echo '<span>Page ' . $current_page . ' of ' . $totalPages . '</span>';
                if ($totalPages > 1) {
                    echo '<span style="float:right;">Go to page: ';
                    if ($current_page > 1) {
                        echo '<a href="?page=' . ($current_page - 1) . '">Previous</a>, ';
                    }
                    $ellipsisDisplayed = false;
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($i == $current_page) {
                            echo '<strong>' . $i . '</strong>';
                            if ($i < $totalPages && $i < $current_page + 2) {
                                echo ' ,';
                            }
                        } elseif (
                            $i == 1 ||
                            $i == $totalPages ||
                            ($i >= $current_page - 2 && $i <= $current_page + 2)
                        ) {
                            if ($i != $current_page - 1) {
                                echo '<a href="?page=' . $i . '">' . $i . '</a> ,';
                            }
                            if ($i < $totalPages && $i < $current_page + 2) {
                                echo '';
                            }
                            $ellipsisDisplayed = false;
                        } elseif (!$ellipsisDisplayed) {
                            echo ' ... ';
                            $ellipsisDisplayed = true;
                        }
                    }
                    if ($current_page < $totalPages - 1) {
                        echo ' <a href="?page=' . ($current_page + 1) . '">Next</a>';
                    }
                    echo '</span>';
                }
                ?>
            </div>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>
