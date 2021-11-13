@extends("backend.layouts")


@section("content")

<div class="container m-auto">
    <div class="">
        <a class="btn btn-sm btn-primary" href="{{ route('admin.permissions.create') }}">Create a permission</a>
    </div>

    @if(count($permissions))
    <div class="mt-3">
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
            @foreach($permissions as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->active == "1" ? "true" : "false"}}</td>
                <td>
                    <a href="{{ route('admin.permissions.edit', ['permission' => $item->id ]) }}" class="btn btn-info btn-sm">Edit</a>
                    <a href="{{ route('admin.permissions.delete', ['permission' => $item->id ]) }}" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            @endforeach
        </table>
        <div class="">
            {{ $permissions->links() }}
        </div>
    </div>
    @else
    <div class="alert alert-warning m-3">
        <span>There is no permission.</span>
    </div>
    @endif

</div>
@endsection