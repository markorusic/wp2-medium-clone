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
						<div>
							<input type="file" accept="image/*" class="d-none" data-photo-file-input />
							<input
								data-photo-input
								type="hidden"
								name="{{ $field->get('name') }}"
								value="{{ $field->get('value') }}"
							>
							<div class="photo-upload-control d-flex justify-content-center align-items-center border p-3 pointer">
								<div class="d-flex justify-content-center align-items-center h-100">
									@if (!$field->get('value'))
										{{ $field->get('placeholder', 'Click to upload photo') }}
									@else
										<img class="img-fluid" src="{{ $field->get('value') }}">
									@endif
								</div>
							</div>
						</div>
					@break
				@endswitch

			</div>
		@endforeach
	</div>
	<div class="my-3">
		<button class="btn btn-success" type="submit">
			<i class="fa fa-floppy-o text-white mr-2"></i>
			<span class="content">Save</span>
		</button>
	</div>
</form>
