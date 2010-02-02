<?php

/*
* Copyright: (c) 2009, Yahoo! Inc. All rights reserved.
* License: code licensed under the BSD License.  See [license.markdown](http://github.com/ydn/async_profile_fetch/blob/master/license.markdown)
*/

require '../../yosdk/yahoo-yos-social-php-1fe1b43/lib/YahooSessionStore.inc';

class SqliteStorage
{
   function __construct()
   {
       //the directory containing this file must be writable for this to work
       if ($this->db = new SQLiteDatabase('sqlite')) {
           
           //if there's no 'storage' table, create one
           $q = @$this->db->query('SELECT value FROM storage WHERE key = 1');
           if ($q === false) {
               $sql = "CREATE TABLE storage (key text, value text, PRIMARY KEY (key)); INSERT INTO storage VALUES (1,1)";
               $this->db->queryExec($sql);
           }
       } else {
           die($err);
       }
   }
   
   function insert($key, $value)
   {
       $sql = "INSERT INTO storage (key, value) values ('$key', '$value')";
       $this->db->queryExec($sql);
   }
   
   function select($key)
   {
       $sql = "SELECT value FROM storage WHERE key = '$key'";
       $result = $this->db->query($sql);
       return $result = $result->fetchSingle();
   }
   
   function delete($key)
   {
       $sql = "DELETE FROM storage WHERE key = '$key'";
       $this->db->queryExec($sql);
   }
}

class CustomSessionStore implements YahooSessionStore {
    
    function __construct($guid=NULL){
        $this->guid = $guid;
        $this->storage = $storage;
        $this->storage = new SqliteStorage;
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
        $this->storage->insert($token->key, serialize($token));
        return true;
    }

    /**
     * Fetches and returns the request token from the session store.
     *
     * @return The request token.
     */
    function fetchRequestToken($oauth_token=NULL){
        return unserialize($this->storage->select($oauth_token));
    }

    /**
     * Clears the request token from the session store.
     *
     * @return True on success, false otherwise.
     */
    function clearRequestToken(){
        $this->storage->delete($oauth_token);
    }

    /**
     * Stores the given access token in the session store.
     *
     * @param $token A PHP stdclass object containing the components of
     *               the OAuth access token.
     * @return True on success, false otherwise.
     */
    function storeAccessToken($token){
        $this->storage->insert($token->guid, serialize($token));
        return true;
    }

    /**
     * Fetches and returns the access token from the session store.
     *
     * @return The access token.
     */
    function fetchAccessToken(){
        return unserialize($this->storage->select($this->guid));
    }

    /**
     * Clears the access token from the session store.
     *
     * @return True on success, false otherwise.
     */
    function clearAccessToken(){
        $this->storage->delete($this->guid);
    }
}