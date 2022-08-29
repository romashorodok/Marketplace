@component('emails.components.message')
    @slot('styles')
        <style>
            p {
                margin-bottom: 20px;
            }
        </style>
    @endslot

    @slot('header')
        Thank you for registration!
    @endslot

    <p>
        Hi <b> {{ $firstName }} {{ $lastName }}</b>!
    </p>
    <p>
        You've successfully completed registration!
    </p>
    <p>
            You can login at <a href="{{ url('') }}">site</a>.
    </p>
@endcomponent
