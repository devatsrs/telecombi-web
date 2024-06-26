@extends('layout.main')
@section('content')
<style>
.control-label>span{
        position: relative;
        bottom: 2px;
        left:   5px;
    }
.custom_hours{display:none; padding-bottom:0px !important; }
.custom_hours_disabled input{
	
}
.custom_hours .col-sm-9{padding-bottom:15px;}
.red_icon{color:#fdb415;}
.card-title span{font-size:12px;}
</style>
<ol class="breadcrumb bc-3">
  <li> <a href="{{action('dashboard')}}"><i class="entypo-home"></i>Home</a> </li>
  <li> <a href="{{URL::to('businesshours')}}">Business Hours</a> </li>
  <li class="active"> <strong>Add Business Hours</strong> </li>
</ol>
<h3>Add Business Hours</h3>
@include('includes.errors')
@include('includes.success')
<form role="form" id="form-business-hour"  method="post" action=""  class="form-horizontal form-groups-bordered">
<p style="text-align: right;">
  <button type="button" class="save btn btn-primary btn-sm btn-icon icon-left" data-loading-text="Loading..."> <i class="entypo-floppy"></i> Save </button>
  <a href="{{URL::to('/businesshours')}}" class="btn btn-danger btn-sm btn-icon icon-left"> <i class="entypo-cancel"></i> Close </a> </p>
<br>
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow card-primary" data-collapsed="0">
        <div class="card-header py-3">
          <div class="card-title"> Basic Information </div>
          <div class="card-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="Title" name="Title" placeholder="Title" value="">
            </div>
          </div>
          <div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-6">
              <textarea class="form-control" name="Description" id="Description" rows="5" placeholder="Description"></textarea>
            </div>
          </div>
          <!--<div class="form-group">
            <label for="field-1" class="col-sm-2 control-label">Time Zone</label>
            <div class="col-sm-6"> {{Form::select('Timezone', $timezones,'',array("class"=>"form-control select2"))}} </div>
          </div>-->
          <div class="form-group">
            <label for="field-1" class="col-md-2 control-label">Helpdesk Hours</label>
            <div class="col-md-6">
              <ul class="icheck-list">
                <li>
                  <input tabindex="7" class="icheck HelpdeskHoursChange" value="{{TicketBusinessHours::$HelpdeskHours247}}" type="radio" id="HelpdeskHoursFixed" name="HelpdeskHours" checked>
                  <label for="HelpdeskHoursFixed">24 hrs x 7 days</label>
                </li>
                <li>
                  <input tabindex="8" class="icheck HelpdeskHoursChange" value="{{TicketBusinessHours::$HelpdeskHoursCustom}}" type="radio" id="HelpdeskHoursCustom" name="HelpdeskHours">
                  <label for="HelpdeskHoursCustom">Select working days/hours</label>
                </li>
              </ul>
            </div>
          </div>
          <div>
            <div class="custom_hours form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2">
                <label for="field-1" class="control-label">
                  <input checked type="checkbox" class="custom_hours_day icheck custom_hours_monday" day="Monday" name="custom_hours_day[2]" value="1">
                  <span>Monday</span></label>
              </div>
              <div class="day_time_data col-sm-9">
                <div class="col-sm-2"> {{Form::select('MondayFromHour', $TicketHours,TicketBusinessHours::$DefaultHourFrom,array("class"=>"form-control MondayFromHour row_change small select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('MondayFromType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeFrom,array("class"=>"form-control MondayFromType row_change small select2"))}} </div>
                <div class="col-sm-1">to</div>
                <div class="col-sm-2"> {{Form::select('MondayToHour', $TicketHours,TicketBusinessHours::$DefaultHourTo,array("class"=>"form-control row_change small MondayToHour select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('MondayToType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeTo,array("class"=>"form-control small MondayToType row_change select2"))}} </div>
                <div class="col-sm-3 row_time_calc"></div>
              </div>
            </div>
            <div class="custom_hours form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2">
                <label for="field-1" class="control-label">
                  <input checked type="checkbox" class="custom_hours_day icheck custom_hours_tuesday" day="Tuesday" name="custom_hours_day[3]" value="1">
                  <span>Tuesday</span></label>
              </div>
              <div class="day_time_data col-sm-9">
                <div class="col-sm-2"> {{Form::select('TuesdayFromHour', $TicketHours,TicketBusinessHours::$DefaultHourFrom,array("class"=>"form-control TuesdayFromHour small row_change select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('TuesdayFromType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeFrom,array("class"=>"form-control TuesdayFromType row_change small select2"))}} </div>
                <div class="col-sm-1">to</div>
                <div class="col-sm-2"> {{Form::select('TuesdayToHour', $TicketHours,TicketBusinessHours::$DefaultHourTo,array("class"=>"form-control TuesdayToHour small row_change select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('TuesdayToType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeTo,array("class"=>"form-control row_change TuesdayToType small select2"))}} </div>
                <div class="col-sm-3 row_time_calc"></div>
              </div>
            </div>
            <div class="custom_hours form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2">
                <label for="field-1" class="control-label">
                  <input checked type="checkbox" class="custom_hours_day icheck custom_hours_wednesday" day="Wednesday" name="custom_hours_day[4]" value="1">
                  <span>Wednesday</span></label>
              </div>
              <div class="day_time_data col-sm-9">
                <div class="col-sm-2"> {{Form::select('WednesdayFromHour', $TicketHours,TicketBusinessHours::$DefaultHourFrom,array("class"=>"form-control WednesdayFromHour small row_change select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('WednesdayFromType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeFrom,array("class"=>"form-control row_change WednesdayFromType small select2"))}} </div>
                <div class="col-sm-1">to</div>
                <div class="col-sm-2"> {{Form::select('WednesdayToHour', $TicketHours,TicketBusinessHours::$DefaultHourTo,array("class"=>"form-control WednesdayToHour row_change small select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('WednesdayToType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeTo,array("class"=>"form-control row_change WednesdayToType small select2"))}} </div>
                <div class="col-sm-3 row_time_calc"></div>
              </div>
            </div>
            <div class="custom_hours form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2">
                <label for="field-1" class="control-label">
                  <input checked type="checkbox" class="custom_hours_day icheck custom_hours_thursday" day="Thursday" name="custom_hours_day[5]" value="1">
                  <span>Thursday</span></label>
              </div>
              <div class="day_time_data col-sm-9">
                <div class="col-sm-2"> {{Form::select('ThursdayFromHour', $TicketHours,TicketBusinessHours::$DefaultHourFrom,array("class"=>"form-control ThursdayFromHour row_change small select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('ThursdayFromType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeFrom,array("class"=>"form-control row_change ThursdayFromType small select2"))}} </div>
                <div class="col-sm-1">to</div>
                <div class="col-sm-2"> {{Form::select('ThursdayToHour', $TicketHours,TicketBusinessHours::$DefaultHourTo,array("class"=>"form-control ThursdayToHour row_change small select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('ThursdayToType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeTo,array("class"=>"form-control row_change ThursdayToType small select2"))}} </div>
                <div class="col-sm-3 row_time_calc"></div>
              </div>
            </div>
            <div class="custom_hours form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2">
                <label for="field-1" class="control-label">
                  <input checked type="checkbox" class="custom_hours_day icheck custom_hours_friday" day="Friday" name="custom_hours_day[6]" value="1">
                  <span>Friday</span></label>
              </div>
              <div class="day_time_data col-sm-9">
                <div class="col-sm-2"> {{Form::select('FridayFromHour', $TicketHours,TicketBusinessHours::$DefaultHourFrom,array("class"=>"form-control FridayFromHour row_change small select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('FridayFromType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeFrom,array("class"=>"form-control row_change FridayFromType small select2"))}} </div>
                <div class="col-sm-1">to</div>
                <div class="col-sm-2"> {{Form::select('FridayToHour', $TicketHours,TicketBusinessHours::$DefaultHourTo,array("class"=>"form-control FridayToHour small row_change select2"))}} </div>
                <div class="col-sm-2"> {{Form::select('FridayToType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeTo,array("class"=>"form-control small row_change FridayToType select2"))}} </div>
                <div class="col-sm-3 row_time_calc"></div>
              </div>
            </div>
            <div class="custom_hours custom_hours_disabled form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2">
                <label for="field-1" class="control-label">
                  <input  type="checkbox" class="custom_hours_day icheck custom_hours_saturday" day="Saturday" name="custom_hours_day[7]" value="1">
                  <span>Saturday</span></label>
              </div>
              <div class="day_time_data col-sm-9">
                <div class="col-sm-2"> {{Form::select('SaturdayFromHour', $TicketHours,TicketBusinessHours::$DefaultHourFrom,array("class"=>"form-control SaturdayFromHour row_change small select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-2"> {{Form::select('SaturdayFromType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeFrom,array("class"=>"form-control row_change SaturdayFromType small select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-1">to</div>
                <div class="col-sm-2"> {{Form::select('SaturdayToHour', $TicketHours,TicketBusinessHours::$DefaultHourTo,array("class"=>"form-control SaturdayToHour row_change small select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-2"> {{Form::select('SaturdayToType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeTo,array("class"=>"form-control row_change SaturdayToType small select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-3 row_time_calc"></div>
              </div>
            </div>
            <div class="custom_hours custom_hours_disabled form-group">
              <div class="col-sm-1">&nbsp;</div>
              <div class="col-sm-2">
                <label for="field-1" class="control-label">
                  <input type="checkbox" class="custom_hours_day icheck custom_hours_sunday" day="Sunday" name="custom_hours_day[1]" value="1">
                  <span>Sunday</span></label>
              </div>
              <div class="day_time_data col-sm-9">
                <div class="col-sm-2"> {{Form::select('SundayFromHour', $TicketHours,TicketBusinessHours::$DefaultHourFrom,array("class"=>"form-control SundayFromHour row_change small select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-2"> {{Form::select('SundayFromType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeFrom,array("class"=>"form-control row_change SundayFromType small select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-1">to</div>
                <div class="col-sm-2"> {{Form::select('SundayToHour', $TicketHours,TicketBusinessHours::$DefaultHourTo,array("class"=>"form-control SundayToHour small row_change select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-2"> {{Form::select('SundayToType', $TicketHoursType,TicketBusinessHours::$DefaultTicketHoursTypeTo,array("class"=>"form-control small row_change SundayToType select2","disabled"=>"disabled"))}} </div>
                <div class="col-sm-3 row_time_calc"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- -->
      <div class="card shadow card-primary" data-collapsed="0">
        <div class="card-header py-3">
          <div class="card-title">Yearly Holiday Information <span data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Holidays will be ignored when calculating SLA for a ticket" data-original-title="Yearly Holiday Information" class="label label-info popover-primary">?</span> </div>
          <div class="card-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div>
        </div>
        <div class="card-body">
          <div class="form-group">
          	<div class="col-sm-9"> 
            <div class="col-sm-2"> {{Form::select('HolidaysMonths', TicketBusinessHours::$HolidaysMonths,'',array("class"=>"form-control HolidaysMonths  small select2"))}} </div>
            <div class="col-sm-2"> {{Form::select('HolidaysDays', TicketBusinessHours::$HolidaysDays,'',array("class"=>"form-control HolidaysDays  small select2"))}} </div>
            <div class="col-sm-5">
              <input type="text" class="form-control HolidaysName" id="HolidaysName" name="HolidaysName" placeholder="Title" value="">
            </div>
            <div class="col-sm-2"> <a class="btn add_holiday btn-primary"><i class="entypo-plus"></i>Add Holiday</a> </div>
            </div>
            <div class="col-sm-3"> </div>
          </div>
          <div class="holidaysShow"></div>          
        </div>
      </div>
      <!-- --> 
    </div>
  </div>
</form>
<script type="text/javascript">
var error_time = 0;
var error_msg  = '';
var calculate_time_error = new Array();
    jQuery(document).ready(function ($) {
		jQuery('.HelpdeskHoursChange').on('ifChecked', function(event){			
			 	if(event.target.id=="HelpdeskHoursCustom"){
					jQuery('.custom_hours').show();
					$(".row_change").trigger('change');
				}else{
					jQuery('.custom_hours').hide();
					error_time = 0;
				}
		});
		
		jQuery('.custom_hours_day').on('ifChecked', function(event){			
				  var selected_day = 	$(this).attr('day');
				  if(selected_day){				 
						$('.'+selected_day+'FromHour').prop('disabled',false);
						$('.'+selected_day+'FromType').prop('disabled',false);
						$('.'+selected_day+'ToHour').prop('disabled',false);
						$('.'+selected_day+'ToType').prop('disabled',false);
						$(".row_change").trigger('change');
				  }
			});
			
		jQuery('.custom_hours_day').on('ifUnchecked', function(event){			
				  var selected_day = 	$(this).attr('day');
				  if(selected_day){				 
						$('.'+selected_day+'FromHour').prop('disabled',true);
						$('.'+selected_day+'FromType').prop('disabled',true);
						$('.'+selected_day+'ToHour').prop('disabled',true);
						$('.'+selected_day+'ToType').prop('disabled',true);
						$(".row_change").trigger('change');
				  }
			});	
	
	 $(".row_change").change(function(e) {
		 e.preventDefault();
	var _day = 	$(this).parent().parent().parent().find('.custom_hours_day').attr('day'); 
		if(_day){
			var ArrayTime = new Array();	
			 var isDisabled = $('select.'+_day+'FromHour').is(':disabled');
			if (isDisabled) {
				$(this).parent().parent().find('.row_time_calc').html(""); 
				return false;
			} else {
				// Handle input is not disabled
			}
			
			if($('select.'+_day+'FromHour').is('[disabled=disabled]')){
				return false;
			}
		
			ArrayTime.push($('select.'+_day+'FromHour').val());
			ArrayTime.push($('select.'+_day+'FromType').val());
			ArrayTime.push($('select.'+_day+'ToHour').val());
			ArrayTime.push($('select.'+_day+'ToType').val());
	  	    var _value  = calculate_duration(ArrayTime); 
			 _condition = compute_validity(ArrayTime);
			 if(_condition){
		   		$(this).parent().parent().find('.row_time_calc').html(_value); 
			 }else{
				 error_msg  = 'Please enter a valid time for '+_day;
				 calculate_time_error.push(error_msg) ;
			 	$(this).parent().parent().find('.row_time_calc').html("<i  class='red_icon fa fa-warning'></i> Please enter a valid time"); 
			 }
		}
    });
	 
	  
	 	to_seconds = function(time, range){
			_hrs = parseInt(time.split(":")[0]);
			_mins = parseInt(time.split(":")[1]);
			_offset = (range == 'pm') ? (12 * 3600) : 0;
	
			return (( _hrs == 12 ? 0 : _hrs ) * 3600) + (_mins * 60) + _offset;
	}
	
	calculate_duration = function(range_array){ 
		start = range_array[0];
		start_range = range_array[1];
		end = range_array[2];
		end_range = range_array[3];
		
		_start = to_seconds( start, start_range )
		_end = to_seconds( end, end_range )

		_duration = (_end - _start);

		_hrs = (_start == 0 && _end == 0) ? 24 : Math.floor(_duration/3600);
		_mins = (_duration%3600)/60;

		return (_hrs ? (_hrs + " hrs ") : "") + (_mins ? (_mins + " mins") : "");
	}
	
	compute_validity = function(range_array){
		var duration;

		start = range_array[0];
		start_range = range_array[1];
		end = range_array[2];
		end_range = range_array[3];

		_morning_sec = to_seconds( start, start_range );
		_evening_sec = to_seconds( end, end_range );

		return ( _morning_sec < _evening_sec ) || (_morning_sec == 0 && _evening_sec == 0);							
	}
	
	 $(document).on("click",".holidayentry",function(ee){
	      var holiday_id_del =   $(this).attr('id');
		  $('.'+holiday_id_del).remove(); 
    });
		
	
	$('.add_holiday').click(function(e) {
       	var HolidaysMonths = $('select.HolidaysMonths').val();
		var HolidaysDays   = $('select.HolidaysDays').val();
		var HolidaysName   = $('.HolidaysName').val();
		var holiday_error  = 0;
		
		 if(!HolidaysMonths){
		 	alert("Invalid holiday month"); holiday_error = 1;
			return false;
		 }
		 
		 if(!HolidaysDays){
		 	alert("Invalid holiday date"); holiday_error = 1;
			return false;
		 }
		 
		  if(!HolidaysName){
		 	alert("Invalid holiday Name"); holiday_error = 1;
			return false;
		 }
		 	var HolidaysMonthsTitle = 	$('.HolidaysMonths option:selected').text();
			var HolidaysID 			= 	HolidaysMonthsTitle+'_'+HolidaysDays;
			
			if ( $( "."+HolidaysID ).length ) {
				 alert("Holiday already added");
				 return false;
			}
			
		 //	var HolidaysData = '<div class="'+HolidaysID+' form-group"><div class="col-sm-1"><a class="holidayentry clickable" id="'+HolidaysID+'"> X </a></div><div class="col-sm-2">'+HolidaysMonthsTitle+' '+HolidaysDays+'</div><div class="col-sm-4">'+HolidaysName+'</div> <input type="hidden" name="holidays['+HolidaysID+']" value="'+HolidaysName+'" /> </div>';	
			
			var HolidaysData = '<div class="'+HolidaysID+' form-group"><div class="col-sm-1"> <a id="'+HolidaysID+'"  title="Delete" class="btn btn-danger holidayentry clickable btn-xs btn-primary btn-sm"><i class="entypo-trash"></i></a> </div><div class="col-sm-2">'+HolidaysMonthsTitle+' '+HolidaysDays+'</div><div class="col-sm-4">'+HolidaysName+'</div> <input type="hidden" name="holidays['+HolidaysID+']" value="'+HolidaysName+'" /> </div>';	
			
			//////
			$('.holidaysShow').append(HolidaysData);
		    $('select.HolidaysMonths  option:eq(0)').attr('selected','selected');
			$('select.HolidaysDays  option:eq(0)').attr('selected','selected');
			$('.HolidaysName').val('');
    });
	
	 $(".save.btn").click(function (ev) {
            $("#form-business-hour").submit();
            $(this).button('Loading');
        });
	
	$("#form-business-hour").submit(function (event) {
            event.stopImmediatePropagation();
            event.preventDefault();					
			calculate_time_error = [];	
			$(".row_change").trigger('change'); console.log(calculate_time_error);
			
			if(calculate_time_error.length>0){
				alert(calculate_time_error[0]);	 
				scrollTo('WorkingDAysDiv');
				return false;
			}
			
			
			/*if(error_time>0){
				alert(error_msg);
				return false;
			}*/

            var formData = $($('#form-business-hour')[0]).serializeArray();	
			var url	 	 = baseurl+"/businesshours/store";   
		   	 $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
				data:formData,
				async :false,
                success: function(response) {
					
                    if(response.status =='success'){
                        toastr.success(response.message, "Success", toastr_opts);      
						window.location =  baseurl+"/businesshours";               
                    }else{
                        toastr.error(response.message, "Error", toastr_opts);
                    }
                    $(".save.btn").button('reset');
                },
			});

        });
		
		function scrollTo(hash) {
			location.hash = "#" + hash;
		}
		
	
	});
</script> 
@stop