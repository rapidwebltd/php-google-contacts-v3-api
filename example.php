<?php

require_once 'helpers/GoogleHelper.php';

use rapidweb\googlecontacts\helpers\GoogleHelper;

$client = GoogleHelper::getClient();

var_dump($client);