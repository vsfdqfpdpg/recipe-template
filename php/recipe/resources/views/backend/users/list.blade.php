@extends("backend.layouts")


@section("content")
<div class="container m-auto mt-3">
    <div>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
                <th>Is Confirmed</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>
                    <img src="/{{ $u->avatar }}" alt="" width="32px" height="32px" />
                </td>
                <td>{{ $u->first_name }}</td>
                <td>{{ $u->last_name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->is_comfirmed ? "true" : "false" }}</td>
                <td>
                    @foreach($u->roles as $r)
                    <button class="btn btn-sm btn-secondary">{{ $r->title }}</button>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('admin.users.role', ['user' => $u->id]) }}" class="btn btn-sm btn-info">Assign Role</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="">
        {{ $users->links() }}
    </div>
</div>
@endsection