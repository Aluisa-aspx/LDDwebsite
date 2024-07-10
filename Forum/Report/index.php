<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Reporting</title>
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
            <h3>Report Content</h3>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reportType = $_POST["report_type"];
    $reportedItemId = $_POST["reported_item_id"];
    $reportReason = $_POST["report_reason"];
    $servername = "servername";
    $username = "usr";
    $password = "password";
    $dbname = "dbname";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO ForumReports (ReportType, ReportedItemId, ReportReason) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $reportType, $reportedItemId, $reportReason);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    header("Location: /Forum/");
    exit();
}
?>


            <form action="" method="post">
                <label for="report_type">Report Type:</label>
                <select id="report_type" name="report_type" required>
                    <?php
                    $defaultOption = '';
                    $typeParameter = isset($_GET['type']) ? $_GET['type'] : '';
                    switch ($typeParameter) {
                        case 'profile':
                            $defaultOption = 'user';
                            break;
                        case 'thread':
                            $defaultOption = 'thread';
                            break;
                        default:
                            $defaultOption = 'reply';
                            break;
                    }
                    ?>
                    <option value="reply" <?php echo ($defaultOption === 'reply') ? 'selected' : ''; ?>>Reply</option>
                    <option value="user" <?php echo ($defaultOption === 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="thread" <?php echo ($defaultOption === 'thread') ? 'selected' : ''; ?>>Thread</option>
                </select>
                <br/>

                <label for="reported_item_id">Reported Item ID:</label>
                        <?php
        $reportedItemId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
        echo '<input type="text" id="reported_item_id" name="reported_item_id" value="' . $reportedItemId . '" required>';
        ?>
                <br/>

                <label for="report_reason">Report Reason:</label>
                <textarea id="report_reason" name="report_reason" required></textarea>
                <br/>

                <input type="submit" value="Submit Report">
            </form>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>
