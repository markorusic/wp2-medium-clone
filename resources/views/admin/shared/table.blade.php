@php
  function _limit($string, $length = 70, $dots = "...") {
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
  }
@endphp
<table class="table resource-table">
  <thead>
    <tr>
      @foreach($cols as $i => $col)
        <th class="uc-first">{{  implode(' ', explode('_', $col)) }}</th>
      @endforeach
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($items as $index => $item)
      @php
        $id = ['id' => $item['id']];
      @endphp
      <tr data-id="{{ $item['id'] }}">
        @foreach($cols as $col)
            @switch($col)
              @case('content')
                <td>{{ _limit($item[$col]) }}</td>
              @break
              @case('main_photo')
                <td>
                  <img src="{{ $item[$col] }}" alt="Photo not found" class="table-img">
                </td>
              @break
              @default
                <td>{{ $item[$col] }}</td>
            @endswitch
        @endforeach
        <td class="flex resource-actions">
          <a class="btn btn-primary white-txt mr-2"
            href="{{ route("admin.{$resource}.edit", 1) }}" 
          >
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </a>
          <a class="btn btn-danger white-txt"
            data-delete="{{ route("admin.{$resource}.destroy", 1) }}"
          >
            <i class="fa fa-trash-o" aria-hidden="true"></i>
          </a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $items->links() }}
