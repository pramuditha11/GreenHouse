<!--
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com/cloud-weather-station-esp32-esp8266/

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
-->
<?php
    include_once('esp-database.php');
    if (isset($_GET["readingsCount"])){
      $data = $_GET["readingsCount"];
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $readings_count = $_GET["readingsCount"];
    }
    // default readings count set to 20
    else {
      $readings_count = 20;
    }

    $last_reading = getLastReadings();
    $last_reading_temp = $last_reading["value1"];
    $last_reading_humi = $last_reading["value2"];
    $last_reading_time = $last_reading["reading_time"];

    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time - 1 hours"));
    // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time + 7 hours"));

    $min_temp = minReading($readings_count, 'value1');
    $max_temp = maxReading($readings_count, 'value1');
    $avg_temp = avgReading($readings_count, 'value1');

    $min_humi = minReading($readings_count, 'value2');
    $max_humi = maxReading($readings_count, 'value2');
    $avg_humi = avgReading($readings_count, 'value2');
?>

<!DOCTYPE html>
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <link rel="stylesheet" type="text/css" href="esp-style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
        <style>
            
      .topnav {overflow: hidden; background-color: #0c6980; color: white; font-size: 1.2rem;}
      .content {padding: 1px; }
      .card {background-color: white; box-shadow: 0px 0px 10px 1px rgba(140,140,140,.5); border: 1px solid #0c6980; border-radius: 15px;}
      .card.header {background-color: #0c6980; color: white; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-top-right-radius: 12px; border-top-left-radius: 12px;}
      .cards {max-width: 700px; margin: 0 auto; display: grid; grid-gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));}
      .reading {font-size: 1.3rem;}
      .packet {color: #bebebe;}
      
      .LEDColor {color: #183153;}
      
      /* ----------------------------------- Toggle Switch */
      .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
      }

      .switch input {display:none;}

      .sliderTS {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #D3D3D3;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 34px;
      }

      .sliderTS:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: #f7f7f7;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
      }

      input:checked + .sliderTS {
        background-color: #00878F;
      }

      input:focus + .sliderTS {
        box-shadow: 0 0 1px #2196F3;
      }

      input:checked + .sliderTS:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
      }

      .sliderTS:after {
        content:'OFF';
        color: white;
        display: block;
        position: absolute;
        transform: translate(-50%,-50%);
        top: 50%;
        left: 70%;
        font-size: 10px;
        font-family: Verdana, sans-serif;
      }

      input:checked + .sliderTS:after {  
        left: 25%;
        content:'ON';
      }

      input:disabled + .sliderTS {  
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
      }
      /* ----------------------------------- */
      @media screen and (max-width: 600px) {
    .table-container {
        overflow-x: auto;
    </style>
    
    </head>
    <header class="header">
        <h1>📊 GreenHouse DashBoard</h1>
        <form method="get">
            <input type="number" name="readingsCount" min="1" placeholder="Number of readings (<?php echo $readings_count; ?>)">
            <input type="submit" value="UPDATE">
        </form><p>Last reading: <span id="lastReadingTime"><?php echo $last_reading_time; ?></span></p>
        <script>
    function updateTime() {
        var now = new Date();
        var formattedTime = now.toLocaleString();
        document.getElementById('lastReadingTime').textContent = formattedTime;
    }

    // Update time every second
    setInterval(updateTime, 1000);
</script>
        <body style="margin: 0;">

        <div class="navbar" style="overflow: hidden; background-color: #333;">
                <div style="float: left;">
                    <a href="esp-weather-station.php" style="display: block; color: #f2f2f2; text-align: center; padding: 14px ;  text-decoration: none; font-size: 20px; " class="active"><i class="fa-solid fa-house"></i> &nbsp; Home &nbsp; &nbsp;|</a>
                </div>
                <div style="float: left;">
                    <a href="control.php" style="display: block; color: #f2f2f2; text-align: center; padding: 14px ;  text-decoration: none;font-size: 20px;"> <i class="fa-solid fa-fan"></i> &nbsp; Control &nbsp; &nbsp;|</a>
                </div>
                <div style="float: left;">
                    <a href="Table.php" style="display: block; color: #f2f2f2; text-align: center;  padding: 14px ; text-decoration: none;font-size: 20px;"><i class="fa-solid fa-table"></i>&nbsp; Table &nbsp; &nbsp;|</a>
                </div>
                <div style="float: left;">
                    <a href="chart.php" style="display: block; color: #f2f2f2; text-align: center;padding: 14px ;  text-decoration: none;font-size: 20px;"> <i class="fa-solid fa-chart-simple"></i> &nbsp; Chart&nbsp; &nbsp;|</a>
                </div>
            
                <div style="float: right;">
                    <a href="index.php" style="display: block; color: #f2f2f2; text-align: center; padding: 14px 10px; text-decoration: none;font-size: 20px;"> <i class="fa-solid fa-right-from-bracket"></i> &nbsp; LOGOUT&nbsp;</a>
                </div>
            </div>
    </header>
<body style="background: linear-gradient(to bottom, #92fe9d 0%, #00c9ff 100%);">
   


<h2 style="text-align: center;"> View Latest  Readings</h2>


<?php
echo '<div class="table-container">';

echo '<div>
        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date">
        
        <label for="end-date">End Date:</label>
        <input type="date" id="end-date">
        
        <button style="background-color: #4CAF50; border: none; color: white; padding: 5px 10px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 10px; transition-duration: 0.4s;" onclick="filterTable()">
    Filter
</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<button class="export-button" style ="padding: 7px 12px;" onclick="exportTableToExcel(\'tableReadings\', \'GreenHouse-data\')">
<i class="fa-solid fa-download fa-bounce fa-lg"></i>
Export 
</button>

      </div>';


      
echo '<table cellspacing="5" cellpadding="5" id="tableReadings">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sensor</th>
                <th>Location</th>
                <th>Temperature</th>
                <th>Humidity</th>
                <th>Pressure</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>';

$result = getAllReadings($readings_count);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_sensor = $row["sensor"];
        $row_location = $row["location"];
        $row_value1 = $row["value1"];
        $row_value2 = $row["value2"];
        $row_value3 = $row["value3"];
        $row_reading_time = $row["reading_time"];

        echo '<tr>
                <td>' . $row_id . '</td>
                <td>' . $row_sensor . '</td>
                <td>' . $row_location . '</td>
                <td>' . $row_value1 . '</td>
                <td>' . $row_value2 . '</td>
                <td>' . $row_value3 . '</td>
                <td>' . $row_reading_time . '</td>
            </tr>';
    }
    echo '</tbody>';
    echo '</table>';
    $result->free();
}
echo '</div>';
?>
<script>
function filterTable() {
    const startDateValue = document.getElementById('start-date').value;
    const endDateValue = document.getElementById('end-date').value;

    const startDate = startDateValue ? new Date(startDateValue) : null;
    const endDate = endDateValue ? new Date(endDateValue) : null;

    const table = document.getElementById('tableReadings');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Clear the table body
    tbody.innerHTML = '';

    // Filter and append rows that match the date criteria
    rows.forEach(row => {
        const dateCell = row.querySelector('td:last-child').textContent;
        const rowDate = new Date(dateCell);

        if ((!startDate || rowDate >= startDate) && (!endDate || rowDate <= endDate)) {
            tbody.appendChild(row);
        }
    });
}
</script>


   </div>



