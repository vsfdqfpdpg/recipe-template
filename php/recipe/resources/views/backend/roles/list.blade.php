@extends("backend.layouts")


@section("content")
<div class="container m-auto">
    <div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">Create a role</a>
    </div>

    @if(count($roles))
    <div class="mt-3">
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Description</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
            @foreach($roles as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->active == "1" ? "true" : "false" }}</td>
                <td>
                    <a href="{{ route('admin.roles.show', ['role' => $item->id ]) }}" class="btn btn-sm btn-secondary">View</a>
                    <a href="{{ route('admin.roles.edit', ['role' => $item->id ]) }}" class="btn btn-sm btn-info">Edit</a>
                    <a href="{{ route('admin.roles.assign', ['role' => $item->id ]) }}" class="btn btn-sm btn-secondary">Asign</a>
                    <a href="{{ route('admin.roles.delete', ['role' => $item->id ]) }}" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            @endforeach
        </table>
        <div class="">
            {{ $roles->links() }}
        </div>
        @else
        <div class="alert alert-warning m-3 row">
            <span>There is no role.</span>
        </div>
    </div>
    @endif
</div>
@endsection