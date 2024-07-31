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
</body>
</html>
