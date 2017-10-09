<?php

function nextPageUrl($url) {
	if (!$url) return null;

	$parse = parse_url($url);
	$relative = ($parse['path']) ?: '/';
	$relative .= ($parse['query']) ? '?' . $parse['query'] : '';
	return $relative;
}

function addhttp($url) {
	if (!$url) return null;

    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    
    return $url;
}