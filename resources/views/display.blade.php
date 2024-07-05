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
                        // Update table with retrieved data
                        var ecgSamples = JSON.stringify(response.ecg_samples);
                        var doseTaken = response.dose_taken ? 'Yes' : 'No';

                        var newRow = '<tr>' +
                            '<td>' + response.heartrate + '</td>' +
                            '<td>' + response.temperature + '</td>' +
                            '<td>' + ecgSamples + '</td>' +
                            '<td>' + doseTaken + '</td>' +
                            '</tr>';

                        $('#dataTable tbody').html(newRow);
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
