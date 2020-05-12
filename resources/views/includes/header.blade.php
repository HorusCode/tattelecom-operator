<header class="header">
    <div class="logo">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo--image">
    </div>
    <span class="page-title">
         @yield('title')
    </span>
    <div class="spacer"></div>
    <div class="d-flex justify-content-between align-items-center">
        <div class="user-name">
            <span class="user-employee text-muted">
                {{ __("base.".Auth::user()->employee->name)}}
            </span>
        </div>
        <span class="avatar">
            <span class="mdi mdi-account-circle-outline"></span>
        </span>

    </div>
</header>
