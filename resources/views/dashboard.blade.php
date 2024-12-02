<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Dashboard</h1>
        <form id="report-form">
            <div class="mb-3">
                <label for="fromDate" class="form-label">Start Date</label>
                <input type="date" id="fromDate" name="fromDate" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="toDate" class="form-label">End Date</label>
                <input type="date" id="toDate" name="toDate" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="merchant" class="form-label">Merchant ID</label>
                <input type="number" id="merchant" name="merchant" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="acquirer" class="form-label">Acquirer ID</label>
                <input type="number" id="acquirer" name="acquirer" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Get Report</button>
        </form>

        <form id="client-form" class="mt-5">
            <div class="mb-3">
                <label for="transactionId" class="form-label">Transaction ID</label>
                <input type="text" id="transactionId" name="transactionId" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-secondary">Get Client Information</button>
        </form>

        <div id="report-results" class="mt-4"></div>
        <div id="client-results" class="mt-4"></div>
    </div>

    <script>
        // Submit report form
        document.getElementById('report-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get dates and merchant/acquirer IDs
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const merchant = document.getElementById('merchant').value;
            const acquirer = document.getElementById('acquirer').value;

            const formattedFromDate = new Date(fromDate).toISOString().split('T')[0];  
            const formattedToDate = new Date(toDate).toISOString().split('T')[0];  

            // Send POST request to API
            fetch('/api/transactions/report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ fromDate: formattedFromDate, toDate: formattedToDate, merchant, acquirer }),
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('report-results').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            });
        });

        // Submit client form
        document.getElementById('client-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const transactionId = document.getElementById('transactionId').value;

            fetch('/api/client', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ transactionId }),
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('client-results').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            });
        });
    </script>
</body>
</html>
