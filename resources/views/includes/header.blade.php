
<header class="app-container__header">
    <span class="page-title">
         @yield('title')
    </span>
    <div class="d-flex justify-content-between align-items-center">
        <div class="user-name">
            {{ Auth::user()->getFullName()  }}
            <span class="user-employee text-muted">
                {{ __("base.".Auth::user()->employee->name)}}
            </span>
        </div>
        <span class="avatar">
            <span class="mdi mdi-account-circle-outline"></span>
        </span>

    </div>

</header>
