<!DOCTYPE html>
<html>
<head>
    <title>Real-time Data Display</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
        }
        .container {
            max-width: 1200px; /* Increased width to accommodate two tables */
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex; /* Added flex display */
        }
        .main-table {
            flex: 3; /* Give more space to the main table */
            margin-right: 10px; /* Margin between tables */
        }
        .side-table {
            flex: 1; /* Less space for the side table */
            margin-left: 10px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f2f2f2;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-table col-7">
            <h1>Health Data Display</h1>
            <table id="dataTable">
                <thead>
                    <tr>
                        <th>Heart Rate</th>
                        <th>Temperature</th>
                        <th>ECG Samples</th>
                        <th>Oxygen Saturation</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data rows will be inserted here dynamically -->
                </tbody>
            </table>
        </div>

        <div class="side-table col-4">
            <h1>Dosage Tracker</h1>
            <table id="doseTable">
                <thead>
                    <tr>
                        <th>Dose Taken</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dose rows will be inserted here dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="charts">
        <h4 class="text-center py-3">Heartrate Graph</h4>
        <canvas id="heartRateChart"></canvas>
        <h4 class="text-center py-3">Temperature Graph</h4>
        <canvas id="temperatureChart"></canvas>
        <h4 class="text-center py-3">ECG Graph</h4>
        <canvas id="ecgSamplesChart"></canvas>
        <h4 class="text-center py-3">Oxygen Saturation Graph</h4>
        <canvas id="oxygenSaturationChart"></canvas>
    </div>
    

    <script>
        $(document).ready(function() {
            // Function to fetch data from server and update tables
            function fetchData() {
                $.ajax({
                    url: '/api/retrieve',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Clear the existing table rows
                        $('#dataTable tbody').empty();
                        $('#doseTable tbody').empty();

                        // Check if response is an array and iterate over it
                        if (Array.isArray(response.data)) {
                            response.data.forEach(function(item) {
                                if (item.heartrate !== undefined &&
                                    item.temperature !== undefined &&
                                    item.ecg_samples !== undefined &&
                                    item.oxygen_saturation !== undefined) {

                                    var ecgSamples = JSON.stringify(item.ecg_samples);
                                   
                                    var newRow = '<tr>' +
                                        '<td>' + item.heartrate + '</td>' +
                                        '<td>' + item.temperature + '</td>' +
                                        '<td>' + ecgSamples + '</td>' +
                                        '<td>' + item.oxygen_saturation + '</td>' +
                                        '</tr>';

                                    $('#dataTable tbody').append(newRow);
                                } else {
                                    console.log('Invalid item format:', item);
                                }
                            });
                        } else {
                            console.log('Invalid response format:', response);
                        }

                        // Populate dose table with dose data
                        if (Array.isArray(response.doses)) {
                            response.doses.forEach(function(dose) {
                                var doseTaken = dose.dose_taken ? 'Yes' : 'No';
                                var createdAt = new Date(dose.created_at).toLocaleString();

                                var doseRow = '<tr>' +
                                    '<td>' + doseTaken + '</td>' +
                                    '<td>' + createdAt + '</td>' +
                                    '</tr>';

                                $('#doseTable tbody').append(doseRow);
                            });
                        } else {
                            console.log('Invalid dose response format:', response);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            // Initial fetch
            fetchData();

            // Fetch data every 5 seconds
            setInterval(fetchData, 5000);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function() {
            var heartRateData = [];
            var temperatureData = [];
            var ecgDatasets = [];
            var oxygenSaturationData = [];
            var timeLabels = [];
        
            // Initialize the charts
            var heartRateChart = new Chart(document.getElementById('heartRateChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                        label: 'Heart Rate',
                        data: heartRateData,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1
                    }]
                }
            });
        
            var temperatureChart = new Chart(document.getElementById('temperatureChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                        label: 'Temperature',
                        data: temperatureData,
                        borderColor: 'rgb(54, 162, 235)',
                        tension: 0.1
                    }]
                }
            });
        
            var ecgSamplesChart = new Chart(document.getElementById('ecgSamplesChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: [], // To be filled dynamically if needed
                    datasets: ecgDatasets
                }
            });
        
            var oxygenSaturationChart = new Chart(document.getElementById('oxygenSaturationChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                        label: 'Oxygen Saturation',
                        data: oxygenSaturationData,
                        borderColor: 'rgb(153, 102, 255)',
                        tension: 0.1
                    }]
                }
            });
        
            function fetchData() {
                $.ajax({
                    url: '/api/retrieve',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Clear the table and dataset arrays
                        $('#dataTable tbody').empty();
                        $('#doseTable tbody').empty();
                        
                        // Reset ECG datasets
                        ecgDatasets = [];
                        
                        if (Array.isArray(response.data)) {
                            response.data.forEach(function(item, index) {
                                if (item.heartrate !== undefined &&
                                    item.temperature !== undefined &&
                                    item.ecg_samples !== undefined &&
                                    item.oxygen_saturation !== undefined) {
        
                                    var newRow = '<tr>' +
                                        '<td>' + item.heartrate + '</td>' +
                                        '<td>' + item.temperature + '</td>' +
                                        '<td>' + JSON.stringify(item.ecg_samples) + '</td>' +
                                        '<td>' + item.oxygen_saturation + '</td>' +
                                        '</tr>';
        
                                    $('#dataTable tbody').append(newRow);
        
                                    heartRateData.push(item.heartrate);
                                    temperatureData.push(item.temperature);
                                    oxygenSaturationData.push(item.oxygen_saturation);
                                    timeLabels.push(new Date().toLocaleTimeString());
        
                                    // Parse ecg_samples from string to array
                                    try {
                                        var ecgSamplesArray = JSON.parse(item.ecg_samples);
        
                                        if (Array.isArray(ecgSamplesArray)) {
                                            ecgDatasets.push({
                                                label: 'ECG Sample ' + (index + 1),
                                                data: ecgSamplesArray,
                                                borderColor: 'rgb(' + (index * 30 % 255) + ', ' + (index * 60 % 255) + ', ' + (index * 90 % 255) + ')',
                                                fill: false,
                                                tension: 0.1
                                            });
                                        } else {
                                            console.log('Parsed ECG Samples is not an array:', ecgSamplesArray);
                                        }
                                    } catch (e) {
                                        console.log('Error parsing ECG Samples:', e);
                                    }
        
                                    // Limit the data array size
                                    if (heartRateData.length > 20) {
                                        heartRateData.shift();
                                        temperatureData.shift();
                                        oxygenSaturationData.shift();
                                        timeLabels.shift();
                                    }
                                } else {
                                    console.log('Invalid item format:', item);
                                }
                            });
        
                            // Set labels for ECG dataset if needed
                            // For simplicity, we assume that each ECG sample has the same length
                            if (ecgDatasets.length > 0 && ecgDatasets[0].data.length > 0) {
                                ecgSamplesChart.data.labels = Array.from({length: ecgDatasets[0].data.length}, (_, i) => i + 1);
                            }
        
                            // Update the charts
                            heartRateChart.update();
                            temperatureChart.update();
                            ecgSamplesChart.data.datasets = ecgDatasets;
                            ecgSamplesChart.update();
                            oxygenSaturationChart.update();
                        } else {
                            console.log('Invalid response format:', response);
                        }
        
                        if (Array.isArray(response.doses)) {
                            response.doses.forEach(function(dose) {
                                var doseTaken = dose.dose_taken ? 'Yes' : 'No';
                                var createdAt = new Date(dose.created_at).toLocaleString();
        
                                var doseRow = '<tr>' +
                                    '<td>' + doseTaken + '</td>' +
                                    '<td>' + createdAt + '</td>' +
                                    '</tr>';
        
                                $('#doseTable tbody').append(doseRow);
                            });
                        } else {
                            console.log('Invalid dose response format:', response);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        
            fetchData();
            setInterval(fetchData, 2000);
        });
        </script>
        
        
        
</body>
</html>
