@extends('layoutFront')

@section('title', 'Post')

@section('css')
    <!-- Bootstrap Validator -->
    <link rel="stylesheet" href="{{ asset('bootstrapValidator/css/bootstrapValidator.min.css') }}">
@stop

@section('content')

    <!-- Blog Post Content Column -->
    <div class="col-lg-8">

        <!-- Blog Post -->

        <!-- Title -->
        <h1>{{ $post->title }}</h1>


        <!-- Author -->
        <p class="lead">
            by <!--<a href="#">Start Bootstrap</a>-->
            <a href="{{ url('user', $hashids->encode($post->user_id), false) }}">{{ $post->user->first_name }}&nbsp;{{ $post->user->last_name }}</a>
            @foreach ($post->tags as $tag)
                <span class="badge" style="background-color: {{ $tag->color }}; vertical-align: 25%;">{{ $tag->name }}</span>
            @endforeach
        </p>

        <hr>

        <!-- Date/Time -->
        <p><span class="glyphicon glyphicon-time"></span> {{ $post->published_at->format('F d, Y \a\t g:i A') }}</p>

        <hr>

        @if (!empty($post->image_path))
            <!-- Display Image -->
            <img class="img-responsive" src="{{ asset($post->image_path) }}" alt="">
            <hr>
        @endif

        <!-- Post Content -->
        <p><?php echo $post->content_html; ?></p>
        <hr>

        <!-- Comments Form -->
        <div class="well">
            <h4>Leave a Comment:</h4>
            <!--<form role="form">-->
            {!! Form::model($post, array('route' => array('post.comment', $post->slug), 'id' => 'comment', 'method' => 'POST')) !!}
                <div class="form-group">
                    {!! Form::text('comment_name', Input::old('comment_name'), ['class' => 'form-control', 'placeholder' => 'Your Name']) !!}
                </div>
                <div class="form-group">
                    {!! Form::textarea('message', Input::old('message'), ['size' => '30x5', 'class' => 'form-control', 'placeholder' => 'Your Message']) !!}
                </div>
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                
            {!! Form::close() !!}
        </div>

        <hr>

        <!-- Posted Comments -->
        @foreach($comments as $comment)
            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object" src="http://placehold.it/64x64" alt="">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{{ $comment->name }}
                        <small>{{ $comment->published_at->format('F d, Y \a\t g:i A') }}</small>
                    </h4>
                    {{ $comment->message }}
                    <div class="expander btn btn-default btn-xs pull-right" style="">Reply</div>
                    <div style="margin-top: 20px; display: none;">
                        <div class="well">
                            <h4>Reply to {{ $comment->name }}:</h4>
                            {!! Form::model($comment, array('route' => array('post.reply', $comment->id), 'id' => 'reply', 'method' => 'POST')) !!}
                                <div class="form-group">
                                    {!! Form::text('reply_name', Input::old('reply_name'), ['class' => 'form-control', 'placeholder' => 'Your Name']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::textarea('reply', Input::old('reply'), ['size' => '30x3', 'class' => 'form-control', 'placeholder' => 'Your Message', 'style' => 'resize:none;']) !!}
                                </div>
                                {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-xs']) !!}
                            {!! Form::close() !!}
                        </div><!-- /. well -->
                    </div><!-- /. panel -->

                    @foreach($replies as $reply)
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" src="http://placehold.it/64x64" alt="">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">{{ $reply->name }}
                                    <small>{{ $reply->published_at->format('F d, Y \a\t g:i A') }}</small>
                                </h4>
                                {{ $reply->message }}
                            </div>
                        </div><!-- /.media -->
                    @endforeach
                </div><!-- /. media-body -->

            </div><!-- /. media -->
        @endforeach

    </div><!-- /.col-lg-8 -->

@stop

@section('scripts')
    <script src="{{ asset('bootstrapValidator/js/bootstrapValidator.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var comment_validator = $("#comment").bootstrapValidator({
                feedbackIcons: {
                    valid: "glyphicon glyphicon-ok",
                    invalid: "glyphicon glyphicon-remove", 
                    validating: "glyphicon glyphicon-refresh"
                }, 
                fields : {     
                    comment_name :{
                        message : "Name is required",
                        validators : {
                            notEmpty : {
                                message : "Please provide a name"
                            }, 
                            stringLength: {
                                min : 2, 
                                max: 32,
                                message: "Name must be between 2 and 32 characters long"
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9?$@#()'!,+\-=_:.&€£*%\s]+$/,
                                message: 'The full name can only consist of alphabetical, digits and punctuation'
                            }
                        }
                    },
                    message : {
                        message : "Message is required",
                        validators : {
                            notEmpty : {
                                message : "Please enter a message"
                            },
                            stringLength: {
                                min : 2, 
                                max: 255,
                                message: "Message must be between 2 and 255 characters long"
                            },
                            regexp: {
                                regexp: /^[a-zA-Z0-9?$@#()'!,+\-=_:.&€£*%\s]+$/,
                                message: 'The message can only consist of alphabetical, digits and punctuation'
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            var reply_validator = $("#reply").bootstrapValidator({
                feedbackIcons: {
                    valid: "glyphicon glyphicon-ok",
                    invalid: "glyphicon glyphicon-remove", 
                    validating: "glyphicon glyphicon-refresh"
                }, 
                fields : {     
                    reply_name :{
                        message : "Name is required",
                        validators : {
                            notEmpty : {
                                message : "Please provide a name"
                            }, 
                            stringLength: {
                                min : 2, 
                                max: 32,
                                message: "Name must be between 2 and 32 characters long"
                            },
                            regexp: {
                                regexp: /^[a-zA-Z\s]+$/,
                                message: 'The full name can only consist of alphabetical and spaces'
                            }
                        }
                    },
                    reply : {
                        message : "Message is required",
                        validators : {
                            notEmpty : {
                                message : "Please enter a message"
                            },
                            stringLength: {
                                min : 2, 
                                max: 255,
                                message: "Reply message must be between 2 and 255 characters long"
                            }
                        }
                    }
                }
            });
        });
    </script>
    
    <script>
        $(document).ready(function(){
            $('.expander').click(function(){
                $(this).next().slideToggle(200);
            });
        });
    </script>
@stop