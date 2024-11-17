@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Conteneur pour le tableau des résultats -->
                <div id="opportunity-list">
                    <div class="card p-2">
                        <table class="table table-bordered">
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
                                @foreach ($logs as $log)
                                    <tr>

                                        <td>{{ $log->user->name ?? 'N/A' }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->table_name }}</td>
                                        <td>
                                            <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                        </td>
                                        <td>
                                            <pre>{{ Str::limit(json_encode($log->new_values, JSON_PRETTY_PRINT), 20) }}...</pre>
                                        </td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>{{ $log->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
