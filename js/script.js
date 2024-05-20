function showLoader() {
    document.getElementById('loader').style.display = 'block';
}

function hideLoader() {
    document.getElementById('loader').style.display = 'none';
}

function showError(message) {
    var errorMessage = document.getElementById('error-message');
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
}

function hideError() {
    var errorMessage = document.getElementById('error-message');
    errorMessage.style.display = 'none';
}

function getWeather() {
var location = document.getElementById('location').value.trim();
var weatherInfo = document.getElementById('weather-info');
if (location) {
showLoader();
hideError();
fetch(`weather.php?location=${location}`)
    .then(response => response.json())
    .then(data => {
        hideLoader();
        if (data.error) {
            showError(data.error);
            weatherInfo.style.display = 'none'; // Hide weather info container on error
        } else {
            hideError();
            displayWeather(data);
            weatherInfo.style.display = 'flex'; // Show weather info container on success
        }
    })
    .catch(error => {
        hideLoader();
        showError('An error occurred while fetching weather data');
        weatherInfo.style.display = 'none'; // Hide weather info container on error
    });
} else {
showError('Please enter a location');
weatherInfo.style.display = 'none'; // Hide weather info container when location is empty
}
}


function displayWeather(data) {
    if (data && data.daily) {
        document.getElementById('weather-info').style.display = 'flex';
        
        var now = new Date();
        var currentDate = now.toDateString();
        var currentTime = now.toLocaleTimeString();

        document.getElementById('current-date-time').innerHTML = `<h3>${currentDate}</h3><p>${currentTime}</p>`;
        document.getElementById('current-temp').innerHTML = `<h3>Current Temperature</h3><p>${data.daily[0].temp.toFixed(2)}°C</p>`;
        document.getElementById('current-location').innerHTML = `<h3>Location</h3><p>${data.city}, ${data.country}</p>`;

        var dailyWeatherDiv = document.getElementById('daily-weather');
        dailyWeatherDiv.innerHTML = '';

        data.daily.forEach(day => {
            var dayDiv = document.createElement('div');
            dayDiv.className = 'weather-day';
            dayDiv.innerHTML = `
                <h4>${new Date(day.date).toLocaleDateString(undefined, { weekday: 'short' })}</h4>
                <p>${day.temp.toFixed(2)}°C</p>
            `;
            dailyWeatherDiv.appendChild(dayDiv);
        });
    }
}