@extends("layouts.app")


@section("content")
<div class="mt-3 container">
    <form action="{{ route('recipe.store') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="mb-3">
            <label for="name mb-1">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" />
            @error('name')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror

        </div>
        <div class="mb-3">
            <span class="d-block mb-1">Preservable</span>
            <div class="form-check form-check-inline p-0 @error('preserve') is-invalid @enderror">
                <input type="radio" name="preserve" id="preserve_yes" value="true" {{ old("preserve") == "true" ? "checked" : "" }} />
                <label for="preserve_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" name="preserve" id="preserve_no" value="false" {{ old("preserve") == "false" ? "checked" : "" }} />
                <label for="preserve_no">No</label>
            </div>
            @error('preserve')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="cooking_style" class="mb-1">Cooking Style</label>
            <select name="cooking_style" id="cooking_style" class="form-control @error('cooking_style') is-invalid @enderror">
                @foreach(\COOKING_STYLE as $key => $cs)
                <option value="{{$key}}" {{ $key == old("cooking_style") ? "selected" : "" }}>
                    {{ ucfirst(strtolower($cs)) }}
                </option>
                @endforeach
            </select>
            @error('cooking_style')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <span class="d-block mb-1 @error('category') is-invalid @enderror">Category</span>
            @foreach(\CATEGORY as $key => $ca)
            <div class="form-check form-check-inline">
                <input type="checkbox" name="category[]" id="{{ ucfirst(strtolower($ca)) }}" class="form-check-input" value="{{$key}}" {{ in_array($key, old("category",[])) ? "checked" : "" }} />
                <label for="{{ ucfirst(strtolower($ca)) }}" class="form-check-label">{{ ucfirst(strtolower($ca)) }}</label>
            </div>
            @endforeach
            @error('category')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="image" class="mb-1">Image</label>
            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" />
            <div class="preview"></div>
            @error('image')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="mb-1">Description</label>
            <textarea name="description" id="description" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
            @error('description')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <label for="duration" class="mb-1">Duration</label>
        <div class="mb-3 input-group">
            <input type="number" name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror" min="1" value="{{old('duration')}}" />
            <span class="input-group-text">minutes</span>
            @error('duration')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-sm btn-primary form-control">
                Create
            </button>
        </div>
    </form>
</div>
@endsection