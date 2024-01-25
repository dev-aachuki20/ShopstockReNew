@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')
@section('title')@lang('quickadmin.qa_change_password') @endsection
@section('customCss')
<meta name="csrf-token" content="{{ csrf_token() }}" >
@endsection

@section('main-content')

<section class="section">
    <div class="section-body">
      <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-8">
          <div class="card">
            <div class="padding-20">
                  <form method="post" class="needs-validation">
                    <div class="card-header">
                      <h4>@lang('quickadmin.qa_reset_password') </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{route('reset-password')}}">
                            @csrf
                            <div class="form-group">
                                <label for="currentpassword">@lang('quickadmin.qa_current_password')</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <i class="fas fa-lock"></i>
                                    </div>
                                  </div>
                                  <input type="password" value="{{ old('currentpassword') }}" id="currentpassword" class="form-control  @error('currentpassword') is-invalid @enderror" name="currentpassword" tabindex="1"   autofocus>
                                  @error('currentpassword')
                                  <div class="invalid-feedback">
                                    {{ $message }}
                                  </div>
                                  @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">@lang('quickadmin.qa_new_password')</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <i class="fas fa-lock"></i>
                                    </div>
                                  </div>
                                  <input type="password" value="{{ old('password') }}" id="password" class="form-control  @error('password') is-invalid @enderror" name="password" tabindex="1"   autofocus>
                                  @error('password')
                                  <div class="invalid-feedback">
                                    {{ $message }}
                                  </div>
                                  @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">@lang('quickadmin.qa_confirm_password')</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <i class="fas fa-lock"></i>
                                    </div>
                                  </div>
                                  <input type="password" value="{{ old('password_confirmation') }}" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" tabindex="1"   autofocus>
                                  @error('password_confirmation')
                                  <div class="invalid-feedback">
                                    {{ $message }}
                                  </div>
                                  @enderror
                                </div>
                            </div>

                            <div class="form-group">
                            <button type="submit" class="btn btn-submit-block btn-lg btn-block" tabindex="4">
                                @lang('quickadmin.qa_change_password')
                            </button>
                            </div>
                        </form>
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

@endsection
