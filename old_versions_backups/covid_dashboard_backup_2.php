<!DOCTYPE html>
<html lang="en">
<title>W3.CSS Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);



// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Work', 8],
  ['Eat', 2],
  ['TV', 4],
  ['Gym', 2],
  ['Sleep', 8]
]);

  var options = {'title':'', 'width':800, 'height':400,
   backgroundColor: 'transparent'};

  var chart = new google.visualization.PieChart(document.getElementById('most_accessed_places_piechart'));
  chart.draw(data, options);
}
</script>

<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Work', 16],
  ['Eat', 2],
  ['TV', 4],
  ['Gym', 2],
  ['Sleep', 8]
]);

  var options = {'title':'', 'width':800, 'height':400, backgroundColor: 'transparent'};

  var chart = new google.visualization.PieChart(document.getElementById('frequently_accessed_services_piechart'));
  chart.draw(data, options);
}
</script>

<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Work', 42],
  ['Eat', 2],
  ['TV', 4],
  ['Gym', 2],
  ['Sleep', 8]
]);

  var options = {'title':'', 'width':800, 'height':400, backgroundColor: 'transparent'};

  var chart = new google.visualization.PieChart(document.getElementById('services_used_by_most_people_piechart'));
  chart.draw(data, options);
}
</script>

<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-red w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="index.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Αρχική</a>
    <a href="service_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Υπηρεσίες</a>
    <a href="sales_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Πωλήσεις</a>
    <a href="clients_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Πελάτες</a>
    <div class="w3-dropdown-hover w3-hide-small">
    <button class="w3-button w3-padding-large w3-white" title="COVID-19">COVID-19</button>
    <div class="w3-dropdown-content w3-card-4 w3-bar-block" style="width:300px">
      <a href="covid_dashboard.php" class="w3-bar-item w3-button">Επισκόπηση</a>
      <a href="trace_infection.php" class="w3-bar-item w3-button">Ιχνηλάτηση κρούσματος</a>
    </div>
    </div>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="index.php" class="w3-bar-item w3-button w3-padding-large">Αρχική</a>
    <a href="service_overview.php" class="w3-bar-item w3-button w3-padding-large">Υπηρεσίες</a>
    <a href="sales_overview.php" class="w3-bar-item w3-button w3-padding-large">Πωλήσεις</a>
    <a href="clients_overview.php" class="w3-bar-item w3-button w3-padding-large">Πελάτες</a>
    <a href="covid_dashboard.php" class="w3-bar-item w3-button w3-padding-large">COVID-19 | Επισκόπηση</a>
    <a href="trace_infection.php" class="w3-bar-item w3-button w3-padding-large">COVID-19 | Ιχνηλάτηση</a>
  </div>
</div>

<!-- Header -->
<header class="w3-container w3-red w3-center" style="padding:82px 128px">

    <div class="w3-container w3-red">
      <h2><u>Επιλογή Ηλικιακής Ομάδας Και Χρονικού Διαστήματος Προς Επισκόπηση </u></h2>
    </div>

    <div class="w3-container w3-white w3-padding-16">
      <form action="covid_dashboard.php" method="get">
        <div class="w3-row-padding w3-margin-bottom">
          <div class="w3-half">
            <label for="age_group"> Ηλικιακή Ομάδα </label>
            <select class="w3-white w3-select w3-border" name="age_group" id="age_group">
              <option value="20-40">20-40</option>
              <option value="41-60">41-60</option>
              <option value="61+">61+</option>
            </select>

            <label for="timeframe"> Χρονικό Διάστημα</label>
            <select class="w3-white w3-select w3-border" name="timeframe" id="timeframe">
              <option value="last_year">Τον τελευαίο χρόνο</option>
              <option value="last_month">Τον τελευταίο μήνα</option>
            </select>
          </div>
          <div class="w3-half">
              <br> <br>
              <button class="w3-button w3-dark-grey" type="submit"><i class="fa fa-search w3-margin-right"></i> Ιχνηλάτηση </button>
          </div>
        </div>
      </form>
    </div>

</header>


<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
      <?php
      if($_GET){

        include "sql_connector.php";

        $sql="SELECT description_position as description, count(place_ID) as access_count
        from (Client as C natural join (Place as P natural join NotRoom) natural join Accessed as A on (P.ID = A.place_ID))
        where";

        if($_GET['timeframe'] == "last_year") {
          $sql .=" exit_timestamp >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)";
        } else {
          $sql .=" exit_timestamp >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)";
        }
        $sql .= " AND";
        if($_GET['age_group'] == "20-40") {
          $sql .= " C.age BETWEEN 20 AND 40";
        } else if($_GET['age_group'] == "41-60") {
          $sql .= " C.age BETWEEN 41 AND 60";
        } else {
          $sql .= " C.age >= 61";
        }
        $sql .= " group by place_ID";
        echo $sql;
        echo "<br>";
        $result = $conn->query($sql);
        print_r($result);
        if (mysqli_num_rows($result) > 0) {

        }
      }
      ?>
        <div class="w3-third w3-center w3-padding-64">
            <h3> Πιο πολυσύχναστοι χώροι</h3>
        </div>
        <div class="w3-twothird">
            <div id="most_accessed_places_piechart"></div>
        </div>
    </div>
</div>

<!-- Second Grid -->
<div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
    <div class="w3-content">
        <div class="w3-twothird">
            <div id="frequently_accessed_services_piechart"></div>
        </div>
        <div class="w3-third w3-center w3-padding-64">
            <h3> Συχνότερα χρησιμοποιούμενες υπηρεσίες</h3>
        </div>
    </div>
</div>


<!-- Third Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
        <div class="w3-third w3-center w3-padding-64">
            <h3> Υπηρεσίες που χρησιμοποιούνται από τους περισσότερους πελάτες</h3>
        </div>
        <div class="w3-twothird">
            <div id="services_used_by_most_people_piechart"></div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity">
 <div class="w3-xlarge w3-padding-32">
    <a href="https://github.com/Panmen/project_DB"><i class="fa fa-github w3-hover-opacity"></i> </a>
 </div>
 <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
</footer>

<script>
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>

</body>
</html>
