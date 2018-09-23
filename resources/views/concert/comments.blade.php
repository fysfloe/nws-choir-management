@extends('layouts.concert')

@section('concertContent')

    <div class="tab-pane fade show active" id="comments" role="tabpanel" aria-labelledby="info-tab">
        <div class="row comment-stream">
            <div class="col">
                @if (count($concert->comments) > 0)
                    @foreach ($concert->comments as $comment)
                        <div class="comment row">
                            <div class="col-md-3 flex comment-info">
                                
                                    <div class="avatar">
                                        @if ($comment->user->avatar)
                                            <img src="{{ asset('/storage/avatars/' . $comment->user->avatar) }}" alt="{{ $comment->user->firstname . ' ' . $comment->user->surname }}">
                                        @else
                                            <img src="{{ asset('img/default_avatar.svg') }}">
                                        @endif
                                    </div>
                                <div>
                                    <strong>
                                        {{ $comment->user->firstname }} {{ $comment->user->surname }}
                                        &nbsp;<span class="accept-decline oi oi-media-record {{ $concert->promises->contains($comment->user) ? 'text-success' : ($concert->denials->contains($comment->user) ? 'text-danger' : 'text-muted') }}"></span>
                                    </strong><br> 
                                    <small class="text-muted">
                                        {{ $comment->created_at->format('d.m.Y H:i') }}
                                        @if ($comment->private)
                                            <span class="oi oi-lock-locked" data-toggle="tooltip" title="{{ __('This comment can be seen only by you and admins.') }}"></span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-9 comment-content">
                                {!! nl2br($comment->comment) !!}
                            </div>
                        </div>
                    @endforeach
                @else
                    {{ __('No comments yet.') }}
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col">
            {{ Form::model(new App\Comment(), ['route' => ['concert.createComment', $concert]]) }}
                @include('comment._form')
            {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection
