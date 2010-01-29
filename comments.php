<?php
/*
* Copyright: (c) 2009, Yahoo! Inc. All rights reserved.
* License: code licensed under the BSD License.  See [license.markdown](http://github.com/ydn/async_profile_fetch/blob/master/license.markdown)
*/

//comments from some db query
$comments = array(
    array(
        'provider' => 'yahoo',
        
        //yahoo user 1
        'id' => 'BG5BMUK24OOYGHWKTJBCX2TN5E',
        
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'twitter',
        'id' => '2',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'facebook',
        'id' => '3',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'facebook',
        'id' => '4',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'yahoo',
        
        //yahoo user 2
        'id' => 'VEGCGDXJ7FSR2PHSWPIR7EXMAQ',
        
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'facebook',
        'id' => '6',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'google',
        'id' => '7',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'twitter',
        'id' => '8',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
    array(
        'provider' => 'yahoo',
        
        //yahoo user 1
        'id' => 'BG5BMUK24OOYGHWKTJBCX2TN5E',
        
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'date' => '1264631647'
    ),
);

?>

<ul id="comments">
    
    <? // use index to disambiguate two comments from same user ?>
    <? foreach($comments as $index => $comment): ?>
    
        <li id="comment-<?= $index ?>-<?= $comment['provider'] ?>-<?= $comment['id'] ?>">
            
            <!-- reveal profile markup id data avail -->
            <div class="profile" style="display:none">
                <div class="name"></div>
                <img/>
            </div>
            
            <?= $comment['text'] ?><br/>
            <?= $comment['date'] ?>
        </li>
    <? endforeach ?>
</ul>

<script type="text/javascript" src="http://yui.yahooapis.com/3.0.0b1/build/yui/yui-min.js"></script>
<script>
var Y = YUI();
Y.use('node', 'io-base', 'json-parse', 'css3-selector', function (Y) {
    
    //loop through all the comments (ref: http://developer.yahoo.com/yui/3/api/NodeList.html#method_each)
    Y.all('#comments li').each(function (node, index, list) {
        
        //comment id
        var id = node.get('id');
        
        //to keep demo simple, profile.php only handles yahoo profiles
        if (-1 === id.indexOf('yahoo')) {
            return;
        }
        
        //proxy url + yahoo guid
		var uri = 'profile.php?guid=' + id.split('-')[3],
		
		    //xhr req callback
		    handleComplete = function (reqId, o, args) {
		        
		        //extract req data
                var json = Y.JSON.parse(o.responseText),
                    liNodeId = args.complete.split('=')[1];
                    
                //bail if error
                if (json.error) {
                    Y.log(json);
                    return;
                }
                
                //get profile data
                var familyName = json.success.query.results.profile.familyName,
                    givenName = json.success.query.results.profile.givenName,
                    picUrl = json.success.query.results.profile.image.imageUrl,
                    
                    //get dom nodes
                    profileNode = Y.Node.get('#' + liNodeId + ' .profile'),
                    nameNode = Y.Node.get('#' + liNodeId + ' .name'),
                    imgNode = Y.Node.get('#' + liNodeId + ' img');
                
                //show the name and/or pic if avail
                if (familyName) {
                    nameNode.append(givenName + ' ' + familyName);
                    profileNode.setStyle('display', 'block');
                }
                    
                if (picUrl) {
                    imgNode.set('src', picUrl);
                    profileNode.setStyle('display', 'block');
                }
	        },
	        config = {
	            
	            //define fn to call when req completes
	            'on' : {
	                'complete' : handleComplete
	            },
	            
	            //pass the comment node's id to the callback so it can add data to it 
                'arguments' : {
                    'complete' : 'id=' + id
                },
                
                //bind callback to each request not to window, which would only call fn for last req
        	    'context' : this
	        };
		Y.io(uri, config);
    });
});
</script>