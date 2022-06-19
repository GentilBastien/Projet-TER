<div tabindex="-1" class="bloc logout">
    <span>Logged as: <b>{{ $user->id }}</b> ({{ $type }})</span>
    <a href="{{ route('logout') }}" tabindex="-1">
        <button type="button" class="redButton" tabindex="-1">
            <img src="img/logout.png" />
            <span>Disconnect</span>
        </button>
    </a>
</div>


{{-- Logout is main blade class for assessment and dashboard views --}}
{{-- assessment view                 dashboard view              --}}
@yield('model') @yield('campaign')
@yield('topic') @yield('expertTable') @yield('adminTable')
@yield('instructions') @yield('expert') @yield('admin')
@yield('abstract')
@yield('save')
@yield('assessment')