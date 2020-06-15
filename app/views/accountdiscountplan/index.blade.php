<div class="card shadow card-primary discount-section-hide" data-collapsed="0">

    <div class="card-header py-3">
        <div class="card-title">
            Discount
        </div>

        <div class="card-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <label for="field-1" class="col-sm-1 control-label">Outbound Discount Plan</label>
            <div class="col-sm-3">
                {{Form::select('DiscountPlanID',$DiscountPlan, $DiscountPlanID,array('class'=>'form-control select2'))}}
            </div>
            <div class="col-sm-1">

                <button id="minutes_report" class="btn btn-sm btn-primary tooltip-primary" data-original-title="View Detail" title="" data-placement="top" data-toggle="tooltip" data-loading-text="Loading...">
                    <i class="fa fa-eye"></i>
                </button>

            </div>
            <label for="field-1" class="col-sm-1 control-label">Inbound Discount Plan </label>
            <div class="col-sm-3">
                {{Form::select('InboundDiscountPlanID',$DiscountPlan, $InboundDiscountPlanID,array('class'=>'form-control select2'))}}
            </div>
            <div class="col-sm-1">

                <button id="inbound_minutes_report" class="btn btn-sm btn-primary tooltip-primary" data-original-title="View Detail" title="" data-placement="top" data-toggle="tooltip" data-loading-text="Loading...">
                    <i class="fa fa-eye"></i>
                </button>

            </div>
        </div>
    </div>
</div>