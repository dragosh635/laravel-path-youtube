@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if( $video->editable() )
                        <form action="{{ route('videos.update', $video->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @endif
                            <div class="card-header">{{ $video->title }}</div>

                            <div class="card-body">
                                <video-js id="video" class="vjs-default-skin" controls preload="auto" width="640" height="268">
                                    <source src="{{ asset( Storage::url("videos/{$video->id}.m3u8") ) }}" type="application/x-mpegURL">
                                </video-js>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mt-3">
                                            @if( $video->editable())
                                                <input type="text" name="title" class="form-control" value="{{ $video->title }}"/>
                                            @else
                                                {{ $video->title }}
                                            @endif
                                        </h4>
                                        {{ $video->views }} {{ Str::plural('view', $video->views) }}
                                    </div>

                                    <votes entity_owner="{{ $video->channel->user_id }}" entity_id="{{ $video->id }}" :default_votes="{{ $video->votes }}"></votes>
                                </div>
                                <hr/>

                                <div>
                                    @if( $video->editable() )
                                        <textarea name="description" cols="3" rows="3" class="form-control">{{ $video->description }}</textarea>
                                        <div class="text-right mt-4">
                                            <button class="btn btn-info btn-sm" type="submit">Update video details</button>
                                        </div>
                                    @else
                                        {{ $video->description }}
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-5">
                                    <div class="media">
                                        <img src="https://picsum.photos/200" alt="" class="rounded-circle" width="50" height="50" class="mr-3" alt="Alt Image"/>
                                        <div class="media-body ml-2">
                                            <h5 class="mt-0 mb-0">{{ $video->channel->name }}</h5>
                                            <span class="small">Published on {{ $video->created_at->toFormattedDateString() }}</span>
                                        </div>
                                    </div>
                                    <subscribe-button :channel="{{ $video->channel }}" :initial-subscriptions="{{ $video->channel->subscriptions }}"></subscribe-button>
                                </div>
                            </div>
                            @if( $video->editable() )
                        </form>
                    @endif
                </div>

                <comments :video="{{ $video }}"></comments>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://vjs.zencdn.net/7.10.2/video-js.css" rel="stylesheet"/>
    <style>
        .vjs-default-skin {
            width: 100%;
        }
        .thumbs-down-active, .thumbs-up-active {
            color: #3EA6FF;
        }
    </style>

@endsection

@section('scripts')
    <script src="https://vjs.zencdn.net/7.10.2/video.min.js"></script>
    <script>
        window.CURRENT_VIDEO = '{{ $video->id }}';
    </script>
    <script src="{{ asset('js/player.js') }}"></script>

@endsection
