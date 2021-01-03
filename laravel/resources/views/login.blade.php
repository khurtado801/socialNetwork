@extends('layouts.master')

@section('title')
    Welcome!
@endsection

@section('content')
    @include('includes.message-block')
    <div class="row">
    <div class="col-sm-12">
        <div class="registration-form">
            <form action="{{ route('signin') }}" method="post">

            <div class="row">
                    <div class="col-sm-6 float-left">
                        <div class="mx-auto ">
                            <h3>Sign Up</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 float-right">
                        <div class="mx-auto ">
                            <h3><a href="{{ route('dashboard') }}" class="signup-image-link">I need to register</a></h3>
                        </div>
                    </div>
                </div>

                <div class="form-icon">
                    <span><i class="icon icon-user"></i></span>
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input class="form-control item" type="text" name="email" id="email"
                        value="{{ Request::old('email') }}" placeholder="Email">
                </div>


                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input class="form-control item" type="password" name="password" id="password"
                        value="{{ Request::old('password') }}" placeholder="Password">
                </div>

                <button type="submit" class="btn btn-block create-account">Submit</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>

            <div class="social-media">
            </div>

        </div>
    </div>
</div>

@endsection
