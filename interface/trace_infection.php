<!DOCTYPE html>
<html lang="en">
<title>ASDF DB | COVID-19 | Tracing</title>
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
    <a href="service_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Services</a>
    <a href="sales_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Sales</a>
    <a href="clients_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Clients</a>
    <div class="w3-dropdown-hover w3-hide-small">
    <button class="w3-button w3-padding-large w3-white" title="COVID-19">COVID-19</button>
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
    <h2><i></i> Contact Tracing </h2>
  </div>
  <div class="w3-container w3-white w3-padding-16 w3-round">
    <form method="GET">
      <div class="w3-row-padding" style="margin:0 -16px;">
        <div class="w3-center w3-margin-bottom">
          <label>Bracelet UID of the infected guest</label>
          <input class="w3-input w3-border" type="text" placeholder="" name="bracelet_UID" style="text-align: center" required>
        </div>
      </div>
      <div class="w3-row-padding" style="margin:8px -16px;">
       <div class="w3-center w3-margin-bottom">
          <button class="w3-button w3-dark-grey" type="submit"><i class="fa fa-search w3-margin-right"></i> Trace </button>
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
    <h5>Places visited by the guest</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <th> Place </th>
        <th> From </th>
        <th> To</th>
      </tr>

      <?php
      if (isset($_GET['bracelet_UID'])) {

          $bracelet_UID = $_GET['bracelet_UID'];

          include "sql_connector.php";

          $sql = "SELECT P.description_position, A.enter_timestamp, A.exit_timestamp FROM Accessed as A join Place as P on (A.place_ID = P.ID) WHERE bracelet_UID = ".strval($bracelet_UID).";";

          $result = $conn->query($sql);

          //echo "query made<br>";

          if (mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()) {

              $place_descritpion = $row["description_position"];
              $enter_time = $row["enter_timestamp"];
              $last_name = $row["exit_timestamp"];


            echo sprintf("<tr><td>%s</td> <td>%s</td> <td>%s</td></tr>", $place_descritpion, $enter_time, $last_name);
            //echo "".$place_descritpion." ".$enter_time." ".$last_name."<br>";
            }
          } else {
            echo sprintf("<tr><td>%s</td> <td>%s</td> <td>%s</td></tr>", "-", "-", "-");
          }
          $conn->close();
      }else{
          echo sprintf("<tr><td>%s</td> <td>%s</td> <td>%s</td></tr>", "-", "-", "-");
      }
      ?>

    </table>
  </div>
  <br> <br> <br>
</div>
</div>

<!-- Second Grid -->
<div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-container">
    <h5>Possibly exposed guests</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <th> Client ID</th>
        <th> First name</th>
        <th> Last name</th>
      </tr>

      <?php
      if (isset($_GET['bracelet_UID'])) {

          $bracelet_UID = $_GET['bracelet_UID'];

          include "sql_connector.php";

          $sql = "SELECT bracelet_UID, first_name, last_name
          from Client
          where bracelet_UID in
          (select Other.bracelet_UID
          from (SELECT bracelet_UID, place_ID, enter_timestamp, TIMESTAMPADD(HOUR,1,exit_timestamp) as up_to FROM Accessed WHERE bracelet_UID =".strval($bracelet_UID).") as ILL, Accessed as Other
          where Other.place_ID = ILL.place_ID and (
              (ILL.enter_timestamp <= Other.exit_timestamp and Other.enter_timestamp <= ILL.up_to)
          ) and ILL.bracelet_UID <> Other.bracelet_UID)";

          $result = $conn->query($sql);

          //echo "query made<br>";

          if (mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()) {

              $bracelet_UID = $row["bracelet_UID"];
              $first_name = $row["first_name"];
              $last_name = $row["last_name"];


            echo sprintf("<tr><td>%s</td> <td>%s</td> <td>%s</td></tr>", $bracelet_UID, $first_name, $last_name);
            //echo "".$place_descritpion." ".$enter_time." ".$last_name."<br>";
            }
          } else {
            echo sprintf("<tr><td>%s</td> <td>%s</td> <td>%s</td></tr>", "-", "-", "-");
          }
          $conn->close();
      }else{
          echo sprintf("<tr><td>%s</td> <td>%s</td> <td>%s</td></tr>", "-", "-", "-");
      }
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
