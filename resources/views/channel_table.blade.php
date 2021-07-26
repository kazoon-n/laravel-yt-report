@extends('layouts.app')

@section('javascript')
<script src="/js/utility.js"></script>
@endsection

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
                @if($channel['subscriber'] < 0) Unknown @else {{ $channel['subscriber'] }} @endif </td>
            <td>
                <form action="{{ route('remove_channel') }}" id="delete-form" method="POST">
                    @csrf
                    <input type="hidden" name="channel_id" value="{{ $channel['id'] }}" />
                    <i class="fas fa-trash mr-3" onclick="deleteHandle(event);"></i>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection