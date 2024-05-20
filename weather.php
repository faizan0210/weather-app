<?php
if (isset($_GET['location'])) {
    $location = urlencode($_GET['location']);
    $apiKey = '14ee9c1dcefc7d7ac5235446039d60fc'; 
    $apiUrl = "https://api.openweathermap.org/data/2.5/forecast?q={$location}&units=metric&appid={$apiKey}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($data['cod'] == "200") {
        $city = $data['city']['name'];
        $country = $data['city']['country'];
        $weatherList = $data['list'];

        // Aggregate data by day
        $dailyData = [];
        foreach ($weatherList as $entry) {
            $date = date('Y-m-d', $entry['dt']);
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'temp_sum' => 0,
                    'count' => 0,
                    'description' => $entry['weather'][0]['description'],
                    'date' => $date,
                ];
            }
            $dailyData[$date]['temp_sum'] += $entry['main']['temp'];
            $dailyData[$date]['count']++;
        }

        // Process and format daily data
        foreach ($dailyData as &$day) {
            $day['temp'] = $day['temp_sum'] / $day['count'];
            unset($day['temp_sum']);
            unset($day['count']);
        }

        // Ensure we have exactly 7 days
        $dailyData = array_values($dailyData);
        if (count($dailyData) > 7) {
            $dailyData = array_slice($dailyData, 0, 7);
        } else {
            $lastDay = end($dailyData);
            while (count($dailyData) < 7) {
                $newDate = date('Y-m-d', strtotime($lastDay['date'] . ' +1 day'));
                $newDay = [
                    'temp' => $lastDay['temp'],
                    'description' => $lastDay['description'],
                    'date' => $newDate,
                ];
                $dailyData[] = $newDay;
                $lastDay = $newDay;
            }
        }

        $result = [
            'city' => $city,
            'country' => $country,
            'daily' => $dailyData,
        ];

        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Location not found']);
    }
}
?>
