<?php

// After filling in the clientID, clientSecret and redirectUri (within 'config.json'), you should visit this page
// to get the authorisation URL.

// Note that the redirectUri value should point towards a hosted version of 'redirect_handler.php'.

require_once '../../../vendor/autoload.php';

use rapidweb\googlecontacts\helpers\GoogleHelper;

$client = GoogleHelper::getClient();

$authUrl = GoogleHelper::getAuthUrl($client);

echo 'Go to the following URL to authorise your application for Google Contacts: '.$authUrl;
echo "\r\n";
