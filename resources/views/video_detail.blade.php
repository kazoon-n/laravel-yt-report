@extends('layouts.app')

@section('javascript')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    window.onload = function() {
        $('#desc-button').click(function() {
            $('#desc-target').slideToggle()
        });
        // flatpickr.localize(flatpickr.l10ns.ja);
        flatpickr('#calendar', {
            mode: 'range'
        });
    };

    google.charts.load('current', {
        'packages': ['corechart']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var entries = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
        var data = google.visualization.arrayToDataTable(entries);

        var options = {
            curveType: 'function',
            legend: {
                position: 'bottom'
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
    }
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 m-3">
            <h4>Thumbnail</h4>
            <img src="{{ asset($video[0]['thumbnail']) }}" class="img-thumbnail img-fluid lg-thumbnail" alt="image">
        </div>
        <div class="col-7 m-3">
            <h4>Video Detail</h4>
            <p>{{ $video[0]['name'] }}</p>
            <h4>Description</h4>
            <p><a id="desc-button" class="desc-button"><b>Open Description</b></a></p>
            <p id="desc-target" class="desc-content" style="display: none;">{{ $video[0]['description'] }}</p>
        </div>
    </div>
    <div class="row">
        <div class="m-4">
            <h4>Daily Chart</h4>
            <form action="{{ route('video_detail', $id) }}" method="GET">
                <div class="d-flex flex-row bd-highlight mb-3">
                    <input type="text" id="calendar" style="width: 30%;" placeholder="Filter Date" class="form-control mr-3" name="date_controll">
                    <select class="form-select mr-3" aria-label="Default select example" name="metrics">
                        <option selected value="views">Views</option>
                        <option value="comments">Comments</option>
                        <option value="likes">Likes</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
            <div id="curve_chart" style="width: 900px; height: 500px"></div>
        </div>
    </div>
</div>
@endsection