@extends('layouts.master')

@section('style')
    <style>
        .bg-custom-default {
            background-color: #F5F8FF
        }

        .auth .auth-form-light {
            background: #ffffff;
        }
    </style>
@endsection

@section('body')
<body class="bg-custom-default">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5 mt-4">
                            <div class="brand-logo" style="text-align:center;">
                                <img src="{{ asset('img/store.png') }}" alt="logo" style="width: 40%">
                            </div>

                            <div class="mt-4">
                                @if(session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
    
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>

                            <form class="pt-3" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                  <label for="kode_karyawan">Username <span style="color: red;">*</span></label>
                                  <input type="text" class="form-control" id="kode_karyawan" name="kode_karyawan" placeholder="Username">
                                  @error('kode_karyawan')
                                     <span style="color: red;">Silahkan Isi Username</span>
                                  @enderror
                                </div>
                                <div class="form-group" style="position: relative">
                                  <label for="password">Password <span style="color: red;">*</span></label>
                                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                  <i class="fas fa-fw fa-eye" id="togglePassword" style="position: absolute; top: 72%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                                  @error('password')
                                      <span style="color: red;">Silahkan Isi Password</span>
                                  @enderror
                                </div>
                                <div class="form-group">
                                  <label for="role">Role <span style="color: red;">*</span></label>
                                  <select name="role" id="role" class="form-control">
                                    <option value="">--Please Select Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="kasir">Kasir</option>
                                  </select>
                                  @error('role')
                                      <span style="color: red;">Silahkan Pilih Role</span>
                                  @enderror
                                </div>
                                <div class="mt-3">
                                  <button class="btn btn-block btn-dark btn-lg font-weight-medium auth-form-btn" type="submit">SIGN IN</button>
                                </div>
                                {{-- <div class="text-center mt-4 font-weight-light">
                                  Don't have an account? <a href="register.html" class="text-primary">Create</a>
                                </div> --}}
                              </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    @include('layouts.inc.script')
    <script>
        $(document).ready(function() {
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');
                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
      </script>
</body>
@endsection