<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 60%; margin: auto;">
        <canvas id="productChart"></canvas>
    </div>

    <script>
        // Fetch data from PHP backend
        fetch('backend.php')
            .then(response => response.json())
            .then(data => {
                // Prepare labels and data arrays
                const categoryLabels = data.map(item => item.category_name);
                const numberOfProducts = data.map(item => item.product_count);

                // Create the chart
                const ctx = document.getElementById('productChart').getContext('2d');
                const productChart = new Chart(ctx, {
                    type: 'bar',  // Chart type
                    data: {
                        labels: categoryLabels,  // Category labels from backend
                        datasets: [{
                            label: 'Number of Products per Category',
                            data: numberOfProducts,  // Number of products from backend
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
</body>
</html>
