@extends('layouts.login_header')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-5 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo center row">
                <div class="col-12 text-center">
                    <img src="{{asset('images/m.png')}}" alt="logo">
                </div>
              </div>
              <h4>{{ __('Reset Password') }}</h4>
              {{-- <h6 class="font-weight-light">Happy to see you again!</h6> --}}
              @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.update') }}" onsubmit='show()'>
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail">Email</label>
                    <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                            <i class="ti-user text-primary"></i>
                        </span>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-control-lg border-left-0" id="exampleInputEmail" placeholder="Email">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword">Password</label>
                    <div class="input-group">
                      <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                          <i class="ti-lock text-primary"></i>
                        </span>
                      </div>
                      <input type="password" name='password' class="form-control form-control-lg border-left-0" id="exampleInputPassword" placeholder="Password" required>
                      @if ($errors->has('password'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif                   
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword">Confirm Password</label>
                    <div class="input-group">
                      <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                          <i class="ti-lock text-primary"></i>
                        </span>
                      </div>
                      <input id="password-confirm"  name="password_confirmation"  type="password" class="form-control form-control-lg border-left-0" id="exampleInputPassword" placeholder="Password" required>
                                      
                    </div>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Reset Password</button>
                  <a href="{{ route('login') }}" onclick='show()' class="auth-link text-black">Back to Login Page</a>
                </div>
                <div class="my-3">
                 
                </div>
                {{-- <div class="mb-2 d-flex">
                  <button type="button" class="btn btn-facebook auth-form-btn flex-grow me-1">
                    <i class="ti-facebook me-2"></i>Facebook
                  </button>
                  <button type="button" class="btn btn-google auth-form-btn flex-grow ms-1">
                    <i class="ti-google me-2"></i>Google
                  </button>
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="register-2.html" class="text-primary">Create</a>
                </div> --}}
           
            </div>
          </div>
          <div class="col-lg-7 login-half-bg d-flex flex-row">
            <p class="text-default font-weight-medium text-center flex-grow align-self-end">Copyright &copy; {{date('Y')}}  All rights reserved.</p>
          </div>
        </form>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
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
