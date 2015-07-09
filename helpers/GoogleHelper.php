<?php
require_once "vendor/google/apiclient/src/Google/autoload.php";

namespace rapidweb\googlecontacts\helpers;

abstract class GoogleHelper
{
    private static $clientID = '';
    private static $clientSecret = '';
    private static $redirectUri = '';
    private static $developerKey = '';
    
    public static function loadConfig()
    {
        $contents = file_get_contents('config.json');
        
        $data = json_decode($contents);
        
        foreach($data as $key => $value)
        {
            $this->$key = $value;
        }
    }
    
    public static function getClient()
    {
        $client = new Google_Client();
        
        $client->setApplicationName("Rapid Web Google Contacts API");
        
        $client->setScopes(array(/*
        'https://apps-apis.google.com/a/feeds/groups/',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://apps-apis.google.com/a/feeds/alias/',
        'https://apps-apis.google.com/a/feeds/user/',*/
        'https://www.google.com/m8/feeds/',
        /*'https://www.google.com/m8/feeds/user/',*/
        ));
        
        $client->setClientId(self::clientID);
        $client->setClientSecret(self::clientSecret);
        $client->setRedirectUri(self::redirectUri);
        $client->setAccessType('offline'); 
        $client->setDeveloperKey(self::developerKey);
        
        return $client;
    }
    
    public static function getAuthUrl(Google_Client $client)
    {
        return $client->getAuthUrl();
    }
}