@extends('layouts.auth')

@section('main-content')
<section class="section">
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
          <div class="login-brand login-brand-color">
             {{-- <img alt="@lang('quickadmin.qa_company_name')" src="{{ asset('admintheme/assets/img/logo.png') }}" height="80" width="80"/> --}}
             <span>{{ getSetting('company_name') ?? ''}}</span>
          </div>
          @if (Session::has('success'))
          <div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                <button class="close" data-dismiss="alert">
                  <span>×</span>
                </button>
                {{ Session::get('success') }}    </div>
          </div>
          @endif
          @error('wrongcrendials')
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {{ $message }}
                </div>
            </div>
            @enderror
          <div class="card p-5 loginauth">
            <div class="card-header card-header-auth p-0">
              <h4>Login to Superio</h4>
            </div>
            <div class="card-body p-0">
              <form method="POST" action="{{route("authenticate")}}"  novalidate="">
                @csrf

                <div class="form-group">
                    <label for="username">@lang('quickadmin.qa_username')</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user"></i>
                        </div>
                      </div>
                      <input type="text" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" name="username" tabindex="1" required autofocus>
                      @error('username')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">@lang('quickadmin.qa_password')</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-lock"></i>
                        </div>
                      </div>
                      <input type="password" class="form-control  @error('password') is-invalid @enderror" name="password" id="password" tabindex="2" required>
                      <div class="input-group-append passwordfor">
                        <div class="input-group-text toggle-password" data-toggle="#password">
                          <i class="fas fa-eye view-password"></i>
                          <i class="fas fa-eye-slash hide-password" style="display: none;"></i>
                        </div>
                      </div>

                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group d-flex align-items-center justify-content-between">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="remember_me" class="custom-control-input" tabindex="3" id="remember-me">
                      <label class="custom-control-label" for="remember-me">@lang('quickadmin.qa_remember_me')</label>
                    </div>
                    <div class="forgotpass">
                        <a href="{{route('forgot.password')}}" class="text-medium">
                            @lang('quickadmin.qa_forgot_password')
                        </a>
                    </div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-lg btn-block btn-auth-color" tabindex="4">
                    @lang('quickadmin.qa_login')
                  </button>
                </div>
              </form>

            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  @endsection

@section('customJS')
  <script type="text/javascript">
    $(".toggle-password").click(function() {
        var inputId = $(this).data("toggle");
        var input = $(inputId);
        var viewPasswordIcon = $(this).find(".view-password");
        var hidePasswordIcon = $(this).find(".hide-password");

        if (input.attr("type") === "password") {
            input.attr("type", "text");
            viewPasswordIcon.hide();
            hidePasswordIcon.show();
        } else {
            input.attr("type", "password");
            viewPasswordIcon.show();
            hidePasswordIcon.hide();
        }
    });

   </script>
@endsection
