<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('db2 file');
include('CONFIG > User.php');

$usernameColumn = 'username';
$username = isset($_SESSION[$usernameColumn]) ? $_SESSION[$usernameColumn] : null;

$allowedAdmins = ['Aluisa', 'Victor'];
$isAdmin = in_array($username, $allowedAdmins);

if (!$isAdmin) {
    header("Location: /Forum/");
    exit();
}

if (!isset($mysqli)) {
    echo "Database connection not available.";
    exit();
}

$latestReportsQuery = "SELECT Id AS ReportId, ReportType, ReportedItemId, ReportReason, ReportedAt
                      FROM ForumReports
                      ORDER BY ReportedAt DESC
                      LIMIT 50";
$latestReportsResult = $mysqli->query($latestReportsQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/all.css" type="text/css" />
    <title>Latest Reports</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" type="text/javascript"></script>
    <script src="/res/js/load.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/filter.js" type="text/javascript"></script>
    <script src="/Forum/CONFIG/status.js" type="text/javascript"></script>
    <style>
        table {font-size: 13px;}   
    </style>
</head>

<body>
    <div id="Tablet">
        <div id="Header">
            <h2><span class="formatMenu"><a href="/board/">DGS</a></span></h2>
        </div>
        <div class="div">
            <h3>Latest Reports</h3>
            <?php if ($latestReportsResult && $latestReportsResult->num_rows > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Report Type</th>
                            <th>Report Item ID</th>
                            <th>Report Reason</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($report = $latestReportsResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $report['ReportId']; ?></td>
                                <td><?php echo $report['ReportType']; ?></td>
                                <td><?php echo $report['ReportedItemId']; ?></td>
                                <td><?php echo $report['ReportReason']; ?></td>
                                <td><?php echo $report['ReportedAt']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No reports found.</p>
            <?php endif; ?>
        </div>
    </div>
    <div id="Footer"></div>
</body>
</html>
