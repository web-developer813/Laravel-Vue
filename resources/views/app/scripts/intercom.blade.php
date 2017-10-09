@if(auth()->check())
	{{-- volunteers --}}
	@if(current_mode('volunteer'))
		<script>
		  window.intercomSettings = {
		  	user_id: "{{ auth()->id() }}",
	  		user_hash: "{{ intercom_secure_id(auth()->id()) }}",
		    name: "{{ $authVolunteer->name }}",
			email: "{{ auth()->user()->email }}",
		    created_at: {{ auth()->user()->created_at->timestamp }},
		    app_id: '{{ getenv('INTERCOM_APP_ID') }}'
		  };
		</script>
	@elseif(current_mode('nonprofit'))
		<script>
		  window.intercomSettings = {
		  	user_id: "N{{ $authNonprofit->id }}",
	  		user_hash: "{{ intercom_secure_id('N' . $authNonprofit->id) }}",
		    name: "{{ $authNonprofit->name }}",
			email: "{{ $authNonprofit->email }}",
		    created_at: {{ $authNonprofit->created_at->timestamp }},
		    app_id: '{{ getenv('INTERCOM_APP_ID') }}'
		  };
		</script>
	@elseif(current_mode('forprofit'))
		<script>
		  window.intercomSettings = {
		  	user_id: "F{{ $authForprofit->id }}",
	  		user_hash: "{{ intercom_secure_id('F' . $authForprofit->id) }}",
		    name: "{{ $authForprofit->name }}",
			email: "{{ $authForprofit->email }}",
		    created_at: {{ $authForprofit->created_at->timestamp }},
		    app_id: '{{ getenv('INTERCOM_APP_ID') }}'
		  };
		</script>
	@endif
@else
	<script>
	  window.intercomSettings = {
		app_id: '{{ getenv('INTERCOM_APP_ID') }}'
	  };
	</script>
@endif

<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/k5ptb7d5';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>