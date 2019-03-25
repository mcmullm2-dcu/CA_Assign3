<?php namespace Assign3;

include 'code/header.php';

if (!isset($user) || !$user->canAccessPage()) {
    // If user isn't allowed to access this page, redirect back to home.
    header("Location: index.php");
}

$dashboard = $user->getPageDashboard();
if (!isset($dashboard)) {
    // If user doesn't have page information, redirect back to home.
    header("Location: index.php");
}

echo '<h2>'.$dashboard->name.'</h2>';
echo '<p>'.$dashboard->description.'</p>';

$reportDb = $db->GetReportDB();
$processTimes = $reportDb->listScheduledProcessTimes();
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Process', 'Scheduled Time']
        <?php
        foreach ($processTimes as $process) {
            echo ",['".$process->name."', ";
            echo ($process->time/100)."]\n";
        }
        ?>
    ]);

    // Optional; add a title and set the width and height of the chart
    var options = {'title':'Scheduled Processes, in minutes', 'width':550, 'height':400};

    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.BarChart(document.getElementById('chart'));
    chart.draw(data, options);
}
</script>
<div id="chart">
</div>

<?php include 'code/footer.php'
?>