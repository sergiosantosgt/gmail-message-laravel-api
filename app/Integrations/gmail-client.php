<?php
require '../vendor/autoload.php';

// if (php_sapi_name() != 'cli') {
//     throw new Exception('This application must be run on the command line.');
// }

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */

function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes(Google_Service_Gmail::GMAIL_MODIFY);
    $client->setAuthConfig('../credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = '../token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            // $authUrl = $client->createAuthUrl();
            // printf("Open the following link in your browser:\n%s\n", $authUrl);
            // print 'Enter verification code: ';
            // $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode('4/mgAOUO5jSXcvBGturA78xlv3shtKkFLs4u8O6DKL9So6tTGeDjo-MtE');
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Gmail($client);

function getService($client) {
    $service = new Google_Service_Gmail($client);
    return $service;
}

// Print the labels in the user's account.
$user = 'me';
$results = $service->users_labels->listUsersLabels($user);

// if (count($results->getLabels()) == 0) {
//     print "No labels found.\n";
// } else {
//     print "Labels:\n";
//     foreach ($results->getLabels() as $label) {
//     printf("- %s\n", $label->getName());
//     }
// }


/**
 * Get Message with given ID.
 *
 * @param  Google_Service_Gmail $service Authorized Gmail API instance.
 * @param  string $userId User's email address. The special value 'me'
 * can be used to indicate the authenticated user.
 * @param  string $messageId ID of Message to get.
 * @return Google_Service_Gmail_Message Message retrieved.
 */


function getMessage($service, $userId, $messageId) {
    try {
        $message = $service->users_messages->get($userId, $messageId);
        $from    = $service->users_messages->get($userId, $message->getId(), ['format' => 'metadata', 'metadataHeaders' => ['From']]);
        $subject = $service->users_messages->get($userId, $message->getId(), ['format' => 'metadata', 'metadataHeaders' => ['Subject']]);

        $emailValue = isset($from["payload"]["headers"][0]["value"]) ? $from["payload"]["headers"][0]["value"] : "";
        if(strpos($emailValue, '<') !== false) {
            $bit  = explode('<',$emailValue);
            $bit2 = explode('>',$bit[1]);
            $senderEmail   = $bit2[0];
            $senderName    = str_replace('"', '',$bit[0]);
        } else {
            $senderName = "";
            $senderEmail = "";
        }

        $senderSubject = isset($subject["payload"]["headers"][0]["value"]) ? $subject["payload"]["headers"][0]["value"] : "";

        $arrRet = array(
            "senderName"    => $senderName,
            "senderEmail"   => $senderEmail,
            "senderSubject" => $senderSubject,
            "message"       => $message
        );
        return $arrRet;

    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function listMessages($service, $userId, $maxResults = 1000000) {
    $pageToken = NULL;
    $messages = array();
    $opt_param = array("maxResults" => $maxResults);
    // do {
    //     try {
    //     if ($pageToken) {
    //         $opt_param['pageToken'] = $pageToken;
    //     }
    //     $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
    //     if ($messagesResponse->getMessages()) {
    //         $messages = array_merge($messages, $messagesResponse->getMessages());
    //         $pageToken = $messagesResponse->getNextPageToken();
    //     }
    //     } catch (Exception $e) {
    //     print 'An error occurred: ' . $e->getMessage();
    //     }
    // } while ($pageToken);

    $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
    if ($messagesResponse->getMessages()) {
        $messages = array_merge($messages, $messagesResponse->getMessages());
        $pageToken = $messagesResponse->getNextPageToken();
    }

    return $messages;
}