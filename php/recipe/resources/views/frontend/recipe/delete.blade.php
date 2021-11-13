@extends("layouts.app")

@section("content")

<div class="container mt-3">
    <div class="" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete recipe</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this recipe?</p>
                    <p>{{ $recipe->name }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="history.back()">
                        Cancel
                    </button>
                    <form action="{{ route('recipe.destroy',[ 'recipe' => $recipe->id ] )}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection