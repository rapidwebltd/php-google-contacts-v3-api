<?php

// This page handles the redirect from the authorisation page. It will authenticate your app and
// retrieve the refresh token which is used for long term access to Google Contacts. You should
// add this refresh token to the 'config.json' file.

if (!isset($_GET['code'])) {
    die('No code URL paramete present.');
}

$code = $_GET['code'];

require_once '../../../vendor/autoload.php';

use rapidweb\googlecontacts\helpers\GoogleHelper;

$client = GoogleHelper::getClient();

GoogleHelper::authenticate($client, $code);

$accessToken = GoogleHelper::getAccessToken($client);

if (!isset($accessToken->refresh_token)) {
    echo 'Google did not respond with a refresh token. You can still use the Google Contacts API, but you may to re-authorise your application in the future. ';

    echo 'Access token response:';

    var_dump($accessToken);
} else {
    echo 'Refresh token is: '.$accessToken->refresh_token.' - Please add this to the config file.';
    $configLocation = '.config.json';
    $contents = file_get_contents($configLocation);
    $data = json_decode($contents, true);
    $data['refreshToken'] = $accessToken->refresh_token;
    $newJsonString = json_encode($data, JSON_UNESCAPED_SLASHES);
    file_put_contents($configLocation, $newJsonString);
    
    // TODO: Redirect to page
}
