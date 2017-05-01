<?php

namespace rapidweb\googlecontacts\helpers;

abstract class GoogleHelper
{
    private static $_config;

    public static function initConfig(
        $clientID,
        $clientSecret,
        $redirectUri,
        $developerKey,
        $refreshToken
    ) {
        self::$_config = new \stdClass();
        self::$_config->clientID = $clientID;
        self::$_config->clientSecret = $clientSecret;
        self::$_config->redirectUri = $redirectUri;
        self::$_config->developerKey = $developerKey;
        self::$_config->refreshToken = $refreshToken;
    }

    private static function loadConfig($customConfig = NULL)
    {
        self::$_config = $customConfig;
        if (NULL === self::$_config) {
            $configPath = __DIR__.'/../../../../.config.json';
            if(!file_exists($configPath)) throw new \Exception('Not found config.json');
            $contents = file_get_contents($configPath);
            self::$_config = json_decode($contents);
        }

        return self::$_config;
    }

    public static function getClient($customConfig = NULL)
    {
        $config = self::loadConfig($customConfig);

        $client = new \Google_Client();

        $client->setApplicationName('Rapid Web Google Contacts API');

        $client->setScopes(array(/*
        'https://apps-apis.google.com/a/feeds/groups/',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://apps-apis.google.com/a/feeds/alias/',
        'https://apps-apis.google.com/a/feeds/user/',*/
        'https://www.google.com/m8/feeds/',
        /*'https://www.google.com/m8/feeds/user/',*/
        ));

        $client->setClientId($config->clientID);
        $client->setClientSecret($config->clientSecret);
        $client->setRedirectUri($config->redirectUri);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setDeveloperKey($config->developerKey);

        if (isset($config->refreshToken) && $config->refreshToken) {
            $client->refreshToken($config->refreshToken);
        }

        return $client;
    }

    public static function getAuthUrl(\Google_Client $client)
    {
        return $client->createAuthUrl();
    }

    public static function authenticate(\Google_Client $client, $code)
    {
        $client->authenticate($code);
    }

    public static function getAccessToken(\Google_Client $client)
    {
        return json_decode($client->getAccessToken());
    }
}
