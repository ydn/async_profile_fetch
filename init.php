<?php

require_once("../../yosdk/yahoo-yos-social-php-1fe1b43/lib/Yahoo.inc");
require_once("CustomSessionStore.php");

//define constants
require 'config.php';

$store = new CustomSessionStore();

if ($_GET['oauth_token']) {
    $request_token = $store->fetchRequestToken($_GET['oauth_token']);
    $store->clearRequestToken($_GET['oauth_token']);
    $access_token = YahooAuthorization::getAccessToken(KEY, SECRET, $request_token, $_GET['oauth_verifier']);
    $store->storeAccessToken($access_token);
    header('location: profile.php?guid='.$access_token->guid);
} else {
    $request_token = YahooAuthorization::getRequestToken(KEY, SECRET, CALLBACK);
    $store->storeRequestToken($request_token);
    $auth_url = YahooAuthorization::createAuthorizationUrl($request_token);
    header('location: '.$auth_url);
}
?>