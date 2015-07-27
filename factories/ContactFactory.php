<?php

namespace rapidweb\googlecontacts\factories;

use rapidweb\googlecontacts\helpers\GoogleHelper;
use rapidweb\googlecontacts\objects\Contact;

abstract class ContactFactory
{
    public static function getAll()
    {
        $client = GoogleHelper::getClient();

        $req = new \Google_Http_Request('https://www.google.com/m8/feeds/contacts/default/full?max-results=10000&updated-min=2007-03-16T00:00:00');

        $val = $client->getAuth()->authenticatedRequest($req);

        $response = $val->getResponseBody();

        $xmlContacts = simplexml_load_string($response);
        $xmlContacts->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');

        foreach ($xmlContacts->entry as $xmlContactsEntry) {
            $contactDetails = array();

            $contactDetails['id'] = (string) $xmlContactsEntry->id;
            $contactDetails['name'] = (string) $xmlContactsEntry->title;

            foreach ($xmlContactsEntry->children() as $key => $value) {
                $attributes = $value->attributes();
                
                if ($key == 'link') {
                    if ($attributes['rel'] == 'edit') {
                        $contactDetails['editURL'] = (string) $attributes['href'];
                    } elseif ($attributes['rel'] == 'self') {
                        $contactDetails['selfURL'] = (string) $attributes['href'];
                    }
                }
            }

            $contactGDNodes = $xmlContactsEntry->children('http://schemas.google.com/g/2005');

            foreach ($contactGDNodes as $key => $value) {
                $attributes = $value->attributes();

                if ($key == 'email') {
                    $contactDetails[$key] = (string) $attributes['address'];
                } else {
                    $contactDetails[$key] = (string) $value;
                }
            }

            $contactsArray[] = new Contact($contactDetails);
        }

        return $contactsArray;
    }
    
    public static function getBySelfURL($selfURL)
    {
        $client = GoogleHelper::getClient();

        $req = new \Google_Http_Request($selfURL);

        $val = $client->getAuth()->authenticatedRequest($req);

        $response = $val->getResponseBody();
        
        $xmlContact = simplexml_load_string($response);
        $xmlContact->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        
        $xmlContactsEntry = $xmlContact;
        
        $contactDetails = array();

        $contactDetails['id'] = (string) $xmlContactsEntry->id;
        $contactDetails['name'] = (string) $xmlContactsEntry->title;

        foreach ($xmlContactsEntry->children() as $key => $value) {
            $attributes = $value->attributes();
            
            if ($key == 'link') {
                if ($attributes['rel'] == 'edit') {
                    $contactDetails['editURL'] = (string) $attributes['href'];
                } elseif ($attributes['rel'] == 'self') {
                    $contactDetails['selfURL'] = (string) $attributes['href'];
                }
            }
        }

        $contactGDNodes = $xmlContactsEntry->children('http://schemas.google.com/g/2005');

        foreach ($contactGDNodes as $key => $value) {
            $attributes = $value->attributes();

            if ($key == 'email') {
                $contactDetails[$key] = (string) $attributes['address'];
            } else {
                $contactDetails[$key] = (string) $value;
            }
        }

        return new Contact($contactDetails);
    }
    
    public static function update(Contact $contact)
    {
        
    }
}
