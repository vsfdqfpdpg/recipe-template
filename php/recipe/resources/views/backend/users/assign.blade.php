@extends("backend.layouts")

@section("content")
<div class="mt-3">
    <div class="col-6 m-auto">
        <div class="d-block row">
            <h3>{{ $member->name }}</h3>
            <h5>{{ $member->email }}</h5>
        </div>

        <div>
            <form action="" method="post">
                {{csrf_field()}}
                <div class="mb-3">
                    <label for="role" class="mb-2">Roles</label>
                    <select name="roles[]" id="role" multiple class="form-control @error('roles') is-invalid @enderror">
                        @foreach($roles as $r)
                        <option value="{{ $r->id }}" {{ $member->roles->contains($r->id)  ? "selected" : "" }}>{{ $r->title }}</option>
                        @endforeach
                    </select>

                    @error("roles")
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <div class="btn btn-sm btn-secondary me-3" onclick="history.back()">Cancel</div>
                    <button type="submit" class="btn btn-sm btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection