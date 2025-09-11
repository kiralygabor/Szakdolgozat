@extends('layout')

@section('content')
    <h1>Counties</h1>

    <a href="{{ route('counties.create') }}">+ Add New County</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @forelse($counties as $county)
                <tr>
                    <td>{{ $county->id }}</td>
                    <td>{{ $county->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No counties found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
