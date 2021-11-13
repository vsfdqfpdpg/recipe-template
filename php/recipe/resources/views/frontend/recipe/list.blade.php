@extends('layouts.app')

@section('content')

<style>
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>

<div class="container mt-3">
    <div>
        <div class="modal fade" tabindex="-1" id="exampleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure want to delete this comment?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary confirm">Yes</button>
                    </div>
                </div>
            </div>
        </div>

        @foreach($recipes as $recipe)
        <div class="card col-8 m-auto mt-3">
            <div class="align-items-center card-header d-flex justify-content-between">
                <div class="align-items-center d-flex">
                    <img src="/{{ $recipe->user->avatar }}" alt="" style="height: 32px; height: 32px" class="rounded-circle" />
                    <div class="d-flex flex-column ms-3">
                        <h5 class="m-0"> {{ $recipe->user->name }}</h5>
                        <span class="m-0" style="font-size: 14px">{{$recipe->updated_at}}</span>
                    </div>
                </div>
                <div>
                    @auth()
                    @if(count($recipe->favourites))
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger favourite" data-id="{{$recipe->id}}" data-type="recipe" data-is-favourite="true">Unfavourite</a>
                    @else
                    <a href="javascript:void(0)" class="btn btn-sm btn-info favourite" data-id="{{$recipe->id}}" data-type="recipe" data-is-favourite="false">Favourite</a>
                    @endif

                    @if( $user->id == $recipe->user->id)
                    <a href="/recipe/{{$recipe->id }}/edit" class="btn btn-sm btn-info">Edit</a>
                    <a href="/recipe/{{$recipe->id }}/delete" class="btn btn-sm btn-danger">Delete</a>
                    @endif
                    @endauth
                </div>
            </div>

            <div class="card-body position-relative">
                <div class="{{  $recipe->status == 'REJECTED' ? 'overlay' : '' }}"></div>
                <h5 class="card-title"><a href=" {{ route('recipe.show', ['recipe' => $recipe->id]) }} ">{{ $recipe->name }}</a></h5>
                <div class="col-6 d-flex flex-nowrap row">
                    <table class="ms-3 table table-bordered">
                        <tr>
                            <td>Cooking Style</td>
                            <td>
                                {{ ucfirst(strtolower($recipe->cooking_style)) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Preservable</td>
                            <td>{{ $recipe->preserve == "1" ? "true" : "false" }}</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>
                                {{implode(",", array_map(function ($value) { return  ucfirst(strtolower(\CATEGORY[$value])); }, explode(",", $recipe->category)))}}
                            </td>
                        </tr>
                        <tr>
                            <td>Duration</td>
                            <td>{{ $recipe->duration }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>
                                {{ ucfirst(strtolower($recipe->status)) }}
                            </td>
                        </tr>
                    </table>
                    <img src="{{ asset($recipe->image) }}" alt="" srcset="" height="205px" />
                </div>
                <p class="card-text">{{ $recipe->description }}</p>

                @if($user)
                <div class="row">
                    <form action="" method="post" class="recipe">
                        {{csrf_field()}}
                        <div class="align-items-center mb-3 row">
                            <div class="mb-1 col-sm-1">
                                <img src="/{{ $user->avatar }}" alt="" style="width: 32px; height: 32px" class="rounded-circle" />
                            </div>
                            <div class="col-sm-11 form-control-sm">
                                <input type="hidden" name="object_id" value="{{ $recipe->id }}" />
                                <input type="hidden" name="type" value="Recipe">
                                <input type="text" name="comment" id="{{ 'comment_' . $recipe->id }}" class="form-control" placeholder="{{ 'Comment as ' . $user->name }}" />
                                <label for="{{'comment_' . $recipe->id }}">Press enter to post.</label>
                                <div class=" invalid-feedback">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <div class="recipe-comment">
                    @foreach($recipe->comments as $comment)
                    <div class="d-flex mb-1">
                        <div class="me-3">
                            <img src="/{{ $comment->user->avatar }}" style="width: 32px; height: 32px;" class="rounded-circle" alt="">
                        </div>
                        <div style="width: 80%;">
                            <div class="d-flex flex-column p-3" style="background: #f0f2f5;border-radius: 10px;">
                                <div class="d-flex justify-content-between">
                                    <b>{{ $comment->user->name }} </b>
                                    <div>
                                        @auth
                                        @if(count($comment->favourites))
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger favourite" data-id="{{ $comment->id }}" data-type="comment" data-is-favourite="true">Unfavourite</a>
                                        @else
                                        <a href="javascript:void(0)" class="btn btn-sm btn-info favourite" data-id="{{ $comment->id }}" data-type="comment" data-is-favourite="false">Favourite</a>
                                        @endif
                                        @if($user->id == $comment->user->id)
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-id="{{ $comment->id }}" data-bs-type="recipe">Delete</button>
                                        @endif
                                        @endauth
                                    </div>
                                </div>
                                <span style="font-size: 12px;">{{ $comment->created_at }}</span>
                                <span style="font-size:15px;" class="p-1">{{ $comment->comment }}</span>
                            </div>
                            @auth
                            <div class="my-1">
                                <a href="javascript:void(0)" class="text-decoration-none reply" style="color: #65676b;">Reply</a>
                                <form action="" method="post" class="comment" style="display: none;">
                                    <div class="d-flex flex-nowrap">
                                        <div class="mt-1">
                                            <img src="/{{ $user->avatar }}" alt="" style="width: 32px; height: 32px" class="rounded-circle" />
                                        </div>
                                        <div class="col-sm-11 form-control-sm">
                                            <input type="hidden" name="object_id" value="{{ $comment->id }}" />
                                            <input type="hidden" name="type" value="Comment">
                                            <input type="text" name="comment" id="{{ 'comment_' . $comment->id }}" class="form-control" placeholder="{{ 'Comment as ' . auth()->user()->name }}" />
                                            <label for="{{ 'comment_' . $comment->id }}">Press enter to post.</label>
                                            <div class=" invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endauth
                            @guest
                            <div class="my-3"></div>
                            @endguest

                            <div class="comment-comment">
                                @foreach($comment->comments as $sub)
                                <div class="d-flex mb-3">
                                    <img src="/{{ $sub->user->avatar }}" style="width: 32px; height: 32px;" class="rounded-circle" alt="">
                                    <div class="d-flex flex-column ms-2 p-3 w-100" style="background-color: #f0f2f5;border-radius: 10px;">
                                        <div class="d-flex justify-content-between">
                                            <b>{{ $sub->user->name }} </b>
                                            <div>
                                                @auth
                                                @if( count($sub->favourites))
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger favourite" data-id="{{ $sub->id }}" data-type="comment" data-is-favourite="true">Unfavourite</a>
                                                @else
                                                <a href="javascript:void(0)" class="btn btn-sm btn-info favourite" data-id="{{ $sub->id }}" data-type="comment" data-is-favourite="false">Favourite</a>
                                                @endif
                                                @if($user->id == $sub->user->id)
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-id="{{ $sub->id }}" data-bs-type="comment">Delete</button>
                                                @endif
                                                @endauth
                                            </div>
                                        </div>
                                        <span style="font-size: 12px;">{{ $sub->created_at }}</span>
                                        <span style="font-size:15px;" class="p-1">{{ $sub->comment }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="col-8 m-auto mt-3">
        {{ $recipes->links() }}
    </div>

</div>

<script>
    const dateFormat = (d) => [d.getFullYear(), d.getMonth() + 1, d.getDate()].join(" -") + " " + [d.getHours(), d.getMinutes(), d.getSeconds()].join(":");

    let exampleModal = document.getElementById('exampleModal');

    exampleModal.addEventListener('show.bs.modal', function(event) {
        // Button that triggered the modal 
        let button = event.relatedTarget
        // Extract info from data-bs-* attributes 
        let id = button.getAttribute('data-bs-id');
        let type = button.getAttribute('data-bs-type');
        exampleModal.querySelector("button.confirm").dataset.id = id;
        exampleModal.querySelector("button.confirm").dataset.type = type;
    });

    exampleModal.querySelector("button.confirm").addEventListener("click", e => {
        let id = e.target.dataset.id;
        let type = e.target.dataset.type;
        fetch(`/recipe/${id}/comment`, {
            method: "DELETE",
            headers: {
                "Content-type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        }).then(r => {
            if (!r.ok) {
                let e = new Error("Http status code: " + r.status);
                e.response = Promise.resolve(r.json());
                throw e;
            } else {
                return r.json()
            }
        }).then(data => {
            console.log(data);
            if (type == "recipe") {
                document.querySelector(`[data-bs-id='${id}']`).closest("div.d-flex.mb-1").remove()
            } else {
                document.querySelector(`[data-bs-id='${id}']`).closest("div.d-flex.mb-3").remove()
            }
            // document.querySelector(`[data-bs-id='${id}']`).parentElement.parentElement.parentElement.parentElement.remove()
            const modal = bootstrap.Modal.getInstance(exampleModal);
            modal.hide();
        }).catch(e => {
            e.response.then(err => {
                alert(err.errors.id)
            })
        })
    });

    let handelReply = reply => reply.addEventListener("click", e => {
        e.preventDefault();
        let form = reply.nextElementSibling;
        let display = form.style.display;
        if (display == "" || display == "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    });

    let handelReplyForm = reply => {
        let form = reply.nextElementSibling;
        form.addEventListener("submit", e => {
            e.preventDefault();
            let formData = new FormData(e.target);
            let object_id = formData.get("object_id");
            fetch(`/recipe/${object_id}/sub-comment`, {
                method: "POST",
                body: JSON.stringify({
                    "comment": form.querySelector("input[name='comment']").value
                }),
                headers: {
                    "Content-type": "application/json",
                    'X-Requested-With': "XMLHttpRequest",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            }).then(r => r.json()).then(data => {
                let commentComent = form.parentElement.nextElementSibling;
                let {
                    avatar,
                    first_name,
                    last_name
                } = data.user
                let {
                    id,
                    comment,
                    created_at
                } = data.comment
                commentComent.insertAdjacentHTML("afterbegin", `<div class="d-flex mb-3">
                <img src="/${avatar}" style="width: 32px; height: 32px;" class="rounded-circle" alt="">
                <div class="d-flex flex-column ms-2 p-3 w-100" style="background-color: #f0f2f5;border-radius: 10px;">
                <div class="d-flex justify-content-between">
                <b>${first_name} ${last_name} </b>
                <div>
                <a href="javascript:void(0)" class="btn btn-sm btn-info favourite dynamic" data-id="${id}" data-type="comment" data-is-favourite="false">Favourite</a>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-id="${id}" data-bs-type="comment">Delete</button>
                </div>

                </div>
                <span style="font-size: 12px;">${created_at.replace("T"," ").split(".")[0]}</span>
                <span style="font-size:15px;" class="p-1">${comment}</span>
                </div>
                </div>`);
                form.querySelector("input[name='comment']").value = "";
                let newlyFavourite = document.querySelector(".favourite.dynamic");
                handelFavourite(newlyFavourite);
                newlyFavourite.classList.remove("dynamic");
                console.log(data);
            })
        });
    };

    let handelFavourite = favourite => favourite.addEventListener("click", e => {
        e.preventDefault();

        let {
            id,
            type,
            isFavourite
        } = e.target.dataset;

        console.log(id, type, isFavourite);

        if (isFavourite == "false") {
            fetch(`/favourite`, {
                method: "POST",
                headers: {
                    "Content-type": "application/json",
                    'X-Requested-With': "XMLHttpRequest",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    id,
                    type
                })
            }).catch(e => {
                console.log(e);
            }).then(j => j.json()).then(data => {
                console.log(data);
                e.target.dataset.isFavourite = true;
                e.target.classList.remove("btn-info")
                e.target.classList.add("btn-danger")
                e.target.innerText = "Unfavourite"
            })
        } else {
            fetch(`/favourite`, {
                method: "DELETE",
                headers: {
                    "Content-type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    id,
                    type
                })
            }).catch(e => {
                console.log(e);
            }).then(j => j.json()).then(data => {
                console.log(data);
                e.target.dataset.isFavourite = false;
                e.target.classList.remove("btn-danger")
                e.target.classList.add("btn-info")
                e.target.innerText = "Favourite"
            })
        }
    });

    let cards = document.querySelectorAll(".card");

    Array.from(cards).forEach(card => {
        let form = card.querySelector("form.recipe");
        if (form) {
            let input = form.querySelector("input[type='text']");

            input.addEventListener("focus", e => {
                e.target.classList.remove("is-invalid")
            }, false);

            input.addEventListener("keyup", e => {
                if (e.keyCode != 13) {
                    e.target.classList.remove("is-invalid")
                }
            }, false);

            form.addEventListener("submit", (e) => {
                e.preventDefault();
                let formData = new FormData(e.target);
                formData.append("_method", "POST");
                let object_id = formData.get("object_id");

                fetch(`/recipe/${object_id}/comment`, {
                        method: "POST",
                        body: JSON.stringify({
                            "comment": form.querySelector("input[name='comment']").value
                        }),
                        headers: {
                            "Content-type": "application/json",
                            'X-Requested-With': "XMLHttpRequest",
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    })
                    .then((r) => {
                        if (!r.ok) {
                            let e = new Error("Http status code: " + r.status);
                            e.response = Promise.resolve(r.json());
                            throw e;
                        }
                        return r.json();
                    })
                    .catch((error) => {
                        error.response.then((err) => {
                            for (let i in err.errors) {
                                let el = e.target.querySelector(`#${i}_${object_id}`);
                                console.log(`#${i}_${object_id}`);
                                el.classList.add("is-invalid");
                                let msgEl = e.target.querySelector(`#${i}_${object_id} ~ .invalid-feedback`);
                                msgEl.innerHTML = err.errors[i].join("\n");
                            }
                        });
                    })
                    .then((data) => {
                        let {
                            avatar,
                            first_name,
                            last_name
                        } = data.user
                        let {
                            id,
                            comment,
                            created_at
                        } = data.comment
                        let recipeComment = card.querySelector(".recipe-comment");
                        recipeComment.insertAdjacentHTML("afterbegin", `<div class="d-flex mb-1">
                                            <div class="me-3">
                                                <img src="/${avatar}" style="width: 32px; height: 32px;" class="rounded-circle" alt="">
                                            </div>
                                            <div style="width: 80%;">
                                                <div class="d-flex flex-column p-3" style="background: #f0f2f5;border-radius: 10px;">
                                                    <div class="d-flex justify-content-between">
                                                        <b>${first_name} ${last_name} </b>
                                                        <div>
                                                            <a href="javascript:void(0)" class="btn btn-sm btn-info favourite dynamic" data-id="${id}" data-type="comment" data-is-favourite="false">Favourite</a>
                                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-id="${id}" data-bs-type="recipe">Delete</button>
                                                        </div>
                                                    </div>
                                                    <span style="font-size: 12px;">${created_at.replace("T"," ").split(".")[0]}</span>
                                                    <span style="font-size:15px;" class="p-1">${comment}</span>
                                                </div>
                                                <div class="my-1">
                                                    <a href="javascript:void(0)" class="text-decoration-none reply dynamic" style="color: #65676b;">Reply</a>
                                                    <form action="" method="post" class="comment" style="display: none;">
                                                        <div class="d-flex flex-nowrap">
                                                            <div class="mt-1">
                                                                <img src="/${avatar}" alt="" style="width: 32px; height: 32px" class="rounded-circle">
                                                            </div>
                                                            <div class="col-sm-11 form-control-sm">
                                                                <input type="hidden" name="object_id" value="${id}">
                                                                <input type="hidden" name="type" value="Comment">
                                                                <input type="text" name="comment" id="comment_${id}" class="form-control
                                                    " placeholder="Comment as ${first_name} ${last_name}">
                                                                <label for="comment_${id}" "="">Press enter to post.</label>
                                                    <div class=" invalid-feedback"></div>
                                                        </div>
                                                </div>
                            </form>
                        </div>
                        <div class="comment-comment">

                        </div>
                    </div>
                </div>`);

                        let newlyReply = document.querySelector(".reply.dynamic");

                        handelReply(newlyReply);
                        handelReplyForm(newlyReply);
                        newlyReply.classList.remove("dynamic");
                        let newlyFavourite = document.querySelector(".favourite.dynamic");
                        handelFavourite(newlyFavourite);
                        newlyFavourite.classList.remove("dynamic");
                        input.value = ""
                    });
            });
        }

        let replyLinks = card.querySelectorAll(".reply");

        Array.from(replyLinks).forEach(reply => {
            handelReply(reply);
            handelReplyForm(reply);
        });
    })

    let favourites = document.querySelectorAll(".favourite");

    Array.from(favourites).forEach(favourite => {
        handelFavourite(favourite);
    });
</script>
@endsection