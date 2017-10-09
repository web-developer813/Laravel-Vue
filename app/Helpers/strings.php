<?php

function excerpt($string, $limit = 200) {
	$string = trim(strip_tags($string));
	$parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
	$parts_count = count($parts);

	$length = 0;
	$last_part = 0;
	$elipsis = '';
	for (; $last_part < $parts_count; ++$last_part) {
		$length += strlen($parts[$last_part]);
		if ($length > $limit) { break; }
	}

	$excerpt = trim(implode(array_slice($parts, 0, $last_part)));

	// remove punctuation from the end of excerpt
	if (strlen($string) > $limit)
	{
		$excerpt = rtrim($excerpt, '.,;)(!?…');
		$excerpt .= '&hellip;';
	}
	
	return $excerpt;
}

function no_single_word($string)
{
	// replace last space with nbsp
	return preg_replace('/\s(\S*)$/', '&nbsp;$1', trim($string));
}

function text_format($text)
{
	$text = strip_tags($text);
	$text = trim($text);
	$text = nl2br($text);
	$text = trim($text, "\n");

	return $text;
}

# encode hash
function encode_hash($id, $salt = null, $length=32)
{
	$hashids = new Hashids\Hashids(config()->get('app.key') . $salt, $length);
	return $hashids->encode($id);
}

#format phone
function format_phone($phone)
{
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1&#8209;$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1&#8209;$2&#8209;$3", $phone);
        // return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1)&nbsp;$2&#8209;$3", $phone);
    else
        return $phone;
}

# pluralize
function pluralize($word, $number)
{
	return $number . ' ' . str_plural($word, $number);
}

function properize($string) {
	return $string.'\''.($string[strlen($string) - 1] != 's' ? 's' : '');
}