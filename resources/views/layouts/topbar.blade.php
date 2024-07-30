@section('scripts')
    <!-- Add DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <!-- Your existing scripts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>1936Bois</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ... (other head elements) ... -->

    <style>
  .button-49,
  .button-49:after {
    width: 100px; /* Adjusted width */
    height: 50px; /* Adjusted height */
    line-height: 50px; /* Adjusted line-height */
    font-size: 14px; /* Adjusted font size */
    font-family: 'Bebas Neue', sans-serif;
    background: linear-gradient(45deg, transparent 5%, #FF013C 5%);
    border: 0;
    color: #fff;
    letter-spacing: 3px;
    box-shadow: 6px 0px 0px #00E6F6;
    outline: transparent;
    position: relative;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .button-49:after {
    --slice-0: inset(50% 50% 50% 50%);
    --slice-1: inset(80% -6px 0 0);
    --slice-2: inset(50% -6px 30% 0);
    --slice-3: inset(10% -6px 85% 0);
    --slice-4: inset(40% -6px 43% 0);
    --slice-5: inset(80% -6px 5% 0);

    content: 'ALTERNATE TEXT';
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 3%, #00E6F6 3%, #00E6F6 5%, #FF013C 5%);
    text-shadow: -3px -3px 0px #F8F005, 3px 3px 0px #00E6F6;
    clip-path: var(--slice-0);
  }

  .button-49:hover:after {
    animation: 1s glitch;
    animation-timing-function: steps(2, end);
  }

  @keyframes glitch {
    0% {
      clip-path: var(--slice-1);
      transform: translate(-20px, -10px);
    }
    10% {
      clip-path: var(--slice-3);
      transform: translate(10px, 10px);
    }
    20% {
      clip-path: var(--slice-1);
      transform: translate(-10px, 10px);
    }
    30% {
      clip-path: var(--slice-3);
      transform: translate(0px, 5px);
    }
    40% {
      clip-path: var(--slice-2);
      transform: translate(-5px, 0px);
    }
    50% {
      clip-path: var(--slice-3);
      transform: translate(5px, 0px);
    }
    60% {
      clip-path: var(--slice-4);
      transform: translate(5px, 10px);
    }
    70% {
      clip-path: var(--slice-2);
      transform: translate(-10px, 10px);
    }
    80% {
      clip-path: var(--slice-5);
      transform: translate(20px, -10px);
    }
    90% {
      clip-path: var(--slice-1);
      transform: translate(-10px, 0px);
    }
    100% {
      clip-path: var(--slice-1);
      transform: translate(0);
    }
  }

  @media (min-width: 768px) {
    .button-49,
    .button-49:after {
      width: 140px; /* Adjusted width */
      height: 60px; /* Adjusted height */
      line-height: 62px; /* Adjusted line-height */
      font-size: 16px; /* Adjusted font size */
    }
  }
</style>
</head>

<body>
    <header id="page-topbar" class="isvertical-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="index" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-dark-sm.png') }}" alt="" height="26">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-dark-sm.png') }}" alt="" height="26">
                        </span>
                    </a>

                    <a href="index" class="logo logo-light">
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="30">
                        </span>
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-light-sm.png') }}" alt="" height="26">
                        </span>
                    </a>
                </div>
<button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                    <i class="bx bx-menu align-middle"></i>
                </button>

                <!-- start page title -->
                <div class="page-title-box align-self-center d-none d-md-block">
                    <h4 class="page-title mb-0">@yield('page-title')</h4>
                </div>
                <!-- end page title -->

            </div>

            <div class="d-flex">

            <!-- Conditional Rendering for General Users -->
                @if (auth()->check() && auth()->user()->user_type == 'general')
                    <!-- Cart Icon -->
                    <div class="header-item ms-2">
                        <p></p>
                        <a href="{{ route('cart.index') }}" class="text-decoration-none">
                            <i class="mdi mdi-cart" style="font-size: 24px; color: black;"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="badge badge-pill badge-danger" style="background-color: red; color: white; border-radius: 50%;">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </a>
                    </div>
                @endif
                <div class="dropdown d-inline-block language-switch ms-2">
                    <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <img class="img" src="{{ URL::asset('build/images/flags/malay.jpg') }}"
                            alt="Header Language" height="18">
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <img src="{{ URL::asset('build/images/flags/malay.jpg') }}" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">Malaysia</span>
                    </div>
                </div>
                <br>
                <br>

                @if (auth()->check())
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item user text-start d-flex align-items-center"
                            id="page-header-user-dropdown-v" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="rounded-circle header-profile-user"
                                src="{{ URL::asset('build/images/users/avatar-3.jpg') }}" alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                <p class="mb-0 font-size-11 text-muted">{{ auth()->user()->email }}</p>
                            </div>
                            <a class="dropdown-item" href="auth-lock-screen"><i
                                    class="mdi mdi-lock text-muted font-size-16 align-middle me-2"></i> <span
                                    class="align-middle">Lock screen</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void();"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span
                                    class="align-middle">Logout</span></a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="button-49">Login/Register</a>
                @endif
            </div>
        </div>
    </header>

    <!-- ... (other body elements) ... -->
</body>

</html>