@extends('layouts.app')

@section('javascript')
<script src="/js/utility.js"></script>
@endsection

@section('content')
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">User Name</th>
            <th scope="col">User Email</th>
            <th scope="col">Delete User</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td scope="row">
                {{ $user['id'] }}
            </td>
            <td>
                {{ $user['name'] }}
            </td>
            <td>
                {{ $user['email'] }}
            </td>
            <td>
                <form action="{{ route('remove_user') }}" id="delete-form" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user['id'] }}" />
                    <i class="fas fa-trash mr-3" onclick="deleteHandle(event);"></i>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<div>
    <div class="row justify-content-left">
        <div class="col-md-8">
            <div>
                <div>{{ __('Register') }}</div>

                <div>
                    <form method="POST" action="{{ route('register_user') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection