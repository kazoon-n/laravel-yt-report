@extends('layouts.app')

@section('content')
<h4 class='m-3'>Add Channel</h4>
<form class="m-3" action="{{ route('search_channel') }}" method="POST">
    @csrf
    <div class="row">
        <div class="mb-3 ml-0 col-6">
            <label for="channel-url" class="form-label">Channel URL*</label>
            <input type="text" class="form-control" id="channel-url" name="channel_url">
        </div>
    </div>
    @error('content')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <button type="submit" class="btn btn-primary">Search</button>
</form>

@if($flag)
<h4 class='m-3'>Confirmation</h4>
<form class="m-3" action="{{ route('add_channel') }}" method="POST">
    @csrf
    <div class="row">
        <div class="mb-3 ml-0 col-6">
            <input type="text" class="form-control" id="channel_id" name="channel_id" value="{{ $channel['snippet']['channelId'] }}" hidden>
            <img src="{{ asset($channel['snippet']['thumbnails']['default']['url']) }}" class="img-thumbnail img-fluid" alt="image">
            <label for="channel-name" class="form-label">{{ $channel['snippet']['channelTitle'] }}</label>
            <label for="channel-name" class="form-label">{{ $channel['snippet']['description'] }}</label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Add Confirm</button>
</form>
@endif

@endsection