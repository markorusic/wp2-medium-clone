<form action="{{ $config['endpoint'] }}">
	
	<input class="config" type="hidden"
		@foreach($config as $key => $value)	
			data-{{$key}}="{{ $value }}"
		@endforeach
	>
	<div class="row">
	@foreach($fields as $field)
		@php
			$type = isset($field['type']) ? $field['type'] : 'text';
			$value = isset($field['value']) ? $field['value'] : '';
			$placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
			$required = isset($field['required']) ? 'required' : '';
			$name = $field['name'];
			$id = preg_replace('/[^a-z0-9]/i', '_', $name);
			$col = isset($field['col']) ? $field['col'] : '12';
			$class = isset($field['class']) ? $field['class'] : '';
		@endphp
		<div class="form-group col-12 col-md-{{ $col }} {{ $class }}">
			@if (isset($field['label']))
				<label for="{{ $id }}">
					<strong>{{ $field['label'] }}</strong>
				</label>
			@endif

			@switch($type)
			    @case('text')
			    @case('number')
			    @case('email')
			        <input class="form-control"
						id="{{ $id }}"
						type="{{ $type }}"
						name="{{ $name }}"
						value="{{ $value }}"
						placeholder="{{ $placeholder }}"
						{{ $required }}
					>
			    @break

				@case('textarea')
			        <textarea class="form-control" rows="4" cols="50"
						id="{{ $id }}"
						type="{{ $type }}"
						name="{{ $name }}"
						placeholder="{{ $placeholder }}"
						{{ $required }}
					>{{ $value }}</textarea>
			    @break

			    @case('checkbox')
				    <input
					    id="{{ $id }}"
						type="{{ $type }}"
						name="{{ $name }}"
						value="true"
						{{ $value ? 'checked' : '' }}
					>
			    @break

			    @case('select')
			    	@php
			    		$multiple = isset($field['multiple']) && $field['multiple'] ? 'multiple' : '';
			    		$options = $field['options'];
			    	@endphp
			    	<select class="form-control"
						{{ $multiple }}
						{{ $required }}
			    		name="{{ $name }}"
			    	>			    		
			    		@foreach($options as $option)
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
			    	@php
			    		$hasPhoto = $value != '';
			    	@endphp
			        <div class="form-control flex-center mp-0">
			        	<input type="hidden"
			        		name="{{ $name }}"
			        		value="{{ $value }}"
			        	>
						<div id="{{ $id }}_photo_input"
							class="dz full-width" 
						>
							<div class="my-preview">
								<img style="height: 200px; max-width: 100%;" 
			        				src="{{ $value }}"
			        				alt="Upload photo"
				        		>
							</div>
						</div>
			        </div>
			    @break
				@case('gallery')
					<div class="row">
						@foreach($value as $photo)
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
					<div class="dz-gallery dropzone" id="{{ $id }}_gallery_input" data-name="{{ $name }}">
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
		<span class="content">Submit</span>
	</button>

	<div class="mt-4 response-alert alert-success">
		<strong class="message"></strong>
		<div class="mt-3 options"></div>
	</div>
</form>
