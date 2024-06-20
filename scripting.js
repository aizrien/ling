document.addEventListener('DOMContentLoaded', function() {
    // Example data visualization
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Monthly Sales',
                data: [12, 19, 3, 5, 2, 3, 7],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
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

    // Animation using anime.js
    anime({
        targets: 'h2, p, img',
        translateY: [-50, 0],
        opacity: [0, 1],
        duration: 1000,
        easing: 'easeOutBounce'
    });

    anime({
        targets: '.spec-button',
        opacity: [0, 1],
        translateX: [-30, 0],
        delay: anime.stagger(200, {start: 1000})
    });
});
