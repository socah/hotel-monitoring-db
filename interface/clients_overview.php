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
<!--<head>

</head>-->
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-red w3-card w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="index.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Αρχική</a>
    <a href="service_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Υπηρεσίες</a>
    <a href="sales_overview.php" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">Πωλήσεις</a>
    <a href="clients_overview.php" class="w3-bar-item w3-button w3-padding-large w3-white">Πελάτες</a>
    <div class="w3-dropdown-hover w3-hide-small">
    <button class="w3-button w3-hide-small w3-padding-large w3-hover-white" title="COVID-19">COVID-19</button>
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
<header class="w3-container w3-red w3-center" style="padding:64px 8px">
<br>
    <div class="w3-container w3-red">
      <h2><i></i> <u>Επισκόπηση Πελατών</u> </h2>
    </div>
    <!--<div class="w3-container w3-white w3-padding-16">

    </div>-->
</header>


<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-container">
    <h5>Πελάτες</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-centered">
      <tr>
        <th>Όνομα Πελάτη</th>
        <th>Επώνυμο Πελάτη</th>
        <th>Ηλικία</th>
        <th>Ηλ. Ταχυδρομείο</th>
        <th>Τηλέφωνα Επικοινωνίας</th>
        <th>Υπηρεσίες</th>
      </tr>
      <?php
        include "sql_connector.php";
        $sql = "SELECT * FROM Client_Info";
        $result = $conn->query($sql);
        $content = "";
        if (mysqli_num_rows($result) > 0) {
          while($row = $result->fetch_assoc()) {

            $first_name = $row["first_name"];
            $last_name = $row["last_name"];
            $age = $row["age"];
            $emails = explode(',', $row["emails"]);
            $phones = explode(',', $row["phones"]);
            $services = explode(',', $row["registered_to"]);

            $content .= "<tr> <td style=\"vertical-align:middle;\">".$first_name;
            $content .= "</td> <td style=\"vertical-align:middle;\">".$last_name;
            $content .= "</td> <td style=\"vertical-align:middle;\">".$age."</td>";
            $content .= "<td> <ul style=\"list-style-type:none;\">";
            foreach ($emails as $email) {
              $content .= "<li>".$email."</li>";
            }
            $content .= "</ul> </td> <td> <ul style=\"list-style-type:none;\">";
            foreach ($phones as $phone) {
              $content .= "<li>".$phone."</li>";
            }
            $content .= "</ul> </td> <td> <ul style=\"list-style-type:none;\">";
            foreach ($services as $service) {
              $content .= "<li>".$service."</li>";
            }
            $content .= "</ul> </td> </tr>";
          }
        } else {
          echo "0 results";
        }
        $conn->close();
        echo $content;
      ?>
    </table>
    <br>

  </div>

  <br> <br> <br> <br>
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
