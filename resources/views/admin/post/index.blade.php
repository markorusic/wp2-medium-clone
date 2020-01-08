@extends('admin.shared.layout')

@section('scripts')
<script>
    dataTable.init({
        resource: 'posts',
        columns: ['title', 'main_photo'],
        searchBy: 'title',
    })
</script>
@endsection
