<?php
/**
 * CLI PHP Script to add Matomo Goals 
 * Every goal will be pre-set with "matchAttribute=manually"
 * Hence you need to use 
 *  _paq.push(['trackGoal', GOAL-ID]);
 * to track these goals
 * 
 * Usage: php ./matomo_add_goals.php --goal_names_file=GOAL_NAMES_FILE.tsv --matomo_url="https://matomo-url.de/index.php" --id_site=ID_SITE --auth_token=AUTH_TOKEN
 * 
 * 
 * Outputs a JSON string with key-value pairs
 * "Content partern name":"_paq.push(['trackGoal', GOAL-ID]);"
 */


$longopts = [
    'goal_names_file:',
    'matomo_url:',
    'id_site:',
    'auth_token:',
];
$options = getopt('', $longopts);


/**
 * Debugging output of matomo_goal_file
 * This csv (tsv) must have 2 values per line: "id" AND "name" of the content partner
 */  
$csv = array_map(function($v){return str_getcsv($v, "\t");},
file($options['goal_names_file']));
print_r($csv);



// CURL POST to Matomo
$matomo_instance_url = $options['matomo_url']; 
$idSite = $options['id_site'];
$auth_token = $options['auth_token']; // d164379768cd2aa3c981702ce3307b55

foreach($csv AS $key => $csv_values) {

    if(!$csv_values[1]) {
        $csv_values[1] = "Leerer Name";
    }

    $name = $csv_values[1];
    $description = $csv_values[1];

    $payload = 'module=API&method=Goals.addGoal&idSite=1&name='.$name.'&description='.$description.'&matchAttribute=manually&allowMultipleConversionsPerVisit=1&pattern=fachportal&patternType=contains&format=JSON&token_auth='.$auth_token;

    // Prepare new cURL resource
    $ch = curl_init($matomo_instance_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set HTTP Header for POST request
    /*
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
    );
    */

    // Submit the POST request
    $result = curl_exec($ch);

    // $result returns JSON like {"value":"5"}
    $result_json = json_decode($result);
    $goal_ID = $result_json->value;
    $output_array[$name] = "_paq.push(['trackGoal', ".$goal_ID."]);";

    if($key == 2) {
        break;
    }
}

$output_json = json_encode($output_array);
print($output_json);


// Close cURL session handle
curl_close($ch);