@extends('layout.customer.main')
@section('content')
    <style>
        .small_fld{width:80.6667%;}
    </style>
    <br />
    {{--<link rel="stylesheet" type="text/css" href="assets/js/daterangepicker/daterangepicker-bs3.css" />--}}

        <ul class="nav nav-tabs">
            @if($is_customer == 1)
                <li ><a href="{{ URL::to('customer/analysis') }}">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_TAB_CUSTOMER_TITLE")</a></li>
            @endif
            @if($is_vendor == 1)
                    <li class="active"><a href="#">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_TAB_VENDOR_TITLE")</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="customer" >
                <div class="row">
                <div class="col-md-12">
                    <form novalidate="novalidate" class="form-horizontal form-groups-bordered filter validate" method="post" id="vendor_analysis">
                        <div data-collapsed="0" class="card shadow card-primary">
                            <div class="card-header py-3">
                                <div class="card-title">
                                    @lang("routes.CUST_PANEL_FILTER_TITLE")
                                </div>
                                <div class="card-options">
                                    <a data-rel="collapse" href="#"><i class="entypo-down-open"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="col-sm-1 control-label" for="field-1">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_FILTER_FIELD_START_DATE")</label>
                                    <div class="col-sm-2" style="padding-left:0; padding-right:0; width:10%;">
                                        <input type="text" name="StartDate"  class="form-control datepicker small_fld"  data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}" data-enddate="{{date('Y-m-d')}}"/>
                                    </div>
                                    <div class="col-md-1 select_hour" style="padding: 0px; width: 9%;">
                                        <input type="text" name="StartHour" data-minute-step="30"   data-show-meridian="false" data-default-time="00:00" value="00:00"  data-template="dropdown" class="form-control timepicker small_fld">
                                    </div>
                                    <label class="col-sm-1 control-label" for="field-1">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_FILTER_FIELD_END_DATE")</label>
                                    <div class="col-sm-2" style="padding-left:0; padding-right:0; width:10%;">
                                        <input type="text" name="EndDate" class="form-control datepicker small_fld"  data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}" data-enddate="{{date('Y-m-d' )}}" />
                                    </div>
                                    <div class="col-md-1 select_hour" style="padding: 0px; width: 9%;">
                                        <input type="text" name="EndHour" data-minute-step="30"   data-show-meridian="false" data-default-time="23:30" value="23:30"   data-template="dropdown" class="form-control timepicker small_fld">
                                    </div>
                                    <label class="col-sm-1 control-label" for="field-1">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_FILTER_FIELD_COUNTRY")</label>
                                    <div class="col-sm-2">
                                        {{ Form::select('CountryID',$Country,'', array("class"=>"select2")) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label" for="field-1">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_FILTER_FIELD_PREFIX")</label>
                                    <div class="col-sm-2">
                                        <input type="text" name="Prefix"  class="form-control"/>
                                    </div>
                                    <label class="col-sm-1 control-label" for="field-1">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_FILTER_FIELD_TRUNK")</label>
                                    <div class="col-sm-2">
                                        {{ Form::select('TrunkID',$trunks,'', array("class"=>"select2")) }}
                                    </div>
                                    <label class="col-sm-1 control-label select_hour" for="field-1">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_FILTER_FIELD_TIMEZONE")</label>
                                    <div class="col-sm-2 select_hour">
                                        {{ Form::select('TimeZone',$timezones,'', array("class"=>"select2")) }}
                                    </div>
                                    <input type="hidden" name="CurrencyID" value="{{$CurrencyID}}">
                                    <input type="hidden" name="AccountID" value="{{Customer::get_accountID()}}">
                                    <input type="hidden" name="CompanyGatewayID" value="0">
                                    <input type="hidden" name="UserID" value="{{$UserID}}">
                                    <input type="hidden" name="Admin" value="{{$isAdmin}}">
                                    <input type="hidden" name="chart_type" value="destination">
                                    <input type="hidden" name="Prefix" value="">
                                    <input type="hidden" name="TrunkID" value="0">
                                </div>
                                <p class="pull-right">
                                    <button class="btn btn-primary btn-sm btn-icon icon-left" type="submit">
                                        <i class="entypo-search"></i>
                                        @lang("routes.BUTTON_SEARCH_CAPTION")
                                    </button>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="clear"></div>
                </div>
                @include('analysis.map')
                @include('analysis.chartreport')
                <ul class="nav nav-tabs refresh_tab">
                    <li class="active"><a href="#destination" data-toggle="tab">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_TAB_DESTINATION_TITLE")</a></li>
                    <li ><a href="#description" data-toggle="tab">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_TAB_DESTINATION_BREAK_TITLE")</a></li>
                    <li ><a href="#prefix" data-toggle="tab">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_TAB_PREFIX_TITLE")</a></li>
                    <li ><a href="#trunk" data-toggle="tab">@lang("routes.CUST_PANEL_PAGE_ANALYSIS_TAB_TRUNK_TITLE")</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="destination" >
                        @include('vendoranalysis.destination')
                        @include('vendoranalysis.destination_grid')
                    </div>
                    <div class="tab-pane" id="description" >
                        @include('vendoranalysis.desc')
                        @include('vendoranalysis.desc_grid')
                    </div>
                    <div class="tab-pane" id="prefix" >
                        @include('vendoranalysis.prefix')
                        @include('vendoranalysis.prefix_grid')
                    </div>
                    <div class="tab-pane" id="trunk" >
                        @include('vendoranalysis.trunk')
                        @include('vendoranalysis.trunk_grid')
                    </div>
                </div>
            </div>
        </div>

    @include('vendoranalysis.script')
@stop