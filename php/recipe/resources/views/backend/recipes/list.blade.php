@extends("backend.layouts")

@section('content')
<div class="mt-3 container">
    <div>
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Category</th>
                <th>Image</th>
                <th>Status</th>
                <th>User</th>
                <th>Action</th>
            </tr>

            @foreach($recipes as $recipe)
            <tr>
                <td>{{ $recipe->id }}</td>
                <td>{{ $recipe->name }}</td>
                <td>
                    {{implode(",", array_map(function ($value) { return  ucfirst(strtolower(\CATEGORY[$value]));}, explode(",", $recipe->category)))}}
                </td>
                <td><img src="/{{ $recipe->image }}" alt="" height="32" width="32" /></td>
                <td>
                    {{ ucfirst(strtolower($recipe->status)) }}
                </td>
                <td>{{ $recipe->user->name }}</td>
                <td>
                    <a href="{{ route('recipe.show', ['recipe' => $recipe->id ]) }}" class="btn btn-sm btn-info">Verify</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div>
        {{ $recipes->links() }}
    </div>
</div>
</div>
@endsection