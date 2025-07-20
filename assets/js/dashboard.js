/**
 * Awesome Dokan Dashboard Scripts
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        console.log('Awesome Dokan script loaded!');

        // Check if the Chart.js library and the canvas element are available
        if (typeof Chart !== 'undefined' && $('#salesChart').length > 0) {
            const ctx = document.getElementById('salesChart').getContext('2d');

            // Example Data - you should fetch this via a WordPress AJAX call
            const salesData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Sales',
                    data: [65, 59, 80, 81, 56, 55, 40], // Replace with dynamic data
                    fill: true,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 4,
                }]
            };

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: salesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide the legend for a cleaner look
                        }
                    }
                }
            });
        }

    });

})(jQuery);
