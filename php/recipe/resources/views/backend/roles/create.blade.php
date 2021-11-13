@extends("backend.layouts")

@section("content")
<div class="mt-3 container">
    <form action="{{ route('admin.roles.store') }}" method="post">
        {{ csrf_field() }}
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{old('title')}}" />

            @error('title')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{old('description')}}" />
            @error('description')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="active" id="active" class="form-check-input" />
            <label for="active" class="form-check-label">Active</label>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-sm btn-primary form-control">
                Add
            </button>
        </div>
    </form>
</div>
@endsection