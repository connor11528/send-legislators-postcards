@extends('layouts.main')

@section('content')
    <div class="row mt-5">
        <div class="col-sm-12">
            <h2>Officials to send letters to</h2>

            <ul class="list-group">
            @foreach($electedOfficials as $official)
                <li class="list-group-item">
                    <h4>{{ $official['name'] }} - {{ $official['office_name'] }}</h4>
                    <p>{{ data_get($official, 'address.0.line1') }}, {{ data_get($official, 'address.0.city') }}, {{ data_get($official, 'address.0.state') }} {{ data_get($official, 'address.0.zip') }}</p>
                </li>
            @endforeach
            </ul>
        </div>
    </div>
@endsection
