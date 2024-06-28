@extends('_layouts.admin.normal')

@section('buttons')
    <!-- Any additional buttons or content specific to this section -->
@endsection

@section('body')
    <div class="dashboard-container">
        
        <!-- Active and Inactive Microcontroladores -->
        <div class="microcontrolador-status">
            <div class="microcontrolador-card">
                <h3>Active Microcontroladores</h3>
                <span class="microcontrolador-count">{{ $arduinosActive }}</span>
            </div>
            <div class="microcontrolador-card">
                <h3>Inactive Microcontroladores</h3>
                <span class="microcontrolador-count">{{ $arduinosInactive }}</span>
            </div>
        </div>

        <!-- Electric Conductivity and pH over Time Charts -->
        @foreach($containers as $containerId => $containerData)
            @php
                $containerName = $containerData['name'];
                $latestEcReading = end($containerData['ec'])['reading'] ?? 'N/A';
                $latestPhReading = end($containerData['ph'])['reading'] ?? 'N/A';
            @endphp
            <div class="chart-card">
                <h3>{{ $containerName }} - Electric Conductivity (Latest: {{ $latestEcReading }} µS/cm)</h3>
                <canvas id="ecChart{{ $containerId }}"></canvas>
            </div>
            <div class="chart-card">
                <h3>{{ $containerName }} - pH (Latest: {{ $latestPhReading }} pH)</h3>
                <canvas id="phChart{{ $containerId }}"></canvas>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Iterate through each container and render the charts
        @foreach($containers as $containerId => $containerData)
            const ecData = {
                labels: {!! json_encode(array_column($containerData['ec'], 'time')) !!},
                datasets: [{
                    label: 'Electric Conductivity',
                    data: {!! json_encode(array_column($containerData['ec'], 'reading')) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 1,
                    fill: true,
                }]
            };

            const phData = {
                labels: {!! json_encode(array_column($containerData['ph'], 'time')) !!},
                datasets: [{
                    label: 'pH',
                    data: {!! json_encode(array_column($containerData['ph'], 'reading')) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1,
                    fill: true,
                }]
            };

            new Chart(document.getElementById('ecChart{{ $containerId }}').getContext('2d'), {
                type: 'line',
                data: ecData,
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            new Chart(document.getElementById('phChart{{ $containerId }}').getContext('2d'), {
                type: 'line',
                data: phData,
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        @endforeach
    });
</script>
@endsection

<style>
    :root {
        --primary-color: #333;
        --secondary-color: #555;
        --background-color: #f9f9f9;
        --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        --text-color: #333;
    }

    .dashboard-container {
        width: 80%;
        margin: auto;
        padding: 20px;
    }

    .dashboard-title {
        text-align: center;
        margin-bottom: 20px;
        font-family: var(--font-family);
        color: var(--text-color);
    }

    .microcontrolador-status {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .microcontrolador-card {
        flex: 1 1 48%;
        text-align: center;
        background: var(--background-color);
        border-radius: 8px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        margin: 0 10px;
    }

    .microcontrolador-card h3 {
        font-family: var(--font-family);
        color: var(--secondary-color);
        margin-bottom: 10px;
    }

    .microcontrolador-count {
        font-size: 24px;
    }

    .chart-card {
        background: var(--background-color);
        border-radius: 8px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        margin-bottom: 20px;
    }

    .chart-card h3 {
        text-align: center;
        font-family: var(--font-family);
        color: var(--secondary-color);
        margin-bottom: 20px;
    }
    #layout-body > .card {
        max-width: 100% !important;
    }
</style>
