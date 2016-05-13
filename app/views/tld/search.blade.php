{{ Form::open(array('action' => 'json')) }}

{{ Form::text('Search') }}
{{ Form::submit('Search') }}

{{ Form::close() }}

{{-- */ $results = DB::select('select TLD from tld_info'); /* --}}

@foreach ($results as $result) 
	@foreach($result as $k=>$v)
		{{ HTML::link("tld/$v", strtoupper($v)) }}
	@endforeach
@endforeach
	
