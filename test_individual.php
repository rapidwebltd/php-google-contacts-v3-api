<?php

require_once '../../../vendor/autoload.php';

use rapidweb\googlecontacts\factories\ContactFactory;

if (!isset($_GET['selfURL'])) {
    throw new Exception('No selfURL specified.');
}

$contact = ContactFactory::getBySelfURL($_GET['selfURL']);

var_dump($contact);

$contact->name = 'Test';
$contact->phoneNumber = '07812363789';
$contact->email = 'test@example.com';
$contact->content = 'Note for example';

$contactAfterUpdate = ContactFactory::submitUpdates($contact);

var_dump($contactAfterUpdate);
