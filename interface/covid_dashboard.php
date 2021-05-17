<!DOCTYPE html>
<html lang="en">
<title>ASDF DB | COVID-19 | Επισκόπηση</title>
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
      <h2>Επιλογή Ηλικιακής Ομάδας Και Χρονικού Διαστήματος Προς Επισκόπηση</h2>
    </div>

    <div class="w3-container w3-white w3-padding-16 w3-round">
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
              <button class="w3-button w3-dark-grey" type="submit"><i class="fa fa-search w3-margin-right"></i> Επισκόπηση </button>
          </div>
        </div>
      </form>
    </div>

</header>


<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
      <h5> Πιο πολυσύχναστοι χώροι</h5>
      <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-centered">
        <tr>
          <th> Χώρος </th>
          <th> Πλήθος επισκέψεων </th>
        </tr>
      <?php
      if($_GET){

        include "sql_connector.php";

        $sql="SELECT description_position as description, count(place_ID) as access_count
        from (Client as C left outer join (Place as P natural join NotRoom) natural join Accessed as A on (P.ID = A.place_ID))
        where";

        $where_clause="";
        if($_GET['timeframe'] == "last_year") {
          $where_clause .=" exit_timestamp >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)";
        } else {
          $where_clause .=" exit_timestamp >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)";
        }
        $where_clause .= " AND";
        if($_GET['age_group'] == "20-40") {
          $where_clause .= " C.age BETWEEN 20 AND 40";
        } else if($_GET['age_group'] == "41-60") {
          $where_clause .= " C.age BETWEEN 41 AND 60";
        } else {
          $where_clause .= " C.age >= 61";
        }
        $sql .= $where_clause;
        $sql .= " group by place_ID ORDER BY count(place_ID) DESC";

        $result = $conn->query($sql);
        $content = "";
        if (mysqli_num_rows($result) > 0) {
          while($row = $result->fetch_assoc()) {
            $description = $row['description'];
            $access_count = $row['access_count'];

            $content .= "<tr> <td style=\"vertical-align:middle;\">".$description;
            $content .= "</td> <td style=\"vertical-align:middle;\">".$access_count."</td> </tr>";
          }
        } else {
          echo "<tr> <td> - </td> <td> - </td></tr>";
        }
        echo $content;
      } else {
        echo "<tr> <td> - </td> <td> - </td></tr>";
      }
      ?>
      </table>
        <div class="w3-third w3-center w3-padding-64">

        </div>
        <div class="w3-twothird">
            <div id="most_accessed_places_piechart"></div>
        </div>
    </div>
</div>

<!-- Second Grid -->
<div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
  <div class="w3-content">
    <h5> Συχνότερα χρησιμοποιούμενες υπηρεσίες</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-centered">
      <tr>
        <th> Υπηρεσία </th>
        <th> Πλήθος χρήσεων </th>
      </tr>
    <?php
    if($_GET){

      include "sql_connector.php";

      $sql="SELECT S.description as description, count(U.service_ID) as service_use_count
      from Client as C natural join (Used as U join Service as S on (U.service_ID = S.service_ID))
      where";

      $where_clause="";
      if($_GET['timeframe'] == "last_year") {
        $where_clause .=" U.timestamp_of_use >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)";
      } else {
        $where_clause .=" U.timestamp_of_use >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)";
      }
      $where_clause .= " AND";
      if($_GET['age_group'] == "20-40") {
        $where_clause .= " C.age BETWEEN 20 AND 40";
      } else if($_GET['age_group'] == "41-60") {
        $where_clause .= " C.age BETWEEN 41 AND 60";
      } else {
        $where_clause .= " C.age >= 61";
      }
      $sql .= $where_clause;
      $sql .= " group by U.service_ID ORDER BY count(U.service_ID) DESC";

      $result = $conn->query($sql);

      $content = "";
      if (mysqli_num_rows($result) > 0) {
        while($row = $result->fetch_assoc()) {
          $description = $row['description'];
          $access_count = $row['service_use_count'];

          $content .= "<tr> <td style=\"vertical-align:middle;\">".$description;
          $content .= "</td> <td style=\"vertical-align:middle;\">".$access_count."</td> </tr>";
        }
      } else {
        echo "<tr> <td> - </td> <td> - </td></tr>";
      }
      echo $content;
      $conn->close();
    } else {
      echo "<tr> <td> - </td> <td> - </td></tr>";
    }
    ?>
    </table>
  </div>
</div>


<!-- Third Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <h5> Υπηρεσίες που χρησιμοποιούνται από τους περισσότερους πελάτες </h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white w3-centered">
      <tr>
        <th> Υπηρεσία </th>
        <th> Πλήθος διαφορετικών πελατών </th>
      </tr>
    <?php
    if($_GET){
      include "sql_connector.php";
      $sql="SELECT S.description as description, count(distinct(U.bracelet_UID)) as client_count
      from (Client as C natural join (Used as U join Service as S on (U.service_ID = S.service_ID)))
      where";

      $where_clause="";
      if($_GET['timeframe'] == "last_year") {
        $where_clause .=" U.timestamp_of_use >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)";
      } else {
        $where_clause .=" U.timestamp_of_use >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)";
      }
      $where_clause .= " AND";
      if($_GET['age_group'] == "20-40") {
        $where_clause .= " C.age BETWEEN 20 AND 40";
      } else if($_GET['age_group'] == "41-60") {
        $where_clause .= " C.age BETWEEN 41 AND 60";
      } else {
        $where_clause .= " C.age >= 61";
      }
      $sql .= $where_clause;
      $sql .= " group by S.service_ID ORDER BY count(distinct(U.bracelet_UID)) DESC";

      $result = $conn->query($sql);

      $content = "";
      if (mysqli_num_rows($result) > 0) {
        while($row = $result->fetch_assoc()) {
          $description = $row['description'];
          $access_count = $row['client_count'];

          $content .= "<tr> <td style=\"vertical-align:middle;\">".$description;
          $content .= "</td> <td style=\"vertical-align:middle;\">".$access_count."</td> </tr>";
        }
      } else {
        echo "<tr> <td> - </td> <td> - </td></tr>";
      }
      echo $content;
    } else {
      echo "<tr> <td> - </td> <td> - </td></tr>";
    }
    ?>
    </table>
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
