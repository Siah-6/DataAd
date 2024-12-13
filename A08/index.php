<?php
include("connect.php");

$airlineFilter = $_GET['airline'] ?? '';
$aircraftFilter = $_GET['aircraft'] ?? '';
$departureFilter = $_GET['departureAirportCode'] ?? '';
$arrivalFilter = $_GET['arrivalAirportCode'] ?? '';
$creditCardTypeFilter = $_GET['creditCardType'] ?? '';
$sort = $_GET['sort'] ?? '';
$order = $_GET['order'] ?? 'ASC';

$flightLogsQuery = "SELECT * FROM flightLogs";

$filters = [];
if ($airlineFilter != '')
    $filters[] = "airlineName='$airlineFilter'";
if ($aircraftFilter != '')
    $filters[] = "aircraftType='$aircraftFilter'";
if ($departureFilter != '')
    $filters[] = "departureAirportCode='$departureFilter'";
if ($arrivalFilter != '')
    $filters[] = "arrivalAirportCode='$arrivalFilter'";
if ($creditCardTypeFilter != '')
    $filters[] = "creditCardType='$creditCardTypeFilter'";

if (count($filters) > 0) {
    $flightLogsQuery .= " WHERE " . implode(" AND ", $filters);
}

if ($sort != '') {
    $flightLogsQuery .= " ORDER BY $sort $order";
}

$flightLogsResults = executeQuery($flightLogsQuery);

$airlineQuery = "SELECT DISTINCT(airlineName) FROM flightLogs";
$airlineResults = executeQuery($airlineQuery);

$aircraftQuery = "SELECT DISTINCT(aircraftType) FROM flightLogs";
$aircraftResults = executeQuery($aircraftQuery);

$departureQuery = "SELECT DISTINCT(departureAirportCode) FROM flightLogs";
$departureResults = executeQuery($departureQuery);

$arrivalQuery = "SELECT DISTINCT(arrivalAirportCode) FROM flightLogs";
$arrivalResults = executeQuery($arrivalQuery);

$creditCardTypeQuery = "SELECT DISTINCT(creditCardType) FROM flightLogs";
$creditCardTypeResults = executeQuery($creditCardTypeQuery);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PUP Airport Flight Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="img/favicon.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <header>
        <h1>PUP Airport Flight Logs</h1>
        <p>Data Admin Dashboard</p>
    </header>

    <!-- Filter Section -->
    <div class="filter-container">
        <form class="filter-form">
            <h4 class="filter-title">Filter Flight Logs</h4>
            <div class="filter-row">
                <div class="filter-item">
                    <label for="airlineSelect">Airline</label>
                    <select id="airlineSelect" name="airline" class="form-select">
                        <option value="">Any</option>
                        <?php
                        while ($airlineRow = mysqli_fetch_assoc($airlineResults)) {
                            echo "<option value='{$airlineRow['airlineName']}'" . ($airlineFilter == $airlineRow['airlineName'] ? " selected" : "") . ">{$airlineRow['airlineName']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="aircraftSelect">Aircraft</label>
                    <select id="aircraftSelect" name="aircraft" class="form-select">
                        <option value="">Any</option>
                        <?php
                        while ($aircraftRow = mysqli_fetch_assoc($aircraftResults)) {
                            echo "<option value='{$aircraftRow['aircraftType']}'" . ($aircraftFilter == $aircraftRow['aircraftType'] ? " selected" : "") . ">{$aircraftRow['aircraftType']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-item">
                    <label for="departureSelect">Departure Airport</label>
                    <select id="departureSelect" name="departureAirportCode" class="form-select">
                        <option value="">Any</option>
                        <?php
                        while ($departureRow = mysqli_fetch_assoc($departureResults)) {
                            echo "<option value='{$departureRow['departureAirportCode']}'" . ($departureFilter == $departureRow['departureAirportCode'] ? " selected" : "") . ">{$departureRow['departureAirportCode']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="arrivalSelect">Arrival Airport</label>
                    <select id="arrivalSelect" name="arrivalAirportCode" class="form-select">
                        <option value="">Any</option>
                        <?php
                        while ($arrivalRow = mysqli_fetch_assoc($arrivalResults)) {
                            echo "<option value='{$arrivalRow['arrivalAirportCode']}'" . ($arrivalFilter == $arrivalRow['arrivalAirportCode'] ? " selected" : "") . ">{$arrivalRow['arrivalAirportCode']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-item">
                    <label for="creditCardTypeSelect">Credit Card Type</label>
                    <select id="creditCardTypeSelect" name="creditCardType" class="form-select">
                        <option value="">Any</option>
                        <?php
                        while ($creditCardRow = mysqli_fetch_assoc($creditCardTypeResults)) {
                            echo "<option value='{$creditCardRow['creditCardType']}'" . ($creditCardTypeFilter == $creditCardRow['creditCardType'] ? " selected" : "") . ">{$creditCardRow['creditCardType']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="sort">Sort By</label>
                    <select id="sort" name="sort" class="form-select">
                        <option value="">None</option>
                        <option value="flightNumber" <?php if ($sort == "flightNumber") echo "selected"; ?>>Flight Number</option>
                        <option value="departureDatetime" <?php if ($sort == "departureDatetime") echo "selected"; ?>>Departure Date</option>
                        <option value="arrivalDatetime" <?php if ($sort == "arrivalDatetime") echo "selected"; ?>>Arrival Date</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="order">Order</label>
                    <select id="order" name="order" class="form-select">
                        <option value="ASC" <?php if ($order == "ASC") echo "selected"; ?>>Ascending</option>
                        <option value="DESC" <?php if ($order == "DESC") echo "selected"; ?>>Descending</option>
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <table class="table">
            <thead>
            <tr>
                <th>Flight #</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Airline</th>
                <th>Aircraft</th>
                <th>Passengers</th>
                <th>Ticket Price</th>
                <th>Pilot</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($flightLogsResults) > 0) {
                while ($row = mysqli_fetch_assoc($flightLogsResults)) {
                    echo "<tr>
                    <td>{$row['flightNumber']}</td>
                    <td>{$row['departureAirportCode']} ({$row['departureDatetime']})</td>
                    <td>{$row['arrivalAirportCode']} ({$row['arrivalDatetime']})</td>
                    <td>{$row['airlineName']}</td>
                    <td>{$row['aircraftType']}</td>
                    <td>{$row['passengerCount']}</td>
                    <td>{$row['ticketPrice']}</td>
                    <td>{$row['pilotName']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No records found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
