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
    <title>PUP Airport Flight Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="img/favicon.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <!-- Modern Header -->
    <header>
        <h1><i class="fas fa-plane"></i> PUP Airport Flight Dashboard</h1>
        <p>Advanced Flight Data Management System</p>
    </header>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($flightLogsResults); ?></div>
            <div class="stat-label">Total Flights</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($airlineResults); ?></div>
            <div class="stat-label">Airlines</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($aircraftResults); ?></div>
            <div class="stat-label">Aircraft Types</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($departureResults); ?></div>
            <div class="stat-label">Airports</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-container">
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Airline Distribution</h3>
            <canvas id="airlineChart"></canvas>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Aircraft Types</h3>
            <canvas id="aircraftChart"></canvas>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Monthly Trends</h3>
            <canvas id="trendsChart"></canvas>
        </div>
    </div>

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
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear Filters
                </a>
                <button type="button" class="btn btn-success" onclick="exportData()">
                    <i class="fas fa-download"></i> Export CSV
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <div class="results-container">
        <div class="results-header">
            <h3 class="results-title">
                <i class="fas fa-list"></i> Flight Results
            </h3>
            <div class="results-info">
                <?php echo mysqli_num_rows($flightLogsResults); ?> flights found
            </div>
        </div>
        <div class="table-responsive">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Airline Distribution Pie Chart
    const airlineCtx = document.getElementById('airlineChart').getContext('2d');
    new Chart(airlineCtx, {
        type: 'pie',
        data: {
            labels: ['Philippine Airlines', 'Cebu Pacific', 'AirAsia', 'PAL Express'],
            datasets: [{
                data: [35, 30, 20, 15],
                backgroundColor: [
                    '#2563eb',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#f1f5f9',
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Aircraft Types Bar Chart
    const aircraftCtx = document.getElementById('aircraftChart').getContext('2d');
    new Chart(aircraftCtx, {
        type: 'bar',
        data: {
            labels: ['A320', 'B737', 'A330', 'B777', 'Q400'],
            datasets: [{
                label: 'Flights',
                data: [45, 38, 25, 20, 15],
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#334155'
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Monthly Trends Line Chart
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Flights',
                data: [120, 145, 165, 180, 195, 210],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#334155'
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

function exportData() {
    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    
    // Create export URL
    const exportUrl = `export.php?${urlParams.toString()}`;
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'flight_data.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Add loading states
document.querySelector('.filter-form').addEventListener('submit', function() {
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 2000);
});
</script>
</body>
</html>
