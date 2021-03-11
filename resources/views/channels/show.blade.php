@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        {{ $channel->name }}

                        <a href="{{ route('channel.upload', $channel->id) }}">Upload Videos</a>

                    </div>

                    <div class="card-body">

                        @if( $channel->editable())
                            <form id="update-channel-form" action="{{ route('channels.update', $channel->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                @endif
                                <div class="form-group row justify-content-center">
                                    <div class="channel-avatar">
                                        @if( $channel->editable())
                                            <div class="channel-avatar-overlay" onclick="document.getElementById('image').click()"></div>
                                        @endif
                                        <img width="100" height="100" src="{{ $channel->image() }}" alt="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h4 class="text-center">
                                        {{ $channel->name }}
                                    </h4>
                                    <p class="text-center">
                                        {{ $channel->description }}
                                    </p>

                                    <div class="text-center">
                                        <subscribe-button :channel="{{ $channel }}" :initial-subscriptions="{{ $channel->subscriptions }}"></subscribe-button>
                                    </div>
                                </div>
                                @if( $channel->editable())
                                    <input onchange="document.getElementById('update-channel-form').submit()" style="display: none" id="image" type="file" name="image">

                                    <div class="form-group">
                                        <label for="name" class="form-control-label">
                                            Name
                                        </label>
                                        <input id="name" name="name" value="{{ $channel->name }}" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="description" class="form-control-label">
                                            Description
                                        </label>
                                        <textarea id="description" name="description" rows="3" class="form-control">{{ $channel->description }}</textarea>
                                    </div>

                                    @if($errors->any())
                                        <ul class="text-danger list-group mb-5">
                                            @foreach($errors->all() as $error )
                                                <li class="list-group-item">
                                                    {{ $error }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <button type="submit" class="btn btn-info">
                                        Update Channel
                                    </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Videos</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Views</th>
                            <th>Status</th>
                            </thead>
                            <tbody>
                            @foreach($videos as $video)
                                <tr>
                                    <td><img width="40" height="40" src="{{ asset( $video->thumbnail ) }}" alt=""/></td>
                                    <td>{{ $video->title }}</td>
                                    <td>{{ $video->views }}</td>
                                    <td>{{ $video->percentage === 100 ? 'Live' : 'Processing' }}</td>
                                    @if( $video->percentage === 100 )
                                        <td>
                                            <a href="{{ route('videos.show', $video->id) }}">View</a>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row-justify-content-center">
                            {{ $videos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
