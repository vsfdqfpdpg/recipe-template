@extends("backend.layouts")


@section("content")
<div>
    <div class="" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete a role</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this role?</p>
                    <p>{{ $role->title }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="history.back()">
                        Close
                    </button>
                    <form action="{{ route('admin.roles.destroy', ['role' => $role->id]) }}" method="post">
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection