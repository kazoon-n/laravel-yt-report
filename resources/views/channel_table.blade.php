@extends('layouts.app')

@section('content')
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Icon</th>
            <th scope="col">Channel Name</th>
            <th scope="col">Subscribers</th>
            <th scope="col">Remove Subscribe</th>
        </tr>
    </thead>
    <tbody>
        @foreach($channels as $channel)
        <tr>
            <td scope="row">
                <img src="{{ asset($channel['icon']) }}" class="img-thumbnail img-fluid thumbnail" alt="image">
            </td>
            <td>
                <a href="/video_list/{{$channel['id']}}" class="card-text d-block elipsis">{{ $channel['name'] }}</a>
            </td>
            <td>
                @if($channel['subscriber'] < 0)
                    Unknown
                @else
                    {{ $channel['subscriber'] }}
                @endif
            </td>
            <td>{{ $channel['id'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection