@extends('voyager::master')

@section('page_header')
    <h1 class="page-title">
        <i class="fa fa-map" aria-hidden="true"></i>
        Estadísticas de mapas
    </h1>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <h2>Estadísticas Diarias</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mapa</th>
                            <th>Partidas</th>
                            <th>Jugadores</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dailyMapStats as $stat)
                            <tr>
                                <td>{{ $stat->map }}</td>
                                <td>{{ $stat->match_count }}</td>
                                <td>{{ $stat->player_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2>Estadísticas Semanales ({{ $startOfWeek->format('Y-m-d H:i:s') }} - {{ $endOfWeek->format('Y-m-d H:i:s') }})</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mapa</th>
                            <th>Partidas</th>
                            <th>Jugadores</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($weeklyMapStats as $stat)
                            <tr>
                                <td>{{ $stat->map }}</td>
                                <td>{{ $stat->match_count }}</td>
                                <td>{{ $stat->player_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2>Estadísticas Históricas</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mapa</th>
                            <th>Partidas</th>
                            <th>Jugadores</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historicalMapStats as $stat)
                            <tr>
                                <td>{{ $stat->map }}</td>
                                <td>{{ $stat->match_count }}</td>
                                <td>{{ $stat->player_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
@endsection
