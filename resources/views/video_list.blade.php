@extends('layouts.app')

@section('content')
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Thumbnail</th>
            <th scope="col">Video Title</th>
            <th scope="col">Tag</th>
            <th scope="col">Views</th>
            <th scope="col">Likes</th>
            <th scope="col">Comment</th>
        </tr>
    </thead>
    <tbody>
        @foreach($videos as $video)
        <tr>
            <td scope="row">
                <img src="{{ asset($video['thumbnail']) }}" class="img-thumbnail img-fluid thumbnail" alt="image">
            </td>
            <td>
                <a href="/video_detail/{{$video['id']}}" class="card-text d-block elipsis">{{ $video['name'] }}</a>
            </td>
            <td>Tags</td>
            <td>{{ $video['views'] }}</td>
            <td>{{ $video['likes'] }}</td>
            <td>{{ $video['comments'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection