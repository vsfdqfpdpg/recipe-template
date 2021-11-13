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
    <div class="mt-3 container">
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
        <div class="card col-6 m-auto mb-3">
            <div class="align-items-center card-header d-flex justify-content-between">
                <div class="align-items-center d-flex">
                    <img src="/{{ $recipe->user->avatar }}" alt="" style="height: 32px; height: 32px" class="rounded-circle" />
                    <div class="d-flex flex-column ms-3">
                        <h6 class="m-0">
                            {{ $recipe->user->name}}
                        </h6>
                        <span class="m-0" style="font-size: 14px">{{ $recipe->updated_at }}</span>
                    </div>
                </div>
                <div>
                    @auth

                    @if(count($recipe->favourites))
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger favourite" data-id="{{ $recipe->id }}" data-type="recipe" data-is-favourite="true">Unfavourite</a>
                    @else
                    <a href="javascript:void(0)" class="btn btn-sm btn-info favourite" data-id="{{ $recipe->id }}" data-type="recipe" data-is-favourite="false">Favourite</a>
                    @endif

                    @if(auth()->id() == $recipe->user_id)
                    <a href="{{ route('recipe.edit', ['recipe' => $recipe->id ]) }}" class="btn btn-sm btn-info">Edit</a>
                    <a href="{{ route('recipe.delete', ['recipe' => $recipe->id ]) }}" class="btn btn-sm btn-danger">Delete</a>
                    @endif

                    @can("verify", $recipe)

                    @if($recipe->status == "PASS")
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger publish" data-id="{{ $recipe->id }}" data-is-published="true">
                        Unpublish
                    </a>
                    @else
                    <a href="javascript:void(0)" class="btn btn-sm btn-info publish" style="display: {{ $recipe->status == 'REJECTED' ? 'none' : 'inline-block' }}" data-id="{{ $recipe->id }}" data-is-published="false">
                        Publish
                    </a>
                    @endif

                    @if($recipe->status != "REJECTED")
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger reject" data-id="{{ $recipe->id }}" data-is-rejected="false">
                        Reject
                    </a>
                    @elseif($recipe->status == "REJECTED")
                    <a href="javascript:void(0)" class="btn btn-sm btn-info reject" data-id="{{ $recipe->id }}" data-is-rejected="true">
                        Restore
                    </a>
                    @endif
                    @endcan
                    @endauth
                </div>
            </div>
            <div class="card-body position-relative">
                <div class="{{ $recipe->status == 'REJECTED' ? 'overlay' : '' }}"></div>
                <h5 class="card-title">{{ $recipe->name }}</h5>
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
                            <td>{{ $recipe->preserve ? "true" : "false"}} </td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>
                                {{implode(",", array_map(function ($value) {return  ucfirst(strtolower(\CATEGORY[$value]));}, explode(",", $recipe->category)))}}
                            </td>
                        </tr>
                        <tr>
                            <td>Duration</td>
                            <td>{{ $recipe->duration }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td class="status-{{ $recipe->id }}">
                                {{ ucfirst(strtolower($recipe->status)) }}
                            </td>
                        </tr>
                    </table>
                    <img src="/{{ $recipe->image }}" alt="" srcset="" height="205px" />
                </div>
                <p class="card-text">{{ $recipe->description }}</p>
                @auth
                <div class="row">
                    <form action="" method="post" class="recipe">
                        <div class="align-items-center mb-3 row">
                            <div class="mb-1 col-sm-1">
                                <img src="/{{ auth()->user()->avatar }}" alt="" style="width: 32px; height: 32px" class="rounded-circle" />
                            </div>
                            <div class="col-sm-11 form-control-sm">
                                <input type="hidden" name="object_id" value="{{ $recipe->id }}" />
                                <input type="hidden" name="type" value="Recipe">
                                <input type="text" name="comment" id="{{ 'comment_'. $recipe->id }}" class="form-control" placeholder="{{'Comment as ' . auth()->user()->name }}" />
                                <label for="{{ 'comment_'. $recipe->id }}">Press enter to post.</label>
                                <div class=" invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @endauth
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

                                        @if(auth()->id() == $comment->user->id)
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
                                            <img src="/{{ auth()->user()->avatar }}" alt="" style="width: 32px; height: 32px" class="rounded-circle" />
                                        </div>
                                        <div class="col-sm-11 form-control-sm">
                                            <input type="hidden" name="object_id" value="{{ $comment->id }}" />
                                            <input type="hidden" name="type" value="Comment">
                                            <input type="text" name="comment" id="{{ 'comment_'. $comment->id }}" class="form-control" placeholder="{{ 'Comment as '. auth()->user()->name }}" />
                                            <label for="{{ 'comment_'. $comment->id }}">Press enter to post.</label>
                                            <div class=" invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @else
                            <div class="my-3"></div>
                            @endauth

                            <div class="comment-comment">
                                @foreach($comment->comments as $sub)
                                <div class="d-flex mb-3">
                                    <img src="/{{ $sub->user->avatar }}" style="width: 32px; height: 32px;" class="rounded-circle" alt="">
                                    <div class="d-flex flex-column ms-2 p-3 w-100" style="background-color: #f0f2f5;border-radius: 10px;">
                                        <div class="d-flex justify-content-between">
                                            <b>{{ $sub->user->name }} </b>
                                            <div>
                                                @auth
                                                @if(count($sub->favourites))
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger favourite" data-id="{{ $sub->id }}" data-type="comment" data-is-favourite="true">Unfavourite</a>
                                                @else
                                                <a href="javascript:void(0)" class="btn btn-sm btn-info favourite" data-id="{{ $sub->id }}" data-type="comment" data-is-favourite="false">Favourite</a>
                                                @endif
                                                @if(auth()->id() == $sub->user->id)
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
    </div>
    <script>
        const dateFormat = (d) => [d.getFullYear(), d.getMonth() + 1, d.getDate()].join("-") + " " + [d.getHours(), d.getMinutes(), d.getSeconds()].join(":");
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
                    'X-Requested-With': "XMLHttpRequest",
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            }).then(r => r.json()).then(data => {
                console.log(data);
                if (type == "recipe") {
                    document.querySelector(`[data-bs-id='${id}']`).closest("div.d-flex.mb-1").remove()
                } else {
                    document.querySelector(`[data-bs-id='${id}']`).closest("div.d-flex.mb-3").remove()
                }
                // document.querySelector(`[data-bs-id='${id}']`).parentElement.parentElement.parentElement.parentElement.remove()
                const modal = bootstrap.Modal.getInstance(exampleModal);
                modal.hide();
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
        })
        let handelReplyForm = reply => {
            let form = reply.nextElementSibling;
            form.addEventListener("submit", e => {
                e.preventDefault();
                let formData = new FormData(e.target);
                let object_id = formData.get("object_id");
                fetch(`/recipe/${object_id}/sub-comment`, {
                    method: "POST",
                    headers: {
                        "Content-type": "application/json",
                        'X-Requested-With': "XMLHttpRequest",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        "comment": form.querySelector("input[name='comment']").value
                    }),
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
                                for (let i in err) {
                                    let el = e.target.querySelector(`#${i}_${recipeId}`);
                                    el.classList.add("is-invalid");
                                    let msgEl = e.target.querySelector(`#${i}_${recipeId} ~ .invalid-feedback`);
                                    msgEl.innerHTML = err[i].message;
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
                  <span style="font-size: 12px;">${created_at.replace("T","").split(".")[0]}</span>
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
                      <div class="invalid-feedback"></div>
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
                let replyLinks = card.querySelectorAll(".reply");
                Array.from(replyLinks).forEach(reply => {
                    handelReply(reply);
                    handelReplyForm(reply);
                })
            }

        });


        let favourites = document.querySelectorAll(".favourite");
        Array.from(favourites).forEach(favourite => {
            handelFavourite(favourite);
        })
        let publishes = document.querySelectorAll(".publish");
        Array.from(publishes).forEach(publish => {
            publish.addEventListener("click", e => {
                e.preventDefault();
                let {
                    id,
                    isPublished
                } = e.target.dataset
                console.log(id, isPublished);
                if (isPublished == "true") {
                    fetch(`/recipe/${id}/publish`, {
                        method: "DELETE",
                        headers: {
                            "Content-type": "application/json",
                            'X-Requested-With': "XMLHttpRequest",
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    }).then(j => j.json()).then(d => {
                        e.target.dataset.isPublished = "false"
                        e.target.innerText = "Publish"
                        e.target.classList.remove("btn-danger");
                        e.target.classList.add("btn-info");
                        document.querySelector(`.status-${id}`).innerText = "Pending"
                    })
                } else {
                    fetch(`/recipe/${id}/publish`, {
                        method: "Post",
                        headers: {
                            "Content-type": "application/json",
                            'X-Requested-With': "XMLHttpRequest",
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    }).then(j => j.json()).then(d => {
                        e.target.dataset.isPublished = "true"
                        e.target.innerText = "Unpublish"
                        e.target.classList.remove("btn-info");
                        e.target.classList.add("btn-danger");
                        document.querySelector(`.status-${id}`).innerText = "Pass"
                    })
                }
            })
        })
        let rejects = document.querySelectorAll(".reject");
        Array.from(rejects).forEach(reject => {
            reject.addEventListener("click", e => {
                e.preventDefault();
                let {
                    id,
                    isRejected
                } = e.target.dataset
                console.log(id, isRejected);
                if (isRejected == "true") {
                    fetch(`/recipe/${id}/reject`, {
                        method: "DELETE",
                        headers: {
                            "Content-type": "application/json",
                            'X-Requested-With': "XMLHttpRequest",
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    }).then(j => j.json()).then(d => {
                        e.target.dataset.isRejected = "false";
                        e.target.innerText = "Reject";
                        e.target.classList.add("btn-danger");
                        e.target.classList.remove("btn-info");
                        e.target.previousElementSibling.style.display = "inline-block";
                        document.querySelector(`.status-${id}`).innerText = "Pending";
                        e.target.previousElementSibling.dataset.isPublished = "false";
                        e.target.previousElementSibling.innerText = "Publish";
                        e.target.previousElementSibling.classList.remove("btn-danger")
                        e.target.previousElementSibling.classList.add("btn-info")
                        let overlay = e.target.closest(".card").querySelector(".card-body > div")
                        overlay.classList.remove("overlay");
                    })
                } else {
                    fetch(`/recipe/${id}/reject`, {
                        method: "Post",
                        headers: {
                            "Content-type": "application/json",
                            'X-Requested-With': "XMLHttpRequest",
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    }).then(j => j.json()).then(d => {
                        e.target.dataset.isRejected = "true";
                        e.target.classList.remove("btn-danger");
                        e.target.classList.add("btn-info");
                        e.target.innerText = "Restore";
                        e.target.previousElementSibling.style.display = "none";
                        document.querySelector(`.status-${id}`).innerText = "Rejected";
                        let overlay = e.target.closest(".card").querySelector(".card-body > div")
                        overlay.classList.add("overlay");
                    })
                }
            })
        })
    </script>
</div>
</div>

@endsection