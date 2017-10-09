@if(auth()->check())
	{{-- volunteers --}}
	@if(current_mode('volunteer'))
		<script>var env = {APP_ENV: '{{ env('APP_ENV') }}',PUSHER_KEY: '{{ env('PUSHER_KEY') }}',PUSHER_CLUSTER: '{{ env('PUSHER_CLUSTER') }}', csrfToken: '{{ csrf_token() }}' };var volunteer = {!! json_encode(auth()->user()->volunteer->toArray()) !!};</script>
	@elseif(current_mode('nonprofit'))
		<script>var env = {APP_ENV: '{{ env('APP_ENV') }}',PUSHER_KEY: '{{ env('PUSHER_KEY') }}',PUSHER_CLUSTER: '{{ env('PUSHER_CLUSTER') }}', csrfToken: '{{ csrf_token() }}' };var volunteer = {!! json_encode(auth()->user()->volunteer->toArray()) !!};var nonprofit = {!! json_encode($authNonprofit->toArray()) !!};</script>
	@elseif(current_mode('forprofit'))
		<script>var env = {APP_ENV: '{{ env('APP_ENV') }}',PUSHER_KEY: '{{ env('PUSHER_KEY') }}',PUSHER_CLUSTER: '{{ env('PUSHER_CLUSTER') }}', csrfToken: '{{ csrf_token() }}' };var volunteer = {!! json_encode(auth()->user()->volunteer->toArray()) !!};var forprofit = {!! json_encode($authForprofit->toArray()) !!};</script>
	@endif
@endif
