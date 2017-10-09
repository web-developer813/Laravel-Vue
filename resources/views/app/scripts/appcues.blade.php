@if(auth()->check())
	<script src="//fast.appcues.com/21359.js"></script>
	<script>
	    Appcues.identify({{ auth()->id() }}, {
	        name: "{{ $authVolunteer->name }}",
	        email: "{{ auth()->user()->email }}",
	        created_at: {{ auth()->user()->created_at->timestamp }},
	    });
	</script>
@endif