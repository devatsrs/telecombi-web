<?php

class DiscountController extends \BaseController {


    public function index() {
        $currencies = Currency::getCurrencyDropdownIDList();
        $DestinationGroupSets = DestinationGroupSet::getDropdownIDList();
        return View::make('discountplan.index', compact('currencies','DestinationGroupSets'));
    }

    public function ajax_datagrid(){
        $getdata = Input::all();
        //$response =  NeonAPI::request('discountplan/datagrid',$getdata,false,false,false);


        $post_data = Input::all();
       
            $CompanyID = User::get_companyID();
            $rules['iDisplayStart'] = 'required|Min:1';
            $rules['iDisplayLength'] = 'required';
            $rules['iDisplayLength'] = 'required';
            $rules['sSortDir_0'] = 'required';
            $validator = Validator::make($post_data, $rules);
            if ($validator->fails()) {
                return json_validator_response($validator);
            }
            $post_data['iDisplayStart'] += 1;
            $columns = ['Name', 'CreatedBy', 'created_at'];
            $Name = $CodedeckID = '';
            if (isset($post_data['Name'])) {
                $Name = $post_data['Name'];
            }
            if (isset($post_data['CodedeckID'])) {
                $CodedeckID = $post_data['CodedeckID'];
            }
            $sort_column = $columns[$post_data['iSortCol_0']];
            $query = "call prc_getDiscountPlan(" . $CompanyID . ",'" . $Name . "'," . (ceil($post_data['iDisplayStart'] / $post_data['iDisplayLength'])) . " ," . $post_data['iDisplayLength'] . ",'" . $sort_column . "','" . $post_data['sSortDir_0'] . "'";
            if (isset($post_data['Export']) && $post_data['Export'] == 1) {
                $result = DB::select($query . ',1)');
            } else {
                $query .= ',0)';
                $result = DataTableSql::of($query)->make();
            }
           // return generateResponse('',false,false,$result);
         

            $response = new stdClass;
            $response->data =$result;
            $response->status = 'success';
            


        if(isset($getdata['Export']) && $getdata['Export'] == 1 && !empty($response) && $response->status == 'success') {
            $excel_data = $response->data;
            $excel_data = json_decode(json_encode($excel_data), true);
            Excel::create('Discount Plan', function ($excel) use ($excel_data) {
                $excel->sheet('Discount Plan', function ($sheet) use ($excel_data) {
                    $sheet->fromArray($excel_data);
                });
            })->download('xls');
        }
        return json_response_api($response,true,true,true);
    }

    public function store(){
        $postdata = Input::all();
        //$response =  NeonAPI::request('discountplan/store',$postdata,true,false,false);

        $post_data = Input::all();
        $CompanyID = User::get_companyID();

        $rules['Name'] = 'required|unique:tblDiscountPlan,Name,NULL,CompanyID,CompanyID,' . $CompanyID;
        $rules['DestinationGroupSetID'] = 'required|numeric';
        $rules['CurrencyID'] = 'required|numeric';

        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
        
            $insertdata = array();
            foreach ($rules as $columnname => $column) {
                $insertdata[$columnname] = $post_data[$columnname];
            }
            if (isset($post_data['Description'])) {
                $insertdata['Description'] = $post_data['Description'];
            }
            $insertdata['CompanyID'] = $CompanyID;
            $insertdata['CreatedBy'] = User::get_user_full_name();
            $insertdata['created_at'] = get_currenttime();
            $DiscountPlan = DiscountPlan::create($insertdata);
            $result = ('Discount Plan added successfully');
        
             



            $response = new stdClass;
            $response->data =$result;
            $response->status = 'success';
            


        return json_response_api($response);
    }

    public function delete($id){
        //$response =  NeonAPI::request('discountplan/delete/'.$id,array(),'delete',false,false);


        $DiscountPlanID = $id;
        if (intval($DiscountPlanID) > 0) {
            if (!DiscountPlan::checkForeignKeyById($DiscountPlanID)) {
                     
                    DiscountScheme::join('tblDiscount','tblDiscountScheme.DiscountID','=','tblDiscount.DiscountID')->where('DiscountPlanID',$DiscountPlanID)->delete();
                    Discount::where("DiscountPlanID",$DiscountPlanID)->delete();
                    $result = DiscountPlan::find($DiscountPlanID)->delete();
                    if ($result) {
                        $result =   ('Discount Plan Successfully Deleted');
                    } else {
                        $result =   ('Problem Deleting Discount Plan.');
                    }
                 
            } else {
                $result =   ('Discount Plan is in Use, You cant delete this Discount Plan.');
            }
        } else {
            $result = ('Provide Valid Integer Value.');
        }
         

        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';

        return json_response_api($response);
    }

    public function update($id){
        $postdata = Input::all();
        //$response =  NeonAPI::request('discountplan/update/'.$id,$postdata,'put',false,false);



        $post_data = Input::all();
        $CompanyID = User::get_companyID();
        $DiscountPlanID = $id;
        $rules['Name'] = 'required|unique:tblDiscountPlan,Name,' . $DiscountPlanID . ',DiscountPlanID,CompanyID,' . $CompanyID;
        $rules['DestinationGroupSetID'] = 'required|numeric';
        $rules['CurrencyID'] = 'required|numeric';
        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
        try {
            $DiscountPlan = DiscountPlan::findOrFail($DiscountPlanID);
        } catch (\Exception $e) {
            $result =  ('Discount Plan not found.');
        }
        $updatedata = array();
        foreach ($rules as $columnname => $column) {
            $updatedata[$columnname] = $post_data[$columnname];
        }
        if (isset($post_data['Description'])) {
            $updatedata['Description'] = $post_data['Description'];
        }
        $updatedata['UpdatedBy'] = User::get_user_full_name();
        $DiscountPlan->update($updatedata);
        $result =  ('Discount Plan updated successfully');
        
        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';

        return json_response_api($response);
    }

    public function show($id) {
        $currencies = Currency::getCurrencyDropdownIDList();
        $DestinationGroupSetID = DiscountPlan::where(array('DiscountPlanID'=>$id))->pluck('DestinationGroupSetID');
        $DestinationGroup = DestinationGroup::getDropdownIDList($DestinationGroupSetID);
        $name = DiscountPlan::getName($id);
        $discountplanapplied = DiscountPlan::isDiscountPlanApplied('DiscountPlan',0,$id);
        return View::make('discountplan.show', compact('currencies','DestinationGroup','id','name','discountplanapplied'));
    }

    public function discount_ajax_datagrid(){
        $getdata = Input::all();
        //$response =  NeonAPI::request('discount/datagrid',$getdata,false,false,false);


        $post_data = Input::all();
             $CompanyID = User::get_companyID();
            $rules['iDisplayStart'] = 'required|Min:1';
            $rules['iDisplayLength'] = 'required';
            $rules['iDisplayLength'] = 'required';
            $rules['sSortDir_0'] = 'required';
            $rules['DiscountPlanID'] = 'required';
            $validator = Validator::make($post_data, $rules);
            if ($validator->fails()) {
                return json_validator_response($validator);
            }
            $post_data['iDisplayStart'] += 1;
            $columns = ['Name', 'CreatedBy', 'created_at'];
            $Name = $CodedeckID = '';
            if (isset($post_data['Name'])) {
                $Name = $post_data['Name'];
            }
            if (isset($post_data['CodedeckID'])) {
                $CodedeckID = $post_data['CodedeckID'];
            }
            $sort_column = $columns[$post_data['iSortCol_0']];
            $query = "call prc_getDiscount(" . $CompanyID . ",'".intval($post_data['DiscountPlanID'])."','" . $Name . "'," . (ceil($post_data['iDisplayStart'] / $post_data['iDisplayLength'])) . " ," . $post_data['iDisplayLength'] . ",'" . $sort_column . "','" . $post_data['sSortDir_0'] . "'";
            if (isset($post_data['Export']) && $post_data['Export'] == 1) {
                $result = DB::select($query . ',1)');
            } else {
                $query .= ',0)';
                $result = DataTableSql::of($query)->make();
            }
           // return generateResponse('',false,false,$result);
        
            $response = new stdClass;
            $response->data =$result;
            $response->status = 'success';
            

        if(isset($getdata['Export']) && $getdata['Export'] == 1 && !empty($response) && $response->status == 'success') {
            $excel_data = $response->data;
            $excel_data = json_decode(json_encode($excel_data), true);
            Excel::create('Discount Plan', function ($excel) use ($excel_data) {
                $excel->sheet('Discount Plan', function ($sheet) use ($excel_data) {
                    $sheet->fromArray($excel_data);
                });
            })->download('xls');
        }
        return json_response_api($response,true,true,true);
    }

