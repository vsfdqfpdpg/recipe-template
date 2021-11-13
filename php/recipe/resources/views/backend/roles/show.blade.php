@extends("backend.layouts")

@section("content")

<div class="mt-3 container">
    <div class="m-auto">
        <div class="mb-3">
            <h3>Title: {{ $role->title }}</h3>
            <h5>Descripton: {{ $role->description }}</h5>
        </div>

        @if(count($role->permissions))
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
            @foreach($role->permissions as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->title }}</td>
                <td>{{ $p->description }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <div class="alert alert-warning">
            <span>There has no permission has been assigned to this role.</span>
        </div>
        @endif
    </div>
</div>
@endsection