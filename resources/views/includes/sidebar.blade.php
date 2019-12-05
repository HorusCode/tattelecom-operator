<aside class="sidebar">
    <header class="sidebar__header d-flex justify-content-between align-items-center">
        <div class="logo">
            <img src="{{ asset('image/logo.png') }}" alt="Logo" width="50" height="50" class="logo--image">
        </div>
        <div class="burger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>
    <div class="sidebar__content">
        <ul class="sidebar__nav-menu vertical-list">
            <li class="vertical-list__heading">Меню</li>
            <li class="vertical-list__item --active">
                <a href="#" aria-expanded="true">
                    <span class="mdi mdi-clipboard-text-outline pos-left-null"></span>
                    Заявления
                    <span class="mdi mdi-chevron-up pos-right-null"></span>
                </a>
                <ul class="submenu">
                    <li class="submenu__item"><a>Новые</a></li>
                    <li class="submenu__item"><a>Активные</a></li>
                    <li class="submenu__item"><a>Завершённые</a></li>
                </ul>
            </li>
            <li class="vertical-list__item --active">
                <a href="#" aria-expanded="true">
                    <span class="mdi mdi-clipboard-text-outline  pos-left-null"></span>
                    Заявления
                    <span class="mdi mdi-chevron-up  pos-right-null"></span>
                </a>
                <ul class="submenu">
                    <li class="submenu__item"><a href="{{ route('home') }}">Новые</a></li>
                    <li class="submenu__item"><a href="{{ route('active') }}">Активные</a></li>
                    <li class="submenu__item"><a href="{{ route('ended') }}">Завершённые</a></li>
                </ul>
            </li>
            <li class="vertical-list__heading">UI Components</li>
            <li class="vertical-list__item">
                <a href="#">
                    <span class="mdi mdi-clipboard-text-outline pos-left-null"></span>
                    Заявление
                    <span class="badge  pos-right-null">122</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
