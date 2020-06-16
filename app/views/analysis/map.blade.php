<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/js/jvectormap/jquery-jvectormap-2.0.3.css')}}" />
<div class="row">
    <div class="col-sm-12">
        <div class="card shadow loading card-default" data-collapsed="0">
            <!-- to apply shadow add class "card-shadow" --> <!-- card shadow head -->
                 <div class="card-header py-3"><h3>@lang('routes.CUST_PANEL_PAGE_MONITOR_HEADING_TRAFFIC_BY_REGION')</h3></div>
                <div class="card-options">

                </div>
             <!-- card shadow body -->
            <div class="card-body no-padding">
                <div id="worldmap" style="height:450px;width:100%;" class="world-map-chart"></div>
            </div>
        </div>
    </div>
</div>
<br />
@include('analysis.map_grid')
<script src="{{ URL::asset('assets/js/jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
<script src="{{ URL::asset('assets/js/map.js') }}"></script>