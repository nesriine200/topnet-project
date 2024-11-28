@extends('home.index')

@section('dashboard-content')
    <div class="container">
        <div class="row justify-content-center">
            <!-- Statistiques des opportunités validées -->
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Opportunités Validées</h5>
                            <small class="text-muted">Statistiques par apporteur</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <!-- Total des opportunités validées -->
                                <h2 class="mb-2">{{ array_sum($validatedOpportunities) }}</h2>
                                <span>Total Opportunités Validées</span>
                            </div>
                            <!-- Graphique en camembert -->
                            <div id="opportunityPieChartContainer" style="width:300px; height:300px">
                                <canvas id="opportunityPieChart"></canvas>
                            </div>
                        </div>
                        <!-- Liste des opportunités validées par apporteur -->
                        <ul class="p-0 m-0">
                            @foreach ($names as $index => $name)
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="bx bx-user"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $name }}</h6>
                                            <small class="text-muted">Opportunités Validées</small>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-semibold">{{ $validatedOpportunities[$index] }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @hasanyrole('apporteur')
                <!-- Statistiques des commissions -->
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body px-0">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                    <div class="d-flex p-4 pt-3">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <img src="../assets/img/icons/unicons/wallet.png" alt="Commissions" />
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Total Commissions</small>
                                            <div class="d-flex align-items-center">
                                                <h6 class="mb-0 me-1">{{ $totalCommission }}</h6>
                                                <small class="text-success fw-semibold">
                                                    <i class="bx bx-chevron-up"></i>
                                                    {{ round($percentageChange, 2) }}%
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <canvas id="commissionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endhasanyrole
        </div>
    </div>


    <!-- Chart.js script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Graphique des opportunités validées
        var ctxPie = document.getElementById('opportunityPieChart').getContext('2d');
        var opportunityPieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: {!! json_encode($names) !!}, // Les noms des apporteurs
                datasets: [{
                    data: {!! json_encode($validatedOpportunities) !!}, // Opportunités validées par apporteur
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                    hoverBackgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });

        // Graphique des commissions
        var ctxCommission = document.getElementById('commissionChart').getContext('2d');
        var commissionChart = new Chart(ctxCommission, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!}, // Mois
                datasets: [{
                    label: 'Commissions (€)',
                    data: {!! json_encode($monthlyCommissions) !!}, // Commissions par mois
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Commission (€)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mois'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
@endsection
