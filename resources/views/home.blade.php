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
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        #data {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Real-time Data Display</h1>
        
        <div id="data">
            <!-- Data will be displayed here -->
            <table id="dataTable">
                <thead>
                    <tr>
                        <th>Heart Rate</th>
                        <th>Temperature</th>
                        <th>ECG Samples</th>
                        {{-- <th>R-Value</th> --}}
                        <th>Oxygen Saturation</th>
                        <th>Dose Taken</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data rows will be inserted here dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Function to fetch data from server and update table
            function fetchData() {
                $.ajax({
                    url: '/api/retrieve',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Clear the existing table rows
                        $('#dataTable tbody').empty();

                        // Check if response is an array and iterate over it
                        if (Array.isArray(response)) {
                            response.forEach(function(item) {
                                if (item.heartrate !== undefined &&
                                    item.temperature !== undefined &&
                                    item.ecg_samples !== undefined &&
                                    // item.r_value !== undefined &&
                                    item.oxygen_saturation !== undefined &&
                                    item.dose_taken !== undefined) {

                                    var ecgSamples = JSON.stringify(item.ecg_samples);
                                    var doseTaken = item.dose_taken ? 'Yes' : 'No';

                                    var newRow = '<tr>' +
                                        '<td>' + item.heartrate + '</td>' +
                                        '<td>' + item.temperature + '</td>' +
                                        '<td>' + ecgSamples + '</td>' +
                                        // '<td>' + item.r_value + '</td>' +
                                        '<td>' + item.oxygen_saturation + '</td>' +
                                        '<td>' + doseTaken + '</td>' +
                                        '</tr>';

                                    $('#dataTable tbody').append(newRow);
                                } else {
                                    console.log('Invalid item format:', item);
                                }
                            });
                        } else {
                            console.log('Invalid response format:', response);
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
            setInterval(fetchData, 1000);
        });
    </script>
</body>
</html>
