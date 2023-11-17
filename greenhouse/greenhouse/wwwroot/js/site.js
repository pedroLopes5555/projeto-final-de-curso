// Please see documentation at https://docs.microsoft.com/aspnet/core/client-side/bundling-and-minification
// for details on configuring this project to bundle and minify static web assets.

// Write your JavaScript code.
function getTemperatureAndHumidity() {
    $.ajax({
        url: '/automation/requestValues', // Replace with your server endpoint
        type: 'GET', // or 'POST', depending on your server-side implementation
        success: function (data) {
            console.log('AJAX request successful!', data);
            // Handle the response as needed
            writeTempHum(data);
        },
        error: function (error) {
            console.error('Error in AJAX request:', error);
        }
    });
}

// Set interval to make the AJAX request every 30 seconds
const interval = 10000; // 30 seconds
setInterval(getTemperatureAndHumidity, interval);


function writeTempHum(data) {

    document.getElementById('tds').innerText = `TDS : ${data.tds}`;
    document.getElementById('temperature').innerText = `Temperatura : ${data.temperature}`;

}