<?php
function readCsvFile() {
    $arr = [];
    $handle = fopen("de_postal_codes.csv", "r");
    while (($data = fgetcsv($handle)) !== FALSE) {
        if(count($data) < 2) {
            $test = explode(',', $data[0]);
            $arr[] = $test[0];
        } else {
            $arr[] = $data[0];
        }
    }
    $arr = array_unique($arr);
    unset($arr[0]);
    fclose($handle);
    return $arr;
}

function formatCsvFile($res) {
    $data = [];
    while ($placeId = $res->fetch_assoc())
    {
        $placeId = $placeId['place_id'];
        $result = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=". Google_Places_API);
        $result = (array) json_decode($result);

        $address = $result['result']->address_components;
        $number = '-'; $street = '-'; $city = '-'; $postcode = '-';

        foreach ($address as $key => $addres) {
            switch ($addres->types[0]) {
                case 'street_number':
                    $number = $addres->long_name;
                    break;
                case 'route':
                    $street = $addres->long_name;
                    break;
                case 'locality':
                    $city = $addres->long_name;
                    break;
                case 'postal_code':
                    $postcode = $addres->long_name;
                    break;
            }
        }

        if ($street === '-') {
            $route = array_reverse(explode(',', $result['result']->formatted_address));
            $street = $route[2];
        }

        $data[] = [
            'name' => $result['result']->name,
            'street'   => $street,
            'number'   => $number,
            'postcode' => $postcode,
            'city'     => $city
        ];
    }
    return $data;
}