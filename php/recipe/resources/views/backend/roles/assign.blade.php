@extends("backend.layouts")

@section("content")

<div class="mt-3 container">
    <h3> {{ $role->title }}</h3>
    @if(count($permissions))
    <form action="{{ route('admin.roles.assignPermission', ['role' => $role->id]) }}" method="post">
        <div class="mb-3">
            <label for="permissions">Permissions</label>
            <select name="permissions[]" id="permissions" class="form-control @error('permissions') is-invalid @enderror" multiple>
                @foreach($permissions as $item)
                <option value="{{ $item->id }}" {{ count($role->permissions->filter(function($value) use($item){ return $item->id == $value->id;})) ? "selected" : "" }}> {{ $item->title }}</option>
                @endforeach
            </select>

            @error("permissions")
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-sm btn-primary form-control">
                Assign Permission
            </button>
        </div>
        {{csrf_field()}}
    </form>
    @else
    <div class="alert alert-warning">
        <span>There is no permission yet, please create one first.</span>
    </div>
    @endif
</div>

@endsection