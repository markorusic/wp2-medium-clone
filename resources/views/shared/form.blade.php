@php
	function stringifyProps ($props) {
		return $props
			->keys()
			->reduce(function ($carry, $key) use ($props) {
				$value = $props->get($key);
				$prop = "$key=$value";
				if (is_bool($value)) {
					$prop = $key;
				}
				return implode(' ', [$carry, $prop]);
			}, '');
	}
@endphp
<form action="{{ $config['endpoint'] }}">
	
	<input class="config" type="hidden"
		@foreach($config as $key => $value)	
			data-{{$key}}="{{ $value }}"
		@endforeach
	>
	<div class="row">
	@foreach($fields as $field)
		@php
			$field = collect($field);
			$type = $field->get('type', 'text');
			$props = $field->except(['label', 'options', 'containerClass']);
		@endphp
		<div class="form-group col-12 {{ $field->get('containerClass', '') }}">
			@if ($field->has('label'))
				<label for="{{ $field->get('id') }}">
					<strong>{{ $field->get('label') }}</strong>
				</label>
			@endif

			@switch($type)
			    @case('text')
			    @case('number')
			    @case('email')
					<input
						class="form-control"
						value="{{ $props->get('value', '') }}"
						placeholder="{{ $props->get('placeholder', '') }}"
						{{ stringifyProps($props->except(['value', 'placeholder'])) }}
					>
			    @break

				@case('textarea')
					<textarea
						class="form-control"
						rows="4"
						cols="50"
						placeholder="{{ $props->get('placeholder', '') }}"
						{{ stringifyProps($props->except(['value', 'placeholder'])) }}
					>{{ $props->get('value') }}</textarea>
			    @break

			    @case('checkbox')
				    <input
						{{ stringifyProps($props->except('value')) }}
						value="true"
						{{ $fieldProps->has('value') ? 'checked' : '' }}
					>
			    @break

			    @case('select')
			    	<select class="form-control" {{ stringifyProps($props) }}>			    		
			    		@foreach($field->get('options') as $option)
							@php
			    				$selected = in_array($option['value'], $value) ? 'selected' : '';
			    			@endphp
			    			<option value="{{ $option['value'] }}" {{ $selected }}>
			    				{{ $option['display'] }}
			    			</option>
			    		@endforeach
			    	</select>
			    @break

			    @case('photo')
			        <div class="form-control flex-center mp-0">
			        	<input type="hidden"
			        		name="{{ $field->get('name') }}"
			        		value="{{ $field->get('value') }}"
			        	>
						<div id="{{ $field->get('name') }}_photo_input"
							class="dz full-width" 
						>
							<div class="my-preview">
								<img style="height: 200px; max-width: 100%;" 
			        				src="{{ $field->get('value') }}"
			        				alt="Upload photo"
				        		>
							</div>
						</div>
			        </div>
			    @break
				@case('gallery')
					<div class="row">
						@foreach($field->get('value') as $photo)
							<div class="col-3 mb-4">
								<div class="gallery-item" style="background-image: url({{ $photo['url'] }})">
									<a class="btn btn-danger delete-gallery-item" href="#"
										data-endpoint="{{ route('admin.product.photo.delete', [
											'id' => $photo['id']
										]) }}"
									>
										<i class="fa fa-trash"></i>
									</a>
								</div>
							</div>
						@endforeach
					</div>
					<div class="dz-gallery dropzone" id="{{ $field->get('id') }}_gallery_input" data-name="{{ $name }}">
						{{-- @foreach($value as $val)
							<input type="hidden" name="{{ $name }}" value="{{ $val }}">
						@endforeach --}}
					</div>
			    @break
			    @case('map')
			    	<h3>Map</h3>
			    @break
			@endswitch

		</div>
	@endforeach
	</div>
	<button class="btn btn-success" type="submit">
		<i class="fa fa-floppy-o text-white mr-2"></i>
		<span class="content">Save</span>
	</button>

	<div class="mt-4 response-alert alert-success">
		<strong class="message"></strong>
		<div class="mt-3 options"></div>
	</div>
</form>
