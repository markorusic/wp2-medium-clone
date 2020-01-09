@extends('admin.shared.layout')

@section('scripts')
<script>
    dataTable.init({
        resource: 'posts',
        searchBy: 'title',
        columns: [
            {
                name: 'title',
                sortable: true
            },
            {
                name: 'main_photo',
                displayName: 'Main photo',
                type: 'photo'
            }
        ],
    })
</script>
@endsection
