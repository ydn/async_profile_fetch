<?php

require_once("../../yosdk/yahoo-yos-social-php-1fe1b43/lib/Yahoo.inc");
require_once("CustomSessionStore.php");

//define constants
require 'config.php';

function respond ($response)
{
    header('content-type: application/json');
    echo json_encode($response);
    die;
}

if (!$_GET['guid']) {
    respond(array('error' => 'guid is required'));
}

$store = new CustomSessionStore($_GET['guid']);
$access_token = $store->fetchAccessToken();

$consumer = (object) array(
    'key' => KEY,
    'secret' => SECRET
);

//token will expire in < 30 sec, so try to refresh
if (($access_token->tokenExpires >= 0) && ($access_token->tokenExpires - time()) < 30) {
    YahooSession::accessTokenExpired($access_token, $consumer, APPID, $store);
}

//use yahoosession for convenient yql oauth requests
$session = new YahooSession($consumer, $access_token, APPID);

//more info: http://developer.yahoo.com/yql/console/?q=select%20image.imageUrl%2C%20familyName%2C%20givenName%20from%20social.profile%20where%20guid%3Dme
$yql = sprintf('select image.imageUrl, familyName, givenName from social.profile where guid="%s"', $_GET['guid']);

respond(array('success' => $session->query($yql)));

?>