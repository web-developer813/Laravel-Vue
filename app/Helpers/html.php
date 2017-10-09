<?php

# style tag with time stamp
function style_ts($path, $external = false)
{
	try
	{
		$ts = '?v=' . File::lastModified(public_path().$path);
	}
	catch (Exception $e)
	{
		$ts = '';
	}

	return '<link media="all" type="text/css" rel="stylesheet" href="' . $path . $ts . '">';
}

# script tag with time stamp
function script_ts($path)
{
	try
	{
		$ts = '?v=' . File::lastModified(public_path().$path);
	}
	catch (Exception $e)
	{
		$ts = '';
	}

	return '<script src="' . $path . $ts . '"></script>';
}

# CDN PATH FOR SCRIPTS
function script_cdn($path)
{
	$path = (env('APP_ENV') === 'local') ? $path : env('CDN_URL') . $path;

	return '<script src="' . $path . '"></script>';
}

# CDN PATH FOR CSS
function style_cdn($path)
{
	$path = (env('APP_ENV') === 'local') ? $path : env('CDN_URL') . $path;

	return '<link media="all" type="text/css" rel="stylesheet" href="' . $path . '">';
}

# retina img
function retina_img($url)
{
	$extension_pos = strrpos($url, '.');
	$retina_img_url = substr($url, 0, $extension_pos) . '@2x' . substr($url, $extension_pos);
	return "src='$url' srcset='$url 1x, $retina_img_url 2x'";
}

# form group error
function form_error($fields, $error_class = 'has-error', $other_class = null)
{
	// convert to array
	if (!is_array($fields)) $fields = [0 => $fields];
	
	$errors = session()->get('errors', new \Illuminate\Support\MessageBag);
	
	foreach($fields as $field)
	{
		if ($errors->has($field)) return $error_class;
	}

	return $other_class;
}

# background image
function bg_img($path, $lazy = false)
{
	$lazy_prefix = $lazy ? 'lazy-' : null;
	return $lazy_prefix . 'style="background-image:url(\''. $path . '\')"';
}

# page title
function page_title($title = null, $separator = '&middot;')
{
	$website_name = config()->get('app.name') . " $separator Bijouterie de luxe à Montréal";
	$lang = config()->get('app.locale');

	if ($title)
		return "$title $separator $website_name";

	return "$website_name $separator Montres $separator Fiançailles $separator Bijoux";
}

# nav active
function nav_active($route, $mode = 'route', $route_params = [])
{
	$route_params = $route_params ?: [];

	switch($mode)
	{
		case 'route':
			return ($route == request()->route()->getName()) ? 'active' : null;

		case 'resource':
			// $resource_url = route($route, $route_params, false);
			// $current_url = '/' . request()->path();
			$resource_url = route($route, $route_params);
			$current_url = request()->url();
			return (starts_with($current_url, $resource_url)) ? 'active' : null;

		case 'strict':
			// $url = route($route, $route_params, false);
			// $qs = parse_url(request()->fullUrl(), PHP_URL_QUERY);
			// $current_url = '/' . request()->path();
			// 	if ($qs) $current_url .= '?' . $qs;
			$url = route($route, $route_params);
			$current_url = request()->fullUrl();
			return ($url == $current_url) ? 'active' : null;
	}
}

# nav active
function nav_active_lang($route)
{
	// return (config()->get('app.locale') . '.' . $route == request()->route()->getName()) ? 'active' : null;
	return nav_active(config()->get('app.locale') . '.' . $route);
}

# php to js
function php_to_js($var, $value)
{
	$response = '<script>';
	$response .= 'var ' .  $var . ' = ' . (($value) ? json_encode($value) : '') . ';';
	$response .= '</script>';
	
	return $response;
}

# intercom secure id
function intercom_secure_id($id)
{
	return hash_hmac('sha256', $id, getenv('INTERCOM_SECURE_KEY'));
}