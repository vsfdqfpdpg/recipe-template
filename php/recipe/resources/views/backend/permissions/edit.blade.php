@extends("backend.layouts")

@section("content")
<div class="mt-3 container">
    <form action="{{ route('admin.permissions.update', ['permission' => $permission->id]) }}" method="post">
        {{ csrf_field() }}
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title',$permission->title) }}" />
            @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{old('description',$permission->description) }}" />
            @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="active" id="active" {{ old('active', $permission->active)  == true ? 'checked' : '' }} />
            <label for="active" class="form-check-label">Active</label>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-sm btn-primary form-control">
                Update
            </button>
        </div>
    </form>
</div>
@endsection