    public function discount_store(){
        $postdata = Input::all();
        //$response =  NeonAPI::request('discount/store',$postdata,true,false,false);


        $post_data = Input::all();
        $CompanyID = User::get_companyID();

        //discount
        $rules['DestinationGroupID'] = 'required|numeric';
        $rules['DiscountPlanID'] = 'required|numeric';
        $rules['Service'] = 'required|numeric';

        //discount scheme
        $rules['Threshold'] = 'required|numeric';
        $rules['Discount'] = 'required|numeric';
        $rules['Service'] = 'required|numeric';

        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
        if(Discount::where(array('DiscountPlanID'=>$post_data['DiscountPlanID'],'DestinationGroupID'=>$post_data['DestinationGroupID']))->count()){
            $result = ('Destination Group Already Taken.');
        }

        
            $discountdata = array();
            $discountdata['DestinationGroupID'] = $post_data['DestinationGroupID'];
            $discountdata['DiscountPlanID'] = $post_data['DiscountPlanID'];
            $discountdata['Service'] = $post_data['Service'];
            $discountdata['CreatedBy'] = User::get_user_full_name();
            $discountdata['created_at'] = get_currenttime();
            $Discount = Discount::create($discountdata);

            $discountschemedata = array();
            $discountschemedata['Discount'] = $post_data['Discount'];
            $discountschemedata['Threshold'] = $post_data['Threshold']*60;
            $discountschemedata['DiscountID'] = $Discount->DiscountID;
            $discountschemedata['Unlimited'] = isset($post_data['Unlimited'])?1:0;
            $discountschemedata['CreatedBy'] = User::get_user_full_name();
            $discountschemedata['created_at'] = get_currenttime();
            DiscountScheme::create($discountschemedata);


            $result = ('Discount added successfully');
        
            $response = new stdClass;
            $response->data =$result;
            $response->status = 'success';
            
        return json_response_api($response);
    }

    public function discount_delete($id){
        //$response =  NeonAPI::request('discount/delete/'.$id,array(),'delete',false,false);

        $DiscountID = $id;
        if (intval($DiscountID) > 0) {
            if (!Discount::checkForeignKeyById($DiscountID)) {
                
                    $result = DiscountScheme::where('DiscountID',$DiscountID)->delete();
                    $result = Discount::find($DiscountID)->delete();
                        if ($result) {
                        $result =  ('Discount Successfully Deleted');
                    } else {
                        $result =  ('Problem Deleting Discount.');
                    }
                
            } else {
                $result =  ('Discount is in Use, You cant delete this Discount.');
            }
        } else {
            $result =  ('Provide Valid Integer Value.');
        }
        

        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        
        return json_response_api($response);
    }

    public function discount_update($id){
        $postdata = Input::all();
        //$response =  NeonAPI::request('discount/update/'.$id,$postdata,'put',false,false);

        $DiscountID  = $id;
        if ($DiscountID > 0) {
            $post_data = Input::all();
            $CompanyID = User::get_companyID();

            //discount
            $rules['DestinationGroupID'] = 'required|numeric';
            $rules['DiscountPlanID'] = 'required|numeric';
            $rules['DiscountSchemeID'] = 'required|numeric';
            $rules['Service'] = 'required|numeric';

            //discount scheme
            $rules['Threshold'] = 'required|numeric';
            $rules['Discount'] = 'required|numeric';
            $rules['Service'] = 'required|numeric';

            $validator = Validator::make($post_data, $rules);
            if ($validator->fails()) {
                return json_validator_response($validator);
            }
            if(Discount::where('DiscountID','!=',$DiscountID)->where(array('DiscountPlanID'=>$post_data['DiscountPlanID'],'DestinationGroupID'=>$post_data['DestinationGroupID']))->count()){
                $result =  ('Destination Group Already Taken.');
            }
 
                $DiscountSchemeID = $post_data['DiscountSchemeID'];
                $Discount = Discount::findOrFail($DiscountID);
                $DiscountScheme = DiscountScheme::findOrFail($DiscountSchemeID);

                $discountdata = array();
                $discountdata['DestinationGroupID'] = $post_data['DestinationGroupID'];
                $discountdata['DiscountPlanID'] = $post_data['DiscountPlanID'];
                $discountdata['Service'] = $post_data['Service'];
                $discountdata['UpdatedBy'] = User::get_user_full_name();
                $Discount->update($discountdata);

                $discountschemedata = array();
                $discountschemedata['Discount'] = $post_data['Discount'];
                $discountschemedata['Threshold'] = $post_data['Threshold']*60;
                $discountschemedata['DiscountID'] = $Discount->DiscountID;
                $discountschemedata['Unlimited'] = isset($post_data['Unlimited'])?1:0;
                $discountschemedata['UpdatedBy'] = User::get_user_full_name();
                $DiscountScheme->update($discountschemedata);

                $result =  ('Discount updated successfully');
          
                
        } else {
            $result =  ('Provide Valid Integer Value.');
        }

        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        
        return json_response_api($response);
    }
}