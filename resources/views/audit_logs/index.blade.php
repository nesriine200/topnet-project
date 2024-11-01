@extends('home.index')
@section('content')

<table class="table table-striped">
    <thead>
    <tr>

        <th>Utilisateur</th>
        <th>Action</th>
        <th>Table</th>
        <th>Anciennes valeurs</th>
        <th>Nouvelles valeurs</th>
        <th>IP</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
        <tr>

            <td>{{ $log->user->name ?? 'N/A' }}</td>
            <td>{{ $log->action }}</td>
            <td>{{ $log->table_name }}</td>
            <td><pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre></td>
            <td><pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre></td>
            <td>{{ $log->ip_address }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $logs->links() }}
@endsection
