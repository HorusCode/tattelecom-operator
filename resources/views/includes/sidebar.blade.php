<aside class="sidebar">
    <header class="sidebar__header">
        <div class="burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>
    <div class="sidebar__content">
        <ul class="sidebar__nav-menu vertical-list">
            @php
                $route = Route::currentRouteName();
                $role = Auth()->user()->employee->name;
            @endphp
            <li class="vertical-list__heading">Меню</li>
            <li class="vertical-list__item">
                <a href="#" aria-expanded="true" class="vertical-list__trigger">
                    <span class="mdi mdi-clipboard-text-outline pos-left-null"></span>Все заявления<span
                            class="mdi mdi-chevron-up pos-right-null"></span>
                </a>
                <ul class="submenu">

                    <li class="submenu__item">
                        <a class="{{ $route === 'inactive' ? 'active' : ''}}"
                           href="{{ route('inactive') }}">Новые</a>
                    </li>
                    <li class="submenu__item">
                        <a class="{{ $route === 'active' ? 'active' : '' }}"
                           href="{{ route('active') }}">Активные</a>
                    </li>
                    <li class="submenu__item">
                        <a class="{{ $route === 'ended' ? 'active' : ''}}"
                           href="{{ route('ended') }}">Завершённые</a>
                    </li>
                </ul>
            </li>
            @if($role === 'client_operator')
                <li class="vertical-list__item">
                    <a href="{{ route('statement') }}" aria-expanded="true" class="{{ $route === 'statement' ? 'active' : ''}}">
                        <span class="mdi mdi-account-card-details-outline pos-left-null"></span>Оформить заявление
                    </a>
                </li>
                <li class="vertical-list__item">
                    <a href="{{ route('problem') }}" aria-expanded="true" class="{{ $route === 'problem' ? 'active' : ''}}">
                        <span class="mdi mdi-alert-octagon-outline pos-left-null"></span>Все неисправности
                    </a>
                </li>
                <li class="vertical-list__item">
                    <a href="{{ route('abonents') }}" aria-expanded="true" class="{{ $route === 'abonents' ? 'active' : ''}}">
                        <span class="mdi mdi-account-badge-horizontal-outline pos-left-null"></span>Абоненты
                    </a>
                </li>
                <li class="vertical-list__item">
                    <a href="{{ route('statistics') }}" aria-expanded="true" class="{{ $route === 'statistics' ? 'active' : ''}}">
                        <span class="mdi mdi-chart-line pos-left-null"></span>Статистика
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <footer class="sidebar__footer d-flex justify-content-center align-items-center">
        <b-button type="is-primary" tag="a" expanded
                  icon-right="logout" id="logout" href="{{ route('logout')}}"></b-button>
    </footer>
</aside>