<br><br><br><br>



<script>
    function exportTableToExcel(tableReadings, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableReadings);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';
        
        // Create download link element
        downloadLink = document.createElement("a");
        
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        
            // Setting the file name
            downloadLink.download = filename;
            
            //triggering the function
            downloadLink.click();
        }
    }
</script>


<script>
    var value1 = <?php echo $last_reading_temp; ?>;
    var value2 = <?php echo $last_reading_humi; ?>;
    setTemperature(value1);
    setHumidity(value2);

    function setTemperature(curVal){
    	//set range for Temperature in Celsius -5 Celsius to 38 Celsius
    	var minTemp = -5.0;
    	var maxTemp = 38.0;
        //set range for Temperature in Fahrenheit 23 Fahrenheit to 100 Fahrenheit
    	//var minTemp = 23;
    	//var maxTemp = 100;

    	var newVal = scaleValue(curVal, [minTemp, maxTemp], [0, 180]);
    	$('.gauge--1 .semi-circle--mask').attr({
    		style: '-webkit-transform: rotate(' + newVal + 'deg);' +
    		'-moz-transform: rotate(' + newVal + 'deg);' +
    		'transform: rotate(' + newVal + 'deg);'
    	});
    	$("#temp").text(curVal + ' ºC');
    }

    function setHumidity(curVal){
    	//set range for Humidity percentage 0 % to 100 %
    	var minHumi = 0;
    	var maxHumi = 100;

    	var newVal = scaleValue(curVal, [minHumi, maxHumi], [0, 180]);
    	$('.gauge--2 .semi-circle--mask').attr({
    		style: '-webkit-transform: rotate(' + newVal + 'deg);' +
    		'-moz-transform: rotate(' + newVal + 'deg);' +
    		'transform: rotate(' + newVal + 'deg);'
    	});
    	$("#humi").text(curVal + ' %');
    }

    function scaleValue(value, from, to) {
        var scale = (to[1] - to[0]) / (from[1] - from[0]);
        var capped = Math.min(from[1], Math.max(from[0], value)) - from[0];
        return ~~(capped * scale + to[0]);
    }
</script>
<footer>
<div style="position: fixed; left: 0; bottom: 0; width: 100%;height: 40px; background-color: #333; color: white; text-align: center; padding: 1px 5px 1px 5px;">
    <p><b>Copyright © 2024 All rights reserved.</b> | Lanka Minerals & Chemicals (Pvt) Ltd</p>
</div></footer>
</body>
</html>
