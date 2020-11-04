@extends(backpack_view('blank'))
@section('head')
    <link rel="stylesheet" type="text/css" href="../../../../css/app.css">
@endsection

@section('content')
<div class="container-fluid animated fadeIn trackings">
    <form method="GET" action="">
        <div class="row">
            <div class="col-12">
                <label>Tracking data (Format: lat,long,timestamp):</lable></br>
                <textarea name="input" rows="10" cols="50">{{ $input }}</textarea>
            </div>
            <div class="col-12">
                <button type="submit">Submit</button>
            </div>
            <div class="col-12">
                &nbsp
            </div>
            <div class="col-12">
                &nbsp
            </div>
        </div>
    </form>
    @foreach ($results as $item)
        <div class="row result">
            <div class="col-4 in">
                Position: {{ $item['item']}} </br>
                Time: {{ $item['time'] }}
            </div>
            <div class="col-1 line-parent">
                <div class="line"></div>
                <div class="point"></div>
            </div>
            <div class="col-6"></div>
        </div>
        @if (isset($item['distance']))
            <div class="row result">
                <div class="col-4"></div>
                <div class="col-1 line-parent">
                    <div class="line"></div>
                </div>
                <div class="col-6 out">
                    Distance: {{ $item['distance'] }} (m) </br>
                    Speed: {{ $item['speed'] }} (km/h)
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection
