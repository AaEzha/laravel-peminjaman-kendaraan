<!DOCTYPE html>
<html lang="en">

@include('layouts-auth.header')

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

            @include('layouts-auth.logo')

            <div class="card card-primary">
              <div class="card-header"><h4>Register</h4></div>

              @if ($message = Session::get('success'))
              <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ $message }}</strong>
              </div>
            @endif

            @if ($message = Session::get('error'))
              <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
              </div>
            @endif
              <div class="card-body">
                <form method="post" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control p_input" required>
                </div>
                <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control p_input" required>
                </div>
                <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control p_input" required>
                </div>

                <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block enter-btn">Register</button>
                </div>

                <p class="mt-5 text-muted text-center">Already have an Account?<a href="{{ route('login') }}"> Sign In</a></p>
                </form>
              </div>
            </div>
            @include('layouts-auth.footer')
          </div>
        </div>
      </div>
    </section>
  </div>

  @include('layouts-auth.script')

</body>
</html>
