<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	{{-- styles --}}
	{{ HTML::style('https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700') }}
	{!! style_ts(config('app.url') . '/css/coupon.css') !!}

    {{-- favicon --}}
    <link rel="shortcut icon" href="{{ config('app.url') }}/img/favicon.png" type="image/png" />
</head>
<body id="incentives-purchase-show">
	<div class="container">
		<header>
			<img src='{{ config('app.url') }}/img/tecdonor-logo@2x.png' height="36" />
			Voucher # {{ $purchase->id }}
		</header>
		<div class="title">
			<h1>{{ $purchase->title }}</h1>
			<span>
				Offered by {{ $purchase->forprofit->name }}
				@if($purchase->forprofit->website_url)
					&middot; <a href="{{ $purchase->forprofit->website_url }}" target="_blank" class="profile__website">{{ $purchase->forprofit->formatted_website_url }}</a>
				@endif
			</span>
		</div>
		<div class="information clearfix">
			@if($purchase->has_image)
				<div class="image" style="background-image: url({{ $purchase->image_url }})"></div>
			@endif
			@if($purchase->summary)
				<div class="details">
					<p><strong>Recipient: {{ $purchase->volunteer->name }}</strong></p>
					<p>Purchased on: {{ $purchase->created_at->format('M jS, Y') }}</p>
					@if($purchase->expires_at)
						<p>Expires on: {{ $purchase->expires_at->format('M jS, Y') }}</p>
					@endif
					@if($purchase->coupon_code)
						<p>Coupon Code: {{ $purchase->coupon_code }}</p>
					@endif
					@if($purchase->barcode)
						<img src="{{ $purchase->barcode_url }}" alt="" class="barcode">
					@endif
				</div>
			@endif
		</div>
		{{-- description --}}
		<section class="description">
			<strong>Voucher Description</strong>
			<p>{!! text_format($purchase->summary) !!}</p>
		</section>
		{{-- how to use --}}
		<section class="how-to-use">
			<google-map address="1548 basin montreal quebec"></google-map>
			<strong>How To Use</strong>
			<p>{!! text_format($purchase->how_to_use) !!}</p>
		</section>
		{{-- address --}}
		<section class="address">
			<strong>Address</strong>
			<p>{{ $purchase->forprofit->full_address }}</p>
		</section>
		{{-- terms --}}
		@if($purchase->terms)
			<section class="terms">
				<strong>Voucher Terms</strong>
				<p>{!! text_format($purchase->terms) !!}</p>
			</section>
		@endif
	</div>
</body>
</html>