<!DOCTYPE html>
<html lang="en">
<title>ASDF DB | Services</title>
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

<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-red w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="index.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Home</a>
    <a href="service_overview.php" class="w3-bar-item w3-button w3-padding-large w3-white">Services</a>
    <a href="sales_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Sales</a>
    <a href="clients_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Clients</a>
    <div class="w3-dropdown-hover w3-hide-small">
    <button class="w3-button w3-hide-small w3-padding-large w3-hover-white" title="COVID-19">COVID-19</button>
    <div class="w3-dropdown-content w3-card-4 w3-bar-block" style="width:300px">
      <a href="covid_dashboard.php" class="w3-bar-item w3-button">Overview</a>
      <a href="trace_infection.php" class="w3-bar-item w3-button">Contact tracing</a>
    </div>
    </div>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="index.php" class="w3-bar-item w3-button w3-padding-large">Home</a>
    <a href="service_overview.php" class="w3-bar-item w3-button w3-padding-large">Services</a>
    <a href="sales_overview.php" class="w3-bar-item w3-button w3-padding-large">Sales</a>
    <a href="clients_overview.php" class="w3-bar-item w3-button w3-padding-large">Clients</a>
    <a href="covid_dashboard.php" class="w3-bar-item w3-button w3-padding-large">COVID-19 | Overview</a>
    <a href="trace_infection.php" class="w3-bar-item w3-button w3-padding-large">COVID-19 | Tracing</a>
  </div>
</div>

<!-- Header -->
<header class="w3-container w3-red w3-center" style="padding:82px 128px">


    <div class="w3-container w3-red">
      <h2> Search Service Usage </h2>
    </div>
    <div class="w3-container w3-white w3-padding-16 w3-round">
      <form method="GET">
        <div class="w3-row-padding" style="margin:0 -16px;">
          <div class="w3-half w3-margin-bottom">
            <label><i class="fa fa-calendar-o"></i> From</label>
            <input class="w3-input w3-border" type="datetime-local" name="from_time" style="text-align: center">
          </div>
          <div class="w3-half">
            <label><i class="fa fa-calendar-o"></i> To</label>
            <input class="w3-input w3-border" type="datetime-local" name="to_time" style="text-align: center">
          </div>
        </div>
        <div class="w3-row-padding" style="margin:8px -16px;">
          <div class="w3-half w3-margin-bottom">
            <label>&euro; From</label>
            <input class="w3-input w3-border" type="text" placeholder="" name="min_cost" style="text-align: center">
          </div>
          <div class="w3-half w3-margin-bottom">
            <label>&euro; To</label>
            <input class="w3-input w3-border" type="text" placeholder="" name="max_cost" style="text-align: center">
          </div>
        </div>

        <div class="w3-row-padding" style="margin:8px -16px;">
          <div class="w3-half w3-margin-bottom">
            <label for="services"> Service type</label>
            <select class="w3-white w3-select w3-border" name="services" id="services">

                <?php
                    require 'sql_connector.php';

                    $sql = "SELECT * from Service;";

                    $result = $conn->query($sql);

                     echo sprintf("<option value=%s>%s</option>", "", "All");

                    if (mysqli_num_rows($result) > 0) {
                      while($row = $result->fetch_assoc()) {

                        $serv_ID = $row["service_ID"];
                        $serv_descritpion = $row["description"];

                      echo sprintf("<option value=%s>%s</option>", $serv_ID, $serv_descritpion);
                      //echo "".$place_descritpion." ".$enter_time." ".$last_name."<br>";
                      }
                    }
                    $conn->close();
                 ?>
            </select>
            </div>
         <div class="w3-half w3-margin-bottom">
           <br>
            <button class="w3-button w3-dark-grey" type="submit"><i class="fa fa-search w3-margin-right"></i> Search </button>
          </div>
          </div>
        </div>
      </form>
    </div>
</header>


<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-container">
    <h5>Services</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <th> Service type </th>
        <th> Client ID </th>
        <th> Time of use </th>
        <th> Cost </th>
      </tr>
      <?php

        require 'sql_connector.php';

        $where_clause = "WHERE 1=1"; // so that if no restrictions are set the query is still valid

        if(isset($_GET["from_time"]) && !empty($_GET['from_time'])){
            //echo "in";
            $from_timestamp = str_replace("T", " ", $_GET["from_time"]).":00";
            $where_clause .= " AND U.timestamp_of_use >= \"".$from_timestamp."\"";
        }

        if(isset($_GET["to_time"]) && !empty($_GET['to_time'])){
            //echo "in2";
            $to_timestamp = str_replace("T", " ", $_GET["to_time"]).":00";
            $where_clause .= " AND U.timestamp_of_use <= \"".$to_timestamp."\"";
        }

        if(isset($_GET["min_cost"]) && !empty($_GET['min_cost'])){
            //echo "in3";
            $where_clause .= " AND U.charge_amount >= ".$_GET['min_cost'];
        }

        if(isset($_GET["max_cost"]) && !empty($_GET['max_cost'])){
            //echo "in4";
            $where_clause .= " AND U.charge_amount <= ".$_GET['max_cost'];
        }

        if(isset($_GET["services"]) && (!empty($_GET['services']) || $_GET['services']=='0')){
            //echo "in5";
            $where_clause .= " AND S.service_ID = ".$_GET['services'];
        }

        $sql = "SELECT S.description, U.bracelet_UID, U.timestamp_of_use, U.charge_amount FROM Used as U join Service as S on (U.service_ID = S.service_ID) ".$where_clause.";"; // make the final query

        $result = $conn->query($sql);

        if (mysqli_num_rows($result) > 0) {
          while($row = $result->fetch_assoc()) {

            $serv_description = $row["description"];
            $bracelet_UID = $row["bracelet_UID"];
            $timestamp_of_use = $row["timestamp_of_use"];
            $charge_amount = $row["charge_amount"]." €";

          echo sprintf("<tr><td> %s </td><td> %s </td><td> %s </td><td> %s </td></tr>", $serv_description, $bracelet_UID, $timestamp_of_use, $charge_amount);
          //echo "".$place_descritpion." ".$enter_time." ".$last_name."<br>";
          }
        } else {
          echo "<tr> <td> - </td> <td> - </td><td> - </td> <td> - </td></tr>";
        }

        //echo "Query ".$sql."<br>";
      ?>
    </table>
    </div>
    <br> <br> <br>
  </div>
</div>

<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity">
 <div class="w3-xlarge w3-padding-32">
    <a href="https://github.com/SOCAH/ASDF_DB"><i class="fa fa-github w3-hover-opacity"></i> </a>
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
