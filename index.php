<?php
include_once 'config.php';
include 'functions.php';

$arr = readCsvFile();

for ($item = 1; $item < 10; $item++) {
    $postcode = $arr[$item];
    $result = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$postcode+Germany&key=". Google_Places_API);
    $result = (array) json_decode($result);

    if ($result['status'] === 'OK') {
        $lat = $result['results'][0]->geometry->location->lat;
        $lng = $result['results'][0]->geometry->location->lng;
        /**
        *    Type grocery_or_supermarket will be support to 16 february 2017 year
        *    https://developers.google.com/places/web-service/supported_types?hl=en
        */
        $result = file_get_contents("https://maps.googleapis.com/maps/api/place/radarsearch/json?location=$lat,$lng&radius=3000&type=grocery_or_supermarket&key=". Google_Places_API);
        $result = (array)json_decode($result);

        foreach($result['results'] as $value) {
            $putIntoDb = $mysqli->query("REPLACE INTO `". Database ."`.`places` (`place_id`) VALUES ('$value->place_id');");
        }
    }
}

$fp = fopen('file.csv', 'w');
$res = $mysqli->query("SELECT * FROM `". Database ."`.`places`");

$data = formatCsvFile($res);

fputcsv($fp, ['Name', 'Street', 'Street number', 'Post code', 'City']);
foreach ($data as $fields) {
        fputcsv($fp, $fields);
}
fclose($fp);

$mysqli->query("DROP TABLE `". Database ."`.`places`");
$mysqli->close();
?>
<!DOCTYPE HTML>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Success</title>
    </head>
    <body>
        <p>For loading press button</p>
        <form action="converToXls.php" method="post">
            <button type="submit">Download</button>
        </form>
    </body>
</html>

