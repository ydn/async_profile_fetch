# Async Profile Fetch

This sample code uses OAuth to allow users to authorize access to their Yahoo! Profile data, stores the access token, and then fetches this data asynchronously.

Suppose you have a service that uses Yahoo! data requiring authorization via OAuth and you display that data on a publicly available page.  For example, you have comments on your pages, and you use the Yahoo! Profile API to display the name Yahoo! Profile picture of each comment author.  To do this, you need to store an OAuth access token for each user.  To increase the performance of your page, you might want to request this data after the page loads.  

## Requirements

   * PHP 4 or 5
   * The [Yahoo! PHP SDK](http://developer.yahoo.com/social/sdk/#php)
   * [A Yahoo! OAuth key and secret](http://developer.yahoo.com/oauth/)
   
## Usage

   1. Download this project to your server.  
   Make sure it's accessible via the url you used to create your Yahoo! OAuth key and secret
   1. Edit _config.php_ to use your OAuth key, secret, app id, and callback url
   1. Edit _comments.php_ to use the Yahoo! guids of your users.  
   Normally, the user would be logged in at the time the comments are posted, so the guid would already be associated with the comments when they are viewed.
   1. Load _init.php_ in a browser and authorize access.  
   After authorization, you'll be redirected to _comments.php_, which will load the profile data

## License

   * Copyright: (c) 2009, Yahoo! Inc. All rights reserved.
   * License: code licensed under the BSD License.  See [license.markdown](http://github.com/ydn/async_profile_fetch/blob/master/license.markdown)
   * Package: [http://github.com/ydn/async\_profile\_fetch](http://github.com/ydn/async_profile_fetch)