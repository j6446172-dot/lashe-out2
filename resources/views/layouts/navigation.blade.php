@auth
    @if(auth()->user()->role === 'owner')
        @include('layouts.navbar-owner')

    @elseif(auth()->user()->role === 'staff')
        @include('layouts.navbar-staff')

    @elseif(auth()->user()->role === 'customer')
        @include('layouts.navbar-customer')
    @endif
@endauth