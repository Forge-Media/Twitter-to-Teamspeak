<?php

# Load Twitter class

require_once('TwitterOAuth.php');



# Define constants (Keep the '' if string)

define('TWEET_LIMIT', 1);

define('TWITTER_USERNAME', '');

define('CONSUMER_KEY', '');

define('CONSUMER_SECRET', '');

define('ACCESS_TOKEN', '');

define('ACCESS_TOKEN_SECRET', '');



# Create the connection

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);



# Migrate over to SSL/TLS

$twitter->ssl_verifypeer = true;



# Load the Tweets

$tweets = $twitter->get('statuses/user_timeline', array('screen_name' => TWITTER_USERNAME, 'exclude_replies' => 'true', 'include_rts' => 'false', 'count' => TWEET_LIMIT));



# Example output

if(!empty($tweets)) {

    foreach($tweets as $tweet) {



        # Access as an object

        $tweetText = $tweet['text'];



        # Make links active

        #$tweetText = preg_replace("/(http://|(www.\))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$1$2$4</a>', $tweetText);

        #$tweetText = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","$1",$tweetText);

        $tweetText = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '[URL]$1$2$4[/URL]', $tweetText);



        # Linkify user mentions

        #$tweetText = preg_replace("/@(w+)/",'<a href="http://www.twitter.com/$1" target="_blank">@$1</a>',$tweetText);

        $tweetText = preg_replace('/@([a-zA-Z0-9_]+)/', '@$1', $tweetText);



        # Linkify tags

        $tweetText = preg_replace("/#(w+)/",'<a href="http://search.twitter.com/search?q=$1" target="_blank">#$1</a>',$tweetText);



        # Output

        echo $tweetText;



    }

}