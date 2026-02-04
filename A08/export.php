<?php
include("connect.php");

// Get filter parameters
$airlineFilter = $_GET['airline'] ?? '';
$aircraftFilter = $_GET['aircraft'] ?? '';
$departureFilter = $_GET['departureAirportCode'] ?? '';
$arrivalFilter = $_GET['arrivalAirportCode'] ?? '';
$creditCardTypeFilter = $_GET['creditCardType'] ?? '';
$sort = $_GET['sort'] ?? '';
$order = $_GET['order'] ?? 'ASC';

// Build query
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

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="flight_data_' . date('Y-m-d_H-i-s') . '.csv"');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

// Create output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, [
    'Flight Number',
    'Departure Airport',
    'Departure Date/Time',
    'Arrival Airport',
    'Arrival Date/Time',
    'Airline',
    'Aircraft Type',
    'Passenger Count',
    'Ticket Price',
    'Pilot Name',
    'Credit Card Type'
]);

// Add data rows
if (mysqli_num_rows($flightLogsResults) > 0) {
    while ($row = mysqli_fetch_assoc($flightLogsResults)) {
        fputcsv($output, [
            $row['flightNumber'],
            $row['departureAirportCode'],
            $row['departureDatetime'],
            $row['arrivalAirportCode'],
            $row['arrivalDatetime'],
            $row['airlineName'],
            $row['aircraftType'],
            $row['passengerCount'],
            $row['ticketPrice'],
            $row['pilotName'],
            $row['creditCardType']
        ]);
    }
}

// Close output stream
fclose($output);
exit();
?>
