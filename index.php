<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link href="css/style.css" rel="stylesheet" />
</head>
<body>
    <div id="search-bar">
        <input type="text" id="location" placeholder="Enter location" required>
        <button onclick="getWeather()">Search</button>
    </div>
    <div id="weather-info">
        <div id="current-weather">
            <div id="current-date-time"></div>
            <div id="current-temp"></div>
            <div id="current-location"></div>
        </div>
        <div id="daily-weather"></div>
    </div>
    <div id="loader"><div class="spinner"></div></div>
    <div id="error-message">City not found</div>

    <script src="js/script.js" type="text/javascript"></script>
  
</body>
</html>
