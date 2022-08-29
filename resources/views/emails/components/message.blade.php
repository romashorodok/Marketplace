@component('emails.components.layout')
    @isset($styles)
        @slot('styles')
            {{ $styles }}
        @endslot
    @endisset

    @isset($header)
        @slot('header')
            {{ $header }}
        @endslot
    @endisset

    {{ $slot }}

    @slot('footer')
        <a href="{{ url('') }}">
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        </a>
    @endslot
@endcomponent
