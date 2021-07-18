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
        <tr>
            @foreach($channels as $channel)
            <th scope="row">
                <img src="{{ asset('img/'.$channel['icon']) }}" class="img-thumbnail img-fluid thumbnail" alt="image">
            </th>
            <td>{{ $channel['name'] }}</td>
            <td>{{ $channel['subscriber'] }}</td>
            <td>{{ $channel['id'] }}</td>
            @endforeach
        </tr>
    </tbody>
</table>
@endsection