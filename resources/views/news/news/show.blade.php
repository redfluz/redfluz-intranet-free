@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="md:col-9">
                {{-- Back Link --}}
                <div class="mb-3">
                    <a class="btn btn-sm btn-solid theme-secondary" href="{{ route('news.news.index') }}">
                        <i class="me-1" data-lucide="arrow-left"></i>
                        {{ __('common.actions.back') }}
                    </a>
                </div>

                {{-- NewsCategory Badge & Title --}}
                <span class="badge theme-primary mb-2"> {{ $news->category->name }} </span>
                <h1 class="fw-bold mb-3">{{ $news->title }}</h1>

                {{-- Article Meta --}}
                <div class="fg-secondary d-flex align-items-center mb-4 gap-3">
                    <small>
                        <i class="me-1" data-lucide="calendar"></i>
                        {{ __('common.labels.publish_on') }} {{ $news->published_at->format('F d, Y') }}
                    </small>
                    <small>&bull;</small>
                    <small>
                        <i class="me-1" data-lucide="eye"></i>
                        {{ $news->views_count }} {{ __('common.actions.views') }}
                    </small>
                </div>

                {{-- Featured Image --}}
                @if ($news->featuredImageUrl())
                    <div class="mb-4">
                        <img class="img-fluid rounded-5 shadow-sm" src="{{ $news->featuredImageUrl() }}"
                            alt="{{ $news->title }}" style="width: 100%; max-height: 400px; object-fit: cover" />
                    </div>
                @endif

                {{-- Short Excerpt --}}
                @if ($news->short_description)
                    <p class="lead fg-secondary border-start border-primary rounded-end mb-4 border-4 py-1 ps-3">
                        {{ $news->short_description }}
                    </p>
                @endif

                {{-- Content Body --}}
                <div class="article-content fs-5 mb-5" style="line-height: 1.8">
                    {!! Purify::clean($news->content) !!}
                </div>

                <hr class="my-5" />

                {{-- COMMENTS SECTION --}}
                <div class="comments-section">
                    <h3 class="fw-bold mb-4">
                        <i class="fg-primary me-2" data-lucide="message-circle"></i>
                        {{ __('common.labels.comments') }}
                    </h3>

                    {{-- Form to Post Main NewsComment --}}
                    @auth
                        <form class="ajax-comment-form mb-4" action="{{ route('news.comments.store') }}" method="POST">
                            @csrf
                            <input name="news_id" type="hidden" value="{{ $news->id }}" />
                            <div class="card card-body border-0 shadow-sm">
                                <div class="mb-3">
                                    <label class="visually-hidden" for="comment">{{ __('common.labels.join_comment') }}</label>
                                    <textarea id="comment" class="form-control" name="comment" rows="3"
                                        placeholder="{{ __('common.labels.join_comment') }}..." required></textarea>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-solid theme-primary px-3" type="submit">
                                        <i class="me-1" data-lucide="send"></i>
                                        {{ __('common.actions.post') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert theme-light mb-4 border text-center">
                            <a class="fw-bold" href="{{ route('login') }}"> log in {{ __('common.labels.login') }} </a>
                        </div>
                    @endauth

                    {{-- Comments List Container --}}
                    <div class="d-flex flex-column gap-3" id="comments-container">
                        @forelse ($news->comments as $comment)
                            <div class="card border-0 shadow-sm" id="comment-card-{{ $comment->id }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar theme-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-2 text-white"
                                                style="width: 36px; height: 36px">
                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $comment->user->name }}</h6>
                                                <small class="fg-secondary">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>

                                        {{-- Delete NewsComment Button (AJAX) --}}
                                        @auth
                                            @if (auth()->id() === $comment->user_id)
                                                <button class="btn btn-link fg-danger btn-delete-comment p-0"
                                                    data-id="{{ $comment->id }}" type="button">
                                                    <i data-lucide="trash-2"></i>
                                                </button>
                                            @endif
                                        @endauth
                                    </div>

                                    <p class="card-text fg-secondary mb-2">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="fg-secondary py-4 text-center" id="no-comments-msg">
                                {{ __('common.labels.not_found_record') }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Comment Confirmation Dialog --}}
    <dialog class="dialog" id="deleteCommentModal">
        <div class="dialog-header">
            <h5 class="dialog-title">Delete comment?</h5>
            <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
        </div>
        <div class="dialog-body">
            <p>You won't be able to revert this!</p>
        </div>
        <div class="dialog-footer">
            <button class="btn btn-solid theme-secondary" data-bs-dismiss="dialog" type="button">Cancel</button>
            <button class="btn btn-solid theme-danger" type="button" id="confirmDeleteComment">Yes, delete it!</button>
        </div>
    </dialog>

    {{-- Success Toast Dialog --}}
    <dialog class="dialog" id="successToastModal">
        <div class="dialog-header">
            <button class="btn-close" data-bs-dismiss="dialog" type="button"></button>
        </div>
        <div class="dialog-body">
            <p id="successToastText"></p>
        </div>
    </dialog>
@endsection

@section('script')
    <script type="module">
        document.addEventListener('submit', function(e) {
            // Intercept standard submission for AJAX NewsComment / Reply forms
            const form = e.target.closest('.ajax-comment-form');
            if (form) {
                e.preventDefault();

                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            Accept: 'application/json',
                        },
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            const comment = data.comment;
                            const emptyMsg = document.getElementById('no-comments-msg');
                            if (emptyMsg) emptyMsg.remove();

                            if (comment.parent_id) {
                                // Render reply dynamically into parent comment
                                const repliesList = document.getElementById('replies-list-' + comment
                                    .parent_id);
                                if (repliesList) {
                                    repliesList.classList.remove('d-none');
                                    const replyHtml = `
 <div class="mb-2 d-flex justify-content-between align-items-start" id="comment-card-${comment.id}">
 <div>
 <div class="d-flex align-items-center mb-1">
 <strong class="small me-2">${comment.user_name}</strong>
 <small class="fg-secondary" style="font-size: 0.75rem;">${comment.created_at}</small>
 </div>
 <p class="small fg-secondary mb-1">${comment.comment}</p>
 </div>
 <button type="button" class="btn btn-sm btn-link fg-danger p-0 btn-delete-comment" data-id="${comment.id}">
 <i data-lucide="x"></i>
 </button>
 </div>
 `;
                                    repliesList.insertAdjacentHTML('beforeend', replyHtml);
                                }

                                // Close the collapse form box
                                const replyBox = document.getElementById('reply-box-' + comment.parent_id);
                                if (replyBox && window.bootstrap) {
                                    const collapseInstance =
                                        bootstrap.Collapse.getInstance(replyBox) || new bootstrap.Collapse(
                                            replyBox);
                                    collapseInstance.hide();
                                }
                            } else {
                                // Render top-level main comment
                                const commentsContainer = document.getElementById('comments-container');
                                const initialChar = comment.user_name.charAt(0).toUpperCase();
                                const commentHtml = `
 <div class="card border-0 shadow-sm" id="comment-card-${comment.id}">
 <div class="card-body">
 <div class="d-flex align-items-center justify-content-between mb-2">
 <div class="d-flex align-items-center">
 <div class="avatar theme-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 36px; height: 36px;">
 ${initialChar}
 </div>
 <div>
 <h6 class="mb-0 fw-bold">${comment.user_name}</h6>
 <small class="fg-secondary">${comment.created_at}</small>
 </div>
 </div>
 <button type="button" class="btn btn-sm btn-link fg-danger p-0 btn-delete-comment" data-id="${comment.id}">
 <i data-lucide="trash-2"></i>
 </button>
 </div>
 <p class="card-text fg-secondary mb-2">${comment.comment}</p>
 </div>
 </div>
 `;
                                commentsContainer.insertAdjacentHTML('afterbegin', commentHtml);
                            }

                            // Reset input box
                            form.reset();
                        }
                    })
                    .catch((err) => console.error('Error:', err))
                    .finally(() => {
                        if (submitBtn) submitBtn.disabled = false;
                    });
            }
        });

        document.addEventListener('click', function(e) {
            // 1. AJAX Like / Unlike Handler
            const likeBtn = e.target.closest('.btn-like');
            if (likeBtn) {
                const commentId = likeBtn.getAttribute('data-id');
                const type = likeBtn.getAttribute('data-type');
                const commentCard = document.getElementById('comment-card-' + commentId);

                fetch(`/comments/${commentId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            type: type,
                        }),
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success && commentCard) {
                            commentCard.querySelector('.like-count').innerText = data.likes_count;
                            commentCard.querySelector('.unlike-count').innerText = data.unlikes_count;
                        }
                    })
                    .catch((err) => console.error('Error:', err));
            }

            // 2. AJAX Delete NewsComment Handler with Dialog & Fade-Out Animation
            const deleteBtn = e.target.closest('.btn-delete-comment');
            if (deleteBtn) {
                const commentId = deleteBtn.getAttribute('data-id');
                const deleteModal = document.getElementById('deleteCommentModal');
                const confirmBtn = document.getElementById('confirmDeleteComment');

                const handleConfirm = () => {
                    bootstrap.Dialog.getInstance(deleteModal).hide();
                    confirmBtn.removeEventListener('click', handleConfirm);

                    fetch(`/comments/${commentId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                        })
                        .then((res) => res.json())
                        .then((data) => {
                            if (data.success) {
                                const commentElement = document.getElementById('comment-card-' +
                                    commentId);

                                if (commentElement) {
                                    commentElement.style.transition =
                                        'opacity 0.3s ease, transform 0.3s ease';
                                    commentElement.style.opacity = '0';
                                    commentElement.style.transform = 'translateY(-10px)';
                                    setTimeout(() => commentElement.remove(), 300);
                                }

                                // Show auto-closing success dialog
                                const successModal = document.getElementById('successToastModal');
                                document.getElementById('successToastTitle').textContent =
                                    @json(__('news_comments.message.deleted_title'));
                                document.getElementById('successToastText').textContent = data.message;
                                const successInstance = bootstrap.Dialog.getOrCreateInstance(successModal);
                                successInstance.show();
                                setTimeout(() => successInstance.hide(), 1500);
                            }
                        })
                        .catch((err) => console.error('Error:', err));
                };

                confirmBtn.addEventListener('click', handleConfirm);
                bootstrap.Dialog.getOrCreateInstance(deleteModal).show();
            }
        });
    </script>
@endsection
