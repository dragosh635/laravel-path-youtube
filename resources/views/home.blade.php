@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="">
                            <input type="text" class="form-control" placeholder="Search" name="search"/>
                        </form>


                    </div>
                </div>

                @if($channels->count() !== 0 )
                    <div class="card mt-4">
                        <div class="card-header">Channels</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <th>Name</th>
                                <th>Link</th>
                                </thead>
                                <tbody>
                                @foreach($channels as $channel)
                                    <tr>
                                        <td>{{ $channel->name }}</td>
                                        <td><a href="{{ route('channels.show', $channel->id) }}" class="btn btn-info btn-sm">View Channel</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row justify-content-center">{{ $channels->appends(request()->query())->links() }}</div>
                        </div>
                    </div>
                @endif

                @if($videos->count() !== 0 )
                    <div class="card mt-4">
                        <div class="card-header">Videos</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <th>Name</th>
                                <th>Link</th>
                                </thead>
                                <tbody>
                                @foreach($videos as $video)
                                    <tr>
                                        <td>{{ $video->title }}</td>
                                        <td><a href="{{ route('videos.show', $video->id) }}" class="btn btn-info btn-sm">View Video</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row justify-content-center">{{ $videos->appends(request()->query())->links() }}</div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
