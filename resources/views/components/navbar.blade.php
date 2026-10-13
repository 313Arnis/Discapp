
<nav class="discapp-nav">
    <a href="{{ route('home') }}" class="discapp-logo">
        Discapp
    </a>

    <div class="discapp-nav-links">
        <a href="{{ route('home') }}"
           class="{{ request()->routeIs('home') ? 'active' : '' }}">
            Sākums
        </a>

        <a href="{{ route('competitions.index') }}"
           class="{{ request()->routeIs('competitions.*') ? 'active' : '' }}">
            Sacensības
        </a>

        <a href="{{ route('players.index') }}"
           class="{{ request()->routeIs('players.*') ? 'active' : '' }}">
            Spēlētāji
        </a>

        @auth
            <a href="{{ route('profile') }}"
               class="{{ request()->routeIs('profile') || request()->routeIs('profile.*') ? 'active' : '' }}">
                Mans profils
            </a>
        @endauth
    </div>

    <div class="discapp-nav-auth">
        @auth
            <span class="discapp-greeting">
                Sveiks, {{ auth()->user()->name }}
                {{ auth()->user()->surname }}!
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="discapp-logout">
                    Iziet
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="discapp-login">
                Pieslēgties
            </a>

            <a href="{{ route('register') }}" class="discapp-register">
                Reģistrēties
            </a>
        @endauth
    </div>
</nav>
