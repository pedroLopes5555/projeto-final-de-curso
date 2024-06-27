@extends('_layouts.admin.normal')

@section('buttons')
    <!-- Any additional buttons or content specific to this section -->
@endsection

@section('body')
    <div style="width: 80%; margin: auto; padding: 20px;">
        <h2 style="text-align: center; margin-bottom: 20px; font-family: Arial, sans-serif; color: #333;">Dashboard</h2>
        
        <!-- Active and Inactive Arduinos Charts -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <div style="flex: 1 1 48%;">
                <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <h3 style="text-align: center; font-family: Arial, sans-serif; color: #555;">Active Arduinos</h3>
                    <canvas id="arduinoActiveChart"></canvas>
                </div>
            </div>
            <div style="flex: 1 1 48%;">
                <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <h3 style="text-align: center; font-family: Arial, sans-serif; color: #555;">Inactive Arduinos</h3>
                    <canvas id="arduinoInactiveChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Container Data Chart -->
        <div style="margin-bottom: 20px;">
            <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h3 style="text-align: center; font-family: Arial, sans-serif; color: #555;">Container Data</h3>
                <canvas id="containerChart"></canvas>
            </div>
        </div>
        
        <!-- Reading Types Distribution Chart -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
            <div style="flex: 1 1 48%; margin-bottom: 20px;">
                <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <h3 style="text-align: center; font-family: Arial, sans-serif; color: #555;">Reading Types Distribution</h3>
                    <canvas id="readingTypeChart"></canvas>
                </div>
            </div>
            <div style="flex: 1 1 100%; margin-bottom: 20px;">
                <div style="background: #f9f9f9; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <h3 style="text-align: center; font-family: Arial, sans-serif; color: #555;">Historical Readings</h3>
                    <canvas id="historicalReadingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Active Arduinos Chart
        const arduinoActiveCtx = document.getElementById('arduinoActiveChart').getContext('2d');
        const arduinoActiveChart = new Chart(arduinoActiveCtx, {
            type: 'bar',
            data: {
                labels: ['Active Arduinos'],
                datasets: [{
                    label: 'Active',
                    data: [{{ $arduinosActive }}],
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Inactive Arduinos Chart
        const arduinoInactiveCtx = document.getElementById('arduinoInactiveChart').getContext('2d');
        const arduinoInactiveChart = new Chart(arduinoInactiveCtx, {
            type: 'bar',
            data: {
                labels: ['Inactive Arduinos'],
                datasets: [{
                    label: 'Inactive',
                    data: [{{ $arduinosInactive }}],
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Container Chart
        const containerCtx = document.getElementById('containerChart').getContext('2d');
        const containerChart = new Chart(containerCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($containers)) !!}, // Assuming $containers is indexed by container ID
                datasets: [{
                    label: 'Number of Readings',
                    data: {!! json_encode(array_map('count', $containers)) !!}, // Number of readings per container
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Reading Type Chart
        const readingTypeCtx = document.getElementById('readingTypeChart').getContext('2d');
        const readingTypeCounts = [0, 0, 0]; // Initialize counts for each reading type
        @foreach($containers as $container)
            @foreach($container as $type => $readings)
                readingTypeCounts[{{ array_search($type, array_values($typeDict)) }}] += {{ count($readings) }};
            @endforeach
        @endforeach
        const readingTypeChart = new Chart(readingTypeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_values($typeDict)) !!}, // Labels for reading types from $typeDict
                datasets: [{
                    label: '# of Readings',
                    data: readingTypeCounts,
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        // Additional charts omitted for brevity

    });
</script>
@endsection
