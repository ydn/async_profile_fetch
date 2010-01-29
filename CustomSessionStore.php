<?php

/*
* Copyright: (c) 2009, Yahoo! Inc. All rights reserved.
* License: code licensed under the BSD License.  See [license.markdown](http://github.com/ydn/async_profile_fetch/blob/master/license.markdown)
*/

require '../../yosdk/yahoo-yos-social-php-1fe1b43/lib/YahooSessionStore.inc';

class CustomSessionStore implements YahooSessionStore {
    
    function __construct($guid=NULL, $storage='yahooTokenStorage.php'){
        $this->guid = $guid;
        $this->storage = $storage;
        
        //init w/ empty array
        if (!is_file($this->storage)) {
           file_put_contents($this->storage, serialize(array()));
        }
    }
    
    /**
     * Indicates if the session store has a request token.
     *
     * @return True if a request token is present, false otherwise.
     */
    function hasRequestToken() {
        $token = $this->fetchRequestToken();
        return $token ? true : false;
    }

    /**
     * Indicates if the session store has an access token.
     *
     * @return True if an access token is present, false otherwise.
     */
    function hasAccessToken() {
        $token = $this->fetchAccessToken();
        return $token ? true : false;
    }

    /**
     * Stores the given request token in the session store.
     *
     * @param $token A PHP stdclass object containing the components of
     *               the OAuth request token.
     * @return True on success, false otherwise.
     */
    function storeRequestToken($token){
        $storage = unserialize(file_get_contents($this->storage));
        $storage[$token->key] = $token;
        file_put_contents($this->storage, serialize($storage));
        return true;
    }

    /**
     * Fetches and returns the request token from the session store.
     *
     * @return The request token.
     */
    function fetchRequestToken($oauth_token=NULL){
        $storage = unserialize(file_get_contents($this->storage));
        return $storage[$oauth_token];
    }

    /**
     * Clears the request token from the session store.
     *
     * @return True on success, false otherwise.
     */
    function clearRequestToken(){
        $storage = unserialize(file_get_contents($this->storage));
        unset($storage[$this->request_token]);
        file_put_contents($this->storage, serialize($storage));
    }

    /**
     * Stores the given access token in the session store.
     *
     * @param $token A PHP stdclass object containing the components of
     *               the OAuth access token.
     * @return True on success, false otherwise.
     */
    function storeAccessToken($token){
        $storage = unserialize(file_get_contents($this->storage));
        $storage[$token->guid] = $token;
        file_put_contents($this->storage, serialize($storage));
        return true;
    }

    /**
     * Fetches and returns the access token from the session store.
     *
     * @return The access token.
     */
    function fetchAccessToken(){
        $storage = unserialize(file_get_contents($this->storage));
        return $storage[$this->guid];   
    }

    /**
     * Clears the access token from the session store.
     *
     * @return True on success, false otherwise.
     */
    function clearAccessToken(){
        $storage = unserialize(file_get_contents($this->storage));
        unset($storage[$this->guid]);
        file_put_contents($this->storage, serialize($storage));
    }
}