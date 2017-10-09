<li class="nav-item">
	<a href="{{ isset($route_params) ? route($route, $route_params) : route($route) }}" class="{{ nav_active($route, 'resource', isset($route_params) ? $route_params : []) }} nav-link">{{ $name }}</a>
</li>