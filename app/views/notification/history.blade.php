@extends('layout.main')
@section('content')
<ol class="breadcrumb bc-3">
    <li>
        <a href="{{action('dashboard')}}"><i class="entypo-home"></i>Home</a>
    </li>
    <li>
        <a href="{{URL::to('notification')}}">Notification</a>
    </li>
</ol>
<h3>Notification History</h3>

<div class="row">
    <div class="col-md-12">
        <form id="history_filter" method=""  action="" class="form-horizontal form-groups-bordered validate" novalidate>
            <div class="card shadow card-primary" data-collapsed="0">
                <div class="card-header py-3">
                    <div class="card-title">
                        Filter
                    </div>
                    <div class="card-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-1 control-label">Search</label>
                        <div class="col-sm-2">
                            <input class="form-control" name="Search"  type="text" >
                        </div>
                        <label class="col-sm-1 control-label">Name</label>
                        <div class="col-sm-2">
                            {{ Form::select('AlertID', $Alerts, (!empty(Input::get('AlertID'))?Input::get('AlertID'):''), array("class"=>"form-control select2 small")) }}
                        </div>
                        <label class="col-sm-1 control-label">Type</label>
                        <div class="col-sm-2">
                            {{ Form::select('AlertType', $alertType, '', array("class"=>"form-control select2 small")) }}
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-1 control-label">Start Date</label>
                        <div class="col-sm-2">
                            {{ Form::text('StartDate', !empty(Input::get('StartDate'))?Input::get('StartDate'):$data['StartDateDefault'], array("class"=>"form-control datepicker","data-date-format"=>"yyyy-mm-dd" ,"data-enddate"=>date('Y-m-d'))) }}<!-- Time formate Updated by Abubakar -->
                        </div>
                        <div class="col-sm-2  small-date-input">
                            <input type="text" name="StartTime" data-minute-step="5" data-show-meridian="false" data-default-time="00:00:00" data-show-seconds="true" data-template="dropdown" placeholder="00:00:00" class="form-control timepicker">
                        </div>
                        <label for="field-1" class="col-sm-1 control-label">End Date</label>
                        <div class="col-sm-2">
                            {{ Form::text('EndDate', !empty(Input::get('EndDate'))?Input::get('ToDate'):$data['EndDateDefault'], array("class"=>"form-control datepicker","data-date-format"=>"yyyy-mm-dd" ,"data-enddate"=>date('Y-m-d'))) }}
                        </div>
                        <div class="col-sm-2  small-date-input">
                            <input type="text" name="EndTime" data-minute-step="5" data-show-meridian="false" data-default-time="23:59:59" value="23:59:59" data-show-seconds="true" placeholder="00:00:00" data-template="dropdown" class="form-control timepicker">
                        </div>
                    </div>
                    <p style="text-align: right;">
                        <button type="submit" class="btn btn-primary btn-sm btn-icon icon-left">
                            <i class="entypo-search"></i>
                            Search
                        </button>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="clear"></div>

<table class="table table-bordered datatable" id="table-4">
    <thead>
        <tr>
            <th width="30%">Name</th>
            <th width="30%">Type</th>
            <th width="30%">Created Date</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<script src="{{ URL::asset('assets/js/dashboard.js') }}"></script>
