@extends('_layouts.admin.normal')

@section('buttons')
@endsection

@section('body')
<div class="container">
    <h2>Dashboard</h2>
    @foreach($containers as $container)
        <div class="container">
            <h3>{{ $container->container_name }}</h3>
            <form action="{{ route('dashboard.store') }}" method="POST">
                @csrf
                <input type="hidden" name="container_id" value="{{ $container->container_id }}">
                <div class="form-group">
                    <label for="value_ph">pH Value</label>
                    <input type="text" class="form-control" id="value_ph" name="value_ph" value="{{ $container->targetValue->value_ph ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="value_temp">Temperature Value</label>
                    <input type="text" class="form-control" id="value_temp" name="value_temp" value="{{ $container->targetValue->value_temp ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="value_electric_condutivity">Electric Conductivity Value</label>
                    <input type="text" class="form-control" id="value_electric_condutivity" name="value_electric_condutivity" value="{{ $container->targetValue->value_electric_condutivity ?? '' }}" required>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <br>
    @endforeach
</div>
@endsection

@section('scripts')



@endsection