<div class="card shadow card-primary" data-collapsed="0">
    <div class="card-header py-3">
        <div class="card-title">
            Active Jobs
        </div>
        <div class="card-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
        </div>
    </div>
    <div class="card-body">
        <div class="text-right">
            <div class="refresh-collection"><a id="refreshjob" class="btn btn-primary save-collection"><undefined>Refresh</undefined></a></div>
            <!--<a  id="add-subscription" class=" btn btn-primary btn-sm btn-icon icon-left"><i class="entypo-plus"></i>Add Subscription</a>-->
            <div class="clear clearfix"><br></div>
        </div>
        @include('includes.errors')
        @include('includes.success')

        <table class="table table-bordered datatable" id="jobs">
            <thead>
            <tr>
                <th width="20%">Job Title</th>
                <th width="20%">PID</th>
                <th width="20%">Since Process Running</th>
                <th width="20%">Last Run Time</th>
                <th width="20%">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <script type="text/javascript">
            var $searchFilter = {};
            var update_new_url;
            var postdata;
            jQuery(document).ready(function ($) {
                public_vars.$body = $("body");

                //show_loading_bar(40);
                data_table_jobs = $("#jobs").dataTable({
                    "bDestroy": true,
                    "bProcessing":true,
                    "bServerSide":true,
                    "sAjaxSource": baseurl + "/jobs/jobactive_ajax_datagrid",
                    "iDisplayLength": parseInt('{{CompanyConfiguration::get('PAGE_SIZE')}}'),
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row'<'col-xs-6 col-left'l><'col-xs-6 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
                    "fnServerParams": function(aoData) {
                        data_table_extra_params.length = 0;
                        data_table_extra_params.push({"name":"Export","value":1});
                    },
                    "aaSorting": [[0, 'asc']],
                    "aoColumns":
                            [
                                {  "bSortable": true },//0 title
                                {  "bSortable": true },  //1   Pid
                                {  "bSortable": true },  //2   Running Hour
                                {  "bSortable": true },  //3   Last Run Time
                                {                       //4
                                    "bSortable": false,
                                    mRender: function ( JobID, type, full ) {

                                        var action ='';

                                        action = '<div class = "hiddenRowData" >';
                                        action += '<input type = "hidden"  name = "JobTitle" value = "' + (full[0] !== null ?full[0]:'') + '" / >';
                                        action += '<input type = "hidden"  name = "PID" value = "' + (full[1] !== null ?full[1]:'') + '" / >';
                                        action += '<input type = "hidden"  name = "RunningHour" value = "' + (full[2] !== null ?full[2]:'')+ '" / >';
                                        action += '<input type = "hidden"  name = "LastRunTime" value = "' + (full[2] !== null ?full[2]:'')+ '" / >';
                                        action += '<input type = "hidden"  name = "JobID" value = "' + JobID + '" / >';
                                        action += ' <a data-id="'+ JobID +'" data-pid="'+(full[1] !== null ?full[1]:'')+'" class="delete-config btn delete btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Terminate</a>';

                                        return action;
                                    }
                                },

                            ],
                    "oTableTools": {
                        "aButtons": [
                            {
                                "sExtends": "download",
                                "sButtonText": "Refresh",
                                sButtonClass: "save-collection"
                            }
                        ]
                    },
                    "fnDrawCallback": function() {
                        $(".dataTables_wrapper select").select2({
                            minimumResultsForSearch: -1
                        });
                    }

                });



                // Replace Checboxes
                $(".pagination a").click(function (ev) {
                    replaceCheckboxes();
                });

                $("#refreshjob").click(function(){
                    data_table_jobs.fnFilter('', 0);
                });
            });
        </script>
        <style>
            .dataTables_filter label{
                display:none !important;
            }
            .dataTables_wrapper .export-data{
                right: 30px !important;
                display: none;
            }
            .refresh-collection{
                float: right;
                right: 30px !important;
                padding-bottom: 5px;;
            }
        </style>
    </div>
</div>

