// Auto-refresh data every 60 seconds
function refreshData() {
    fetch('../api/get_purpleair.php')
        .then(response => response.json())
        .then(data => {
            console.log('Data updated:', data);
            // Update your UI elements here
        });
}

setInterval(refreshData, 60000);