@extends("frontend.user.layout")


@section('content')

@auth
@if(auth()->id() == $user->id)
<div class="accordion" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                Edit profile
            </button>
        </h2>
        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
            <div class="accordion-body">
                <div class="col-6 m-auto">
                    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="mb-3">
                            <div class="preview mt-2">
                                <img src="/{{$user->avatar}}" alt="" width="100px" height="100px" />
                            </div>
                            <label class="mt-1" for="avatar">Profile image</label>
                            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*" />
                        </div>
                        <div class="mb-3">
                            <label for="first_name">First name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror>" value="{{old("first_name", $user->first_name)}}" />
                            @error('first_name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="last_name">Last name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror>" value="{{old("last_name", $user->last_name)}}" />
                            @error('last_name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-sm btn-primary form-control">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                Change password
            </button>
        </h2>
        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse {{ old('old_password') ? "show" : "" }}" aria-labelledby="panelsStayOpen-headingTwo">
            <div class="accordion-body">
                <div class="col-6 m-auto">
                    <form action="{{ route('profile.change') }}" method="post">
                        {{csrf_field()}}
                        <div class="mb-3">
                            <label for="old_password">Old password</label>
                            <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror >" />
                            @error('old_password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password">New password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror>" />
                            @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation">Confirm password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror>" />
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-sm btn-primary form-control">
                                Change
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let avatar = document.querySelector("#avatar");
    let preveiw = document.querySelector(".preview img");
    if (avatar) {
        avatar.addEventListener("change", (e) => {
            let file = e.target.files[0];
            preveiw.src = URL.createObjectURL(file);
        });
    }
</script>
</div>
@else
<div class="card col-8 m-auto">
    <img src="/{{ $user->avatar }}" style="width: 320px;" class="card-img-top m-auto mt-3" alt="...">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td>Name</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>CreatedAt</td>
                <td>{{ $user->created_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endif
@endauth

@guest
<div class="card col-8 m-auto">
    <img src="/{{ $user->avatar }}" style="width: 320px;" class="card-img-top m-auto mt-3" alt="...">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td>Name</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>CreatedAt</td>
                <td>{{ $user->created_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endguest
@endsection