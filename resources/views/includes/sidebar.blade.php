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
            <li class="vertical-list__heading">Меню</li>
            <li class="vertical-list__item">
                <a href="#" aria-expanded="true" class="vertical-list__trigger">
                    <span class="mdi mdi-clipboard-text-outline pos-left-null"></span>Заявления<span
                            class="mdi mdi-chevron-up pos-right-null"></span>
                </a>
                <ul class="submenu">
                    @php
                        $route = Route::currentRouteName();
                    @endphp
                    <li class="submenu__item">
                        <a class="{{ $route === 'home' ? 'active' : ''}}"
                           href="{{ route('home') }}">Новые</a>
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
        </ul>
    </div>
    <footer class="sidebar__footer d-flex justify-content-center align-items-center">
        <b-button type="is-primary" tag="a" expanded
                  icon-right="logout" id="logout" href="{{ route('logout')}}"></b-button>
    </footer>
</aside>
