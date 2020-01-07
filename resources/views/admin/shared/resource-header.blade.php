<div class="card-header flex-sp-between">
    @php
    	$headerTitle = Str::plural($title);
    	if (!isset($plural)) {
    		$headerTitle = $title;
    	}
    @endphp
    <span>{{ $headerTitle }}</span>
    @if (isset($url))
	    <span>
	    	<a class="btn btn-success" href="{{ $url }}">
	    		<i class="fa fa-plus" aria-hidden="true"></i> 
	    		Create {{ $title }}
	    	</a>
	    </span>
	@endif
</div>