<script type="text/javascript">
    var $searchFilter = {};
    var list_fields_index  = ["Name","AlertType","send_at","Subject","Message"];
    var AlertType = JSON.parse('{{json_encode($alertType)}}');
    jQuery(document).ready(function($) {

        $searchFilter.Search = $("#history_filter [name='Search']").val();
        $searchFilter.StartDate = $("#history_filter [name='StartDate']").val();
        $searchFilter.StartTime = $("#history_filter [name='StartTime']").val();
        $searchFilter.EndDate = $("#history_filter [name='EndDate']").val();
        $searchFilter.EndTime = $("#history_filter [name='EndTime']").val();
        $searchFilter.AlertID = $("#history_filter [name='AlertID']").val();
        $searchFilter.AlertType = $("#history_filter [name='AlertType']").val();
        data_table = $("#table-4").dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": baseurl + "/alert/history_grid/type",
            "iDisplayLength": parseInt('{{CompanyConfiguration::get('PAGE_SIZE')}}'),
            "sPaginationType": "bootstrap",
            "sDom": "<'row'<'col-xs-6 col-left'l><'col-xs-6 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
            "fnServerParams": function(aoData) {
                aoData.push(
                        {"name":"Search","value":$searchFilter.Search},
                        {"name":"StartDate","value":$searchFilter.StartDate},
                        {"name":"StartTime","value":$searchFilter.StartTime},
                        {"name":"EndDate","value":$searchFilter.EndDate},
                        {"name":"EndTime","value":$searchFilter.EndTime},
                        {"name":"AlertID","value":$searchFilter.AlertID},
                        {"name":"AlertType","value":$searchFilter.AlertType}
                );
                data_table_extra_params.length = 0;
                data_table_extra_params.push(
                        {"name":"Search","value":$searchFilter.Search},
                        {"name":"StartDate","value":$searchFilter.StartDate},
                        {"name":"StartTime","value":$searchFilter.StartTime},
                        {"name":"EndDate","value":$searchFilter.EndDate},
                        {"name":"EndTime","value":$searchFilter.EndTime},
                        {"name":"AlertID","value":$searchFilter.AlertID},
                        {"name":"AlertType","value":$searchFilter.AlertType},
                        {"name":"Export","value":1}
                );
            },
            "aaSorting": [[2, 'desc']],
            "aoColumns":
                    [
                        {"bSortable": true},  // 1 Title
                        {"bSortable": true, mRender:function(id,type,full){
                            return AlertType[id];
                        }},  // 1 Title
                        {"bSortable": true},  // 1 Title
                        {                        // 9 Action
                            "bSortable": false,
                            mRender: function (id, type, full) {
                                action = '<div class = "hiddenRowData" >';
                                for (var i = 0; i < list_fields_index.length; i++) {
                                    if(list_fields_index[i] == 'AlertType'){
                                        action += '<div class="hidden" name="' + list_fields_index[i] + '">' + AlertType[full[i]] + '</div>';
                                    }else{
                                        action += '<div class="hidden" name="' + list_fields_index[i] + '">' + full[i] + '</div>';
                                    }

                                }
                                action += '</div>';

                                action += ' <a class="view-alert btn btn-primary btn-sm tooltip-primary" data-original-title="View" title="" data-placement="top" data-toggle="tooltip"><i class="fa fa-eye"></i></a>'

                                        return action;
                            }
                        }
                    ],
                    "oTableTools":
                    {
                        "aButtons": [
                            {
                                "sExtends": "download",
                                "sButtonText": "EXCEL",
                                "sUrl": baseurl + "/alert/history_grid/xlsx",
                                sButtonClass: "save-collection btn-sm"
                            },
                            {
                                "sExtends": "download",
                                "sButtonText": "CSV",
                                "sUrl": baseurl + "/alert/history_grid/csv",
                                sButtonClass: "save-collection btn-sm"
                            }
                        ]
                    }
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });

        // Replace Checboxes
        $(".pagination a").click(function(ev) {
            replaceCheckboxes();
        });


        $("#history_filter").submit(function(e) {
            e.preventDefault();
            $searchFilter.Search = $("#history_filter [name='Search']").val();
            $searchFilter.StartDate = $("#history_filter [name='StartDate']").val();
            $searchFilter.StartTime = $("#history_filter [name='StartTime']").val();
            $searchFilter.EndDate = $("#history_filter [name='EndDate']").val();
            $searchFilter.EndTime = $("#history_filter [name='EndTime']").val();
            $searchFilter.Status = $("#history_filter [name='Status']").val();
            $searchFilter.AlertID = $("#history_filter [name='AlertID']").val();
            $searchFilter.AlertType = $("#history_filter [name='AlertType']").val();

            data_table.fnFilter('', 0);
            return false;
        });

    });

</script>
@include('notification.alert-log')
    @stop
