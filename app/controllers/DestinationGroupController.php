<?php

class DestinationGroupController extends \BaseController {


    public function index() {
        $CodedeckList = BaseCodeDeck::getCodedeckIDList();
        return View::make('destinationgroup.index', compact('CodedeckList'));
    }

    public function ajax_datagrid(){
        $getdata = Input::all();
        //$response =  NeonAPI::request('destinationgroupset/datagrid',$getdata,false,false,false);

        $post_data = Input::all();
        
        $CompanyID = User::get_companyID();
        $rules['iDisplayStart'] = 'required|Min:1';
        $rules['iDisplayLength'] = 'required';
        $rules['iDisplayLength'] = 'required';
        $rules['sSortDir_0'] = 'required';
        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return generateResponse($validator->errors(),true);
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
        $query = "call prc_getDestinationGroupSet(" . $CompanyID . ",'" . $Name . "','" . intval($CodedeckID) . "'," . (ceil($post_data['iDisplayStart'] / $post_data['iDisplayLength'])) . " ," . $post_data['iDisplayLength'] . ",'" . $sort_column . "','" . $post_data['sSortDir_0'] . "'";
        if (isset($post_data['Export']) && $post_data['Export'] == 1) {
            $result = DB::select($query . ',1)');
        } else {
            $query .= ',0)';
            $result = DataTableSql::of($query)->make();
        }
        //return generateResponse('',false,false,$result);
        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        
         



        if(isset($getdata['Export']) && $getdata['Export'] == 1 && !empty($response) && $response->status == 'success') {
                $excel_data = $response->data;
                $excel_data = json_decode(json_encode($excel_data),true);
                Excel::create('Destination Group', function ($excel) use ($excel_data) {
                    $excel->sheet('Destination Group', function ($sheet) use ($excel_data) {
                        $sheet->fromArray($excel_data);
                    });
                })->download('xls');
        }
        return json_response_api($response,true,true,true);
    }
    public function store(){
        $postdata = Input::all();
        //$response =  NeonAPI::request('destinationgroupset/store',$postdata,true,false,false);

        $post_data = Input::all();
        $CompanyID = User::get_companyID();

        $rules['Name'] = 'required|unique:tblDestinationGroupSet,Name,NULL,CompanyID,CompanyID,' . $CompanyID;
        $rules['CodedeckID'] = 'required';
        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return generateResponse($validator->errors(),true);
        }
        $insertdata = array();
        $insertdata['Name'] = $post_data['Name'];
        $insertdata['CodedeckID'] = $post_data['CodedeckID'];
        $insertdata['CompanyID'] = $CompanyID;
        $insertdata['CreatedBy'] = User::get_user_full_name();
        $insertdata['created_at'] = get_currenttime();
        $DestinationGroupSet = DestinationGroupSet::create($insertdata);
        //return generateResponse('DestinationGroup Set added successfully');
        $result = 'DestinationGroup Set added successfully';
        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        

        return json_response_api($response);
    }

    public function delete($id){

        //$response =  NeonAPI::request('destinationgroupset/delete/'.$id,array(),'delete',false,false);

        $DestinationGroupSetID = $id;
        if (intval($DestinationGroupSetID) > 0) {
            if (!DestinationGroupSet::checkForeignKeyById($DestinationGroupSetID)) {
                try {
                    DB::beginTransaction();
                    DestinationGroupCode::join('tblDestinationGroup','tblDestinationGroup.DestinationGroupID','=','tblDestinationGroupCode.DestinationGroupID')->where('DestinationGroupSetID',$DestinationGroupSetID)->delete();
                    DestinationGroup::where("DestinationGroupSetID",$DestinationGroupSetID)->delete();
                    $result = DestinationGroupSet::find($DestinationGroupSetID)->delete();
                    DB::commit();
                    if ($result) {
                        $result = ('Destination Group Set Successfully Deleted');
                    } else {
                        $result =  ('Problem Deleting Destination Group Set.');
                    }
                } catch (\Exception $ex) {
                    Log::info($ex);
                    try {
                        DB::rollback();
                    } catch (\Exception $err) {
                        Log::error($err);
                    }
                    $result =  ('Destination Group Set is in Use, You cant delete this Destination Group Set.');
                }
            } else {
                $result = ('Destination Group Set is in Use, You cant delete this Destination Group Set.');
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
        //$response =  NeonAPI::request('destinationgroupset/update/'.$id,$postdata,'put',false,false);

        $DestinationGroupSetID = $id;
        if ($DestinationGroupSetID > 0) {
            $post_data = Input::all();
            $CompanyID = User::get_companyID();

            $rules['Name'] = 'required|unique:tblDestinationGroupSet,Name,' . $DestinationGroupSetID . ',DestinationGroupSetID,CompanyID,' . $CompanyID;
            $rules['CodedeckID'] = 'required';
            $validator = Validator::make($post_data, $rules);
            if ($validator->fails()) {
                return json_validator_response($validator);
            }
            
                $DestinationGroupSet = DestinationGroupSet::findOrFail($DestinationGroupSetID);
                $updatedata = array();
                if (isset($post_data['Name'])) {
                    $updatedata['Name'] = $post_data['Name'];
                }
                if (isset($post_data['CodedeckID'])) {
                    $updatedata['CodedeckID'] = $post_data['CodedeckID'];
                }
                $DestinationGroupSet->update($updatedata);
                $result = ('Destination Group Set updated successfully');
            
        } else {
            $result = ('Provide Valid Integer Value.');
        }

        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        

        return json_response_api($response);
    }
    public function show($id) {
        $DestinationGroupSetID = $id;
        $name = DestinationGroupSet::getName($id);
        $discountplanapplied = DiscountPlan::isDiscountPlanApplied('DestinationGroupSet',$id,0);
        return View::make('destinationgroup.show', compact('DestinationGroupSetID','countries','name','discountplanapplied'));
    }
    public function group_show($id) {
        $countries  = Country::getCountryDropdownIDList();
        $DestinationGroupID = $id;
        $DestinationGroupSetID = DestinationGroup::where("DestinationGroupID",$DestinationGroupID)->pluck('DestinationGroupSetID');
        $groupname = DestinationGroupSet::getName($DestinationGroupSetID);
        $name = DestinationGroup::getName($id);
        $discountplanapplied = DiscountPlan::isDiscountPlanApplied('DestinationGroupSet',$DestinationGroupSetID,0);
        return View::make('destinationgroup.groupshow', compact('DestinationGroupSetID','DestinationGroupID','countries','name','groupname','discountplanapplied'));
    }

    public function group_ajax_datagrid(){
        $getdata = Input::all();
        //$response =  NeonAPI::request('destinationgroup/datagrid',$getdata,false,false,false);

        $post_data = Input::all();

        $CompanyID = User::get_companyID();
        $rules['iDisplayStart'] = 'required|Min:1';
        $rules['iDisplayLength'] = 'required';
        $rules['sSortDir_0'] = 'required';
        $rules['DestinationGroupSetID'] = 'required';
        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return generateResponse($validator->errors(),true);
        }
        $post_data['iDisplayStart'] += 1;
        $columns = ['Name', 'CreatedBy', 'created_at'];
        $Name = $DestinationGroupSetID = '';
        if (isset($post_data['Name'])) {
            $Name = $post_data['Name'];
        }
        if (isset($post_data['DestinationGroupSetID'])) {
            $DestinationGroupSetID = $post_data['DestinationGroupSetID'];
        }
        $sort_column = $columns[$post_data['iSortCol_0']];
        $query = "call prc_getDestinationGroup(" . $CompanyID . ",'" . intval($DestinationGroupSetID) . "','" . $Name . "'," . (ceil($post_data['iDisplayStart'] / $post_data['iDisplayLength'])) . " ," . $post_data['iDisplayLength'] . ",'" . $sort_column . "','" . $post_data['sSortDir_0'] . "'";
        if (isset($post_data['Export']) && $post_data['Export'] == 1) {
            $result = DB::select($query . ',1)');
        } else {
            $query .= ',0)';
            $result = DataTableSql::of($query)->make();
        }
        //return generateResponse('',false,false,$result);
    
        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        
        if(isset($getdata['Export']) && $getdata['Export'] == 1 && !empty($response) && $response->status == 'success') {
            $excel_data = $response->data;
            $excel_data = json_decode(json_encode($excel_data), true);
            Excel::create('Destination Group Set', function ($excel) use ($excel_data) {
                $excel->sheet('Destination Group Set', function ($sheet) use ($excel_data) {
                    $sheet->fromArray($excel_data);
                });
            })->download('xls');
        }
        return json_response_api($response,true,true,true);
    }


    public function code_ajax_datagrid(){
        $getdata = Input::all();
        //$response =  NeonAPI::request('destinationgroupsetcode/datagrid',$getdata,false,false,false);


        $post_data = Input::all();
 
            $rules['iDisplayStart'] = 'required|Min:1';
            $rules['DestinationGroupSetID'] = 'required';
            $rules['iDisplayLength'] = 'required';
            $validator = Validator::make($post_data, $rules);
            if ($validator->fails()) {
                return json_validator_response($validator);
            }
            $DestinationGroupID = $CountryID = $Selected = 0;
            $Code = $Description = '';
            $post_data['iDisplayStart'] += 1;
            if (isset($post_data['DestinationGroupID'])) {
                $DestinationGroupID = $post_data['DestinationGroupID'];
            }
            if (isset($post_data['Code'])) {
                $Code = $post_data['Code'];
            }
            if (isset($post_data['Description'])) {
                $Description = $post_data['Description'];
            }
            if (isset($post_data['CountryID'])) {
                $CountryID = (int)$post_data['CountryID'];
            }
            if (isset($post_data['Selected'])) {
                $Selected = $post_data['Selected'] == 'true'?1:0;
            }
            $query = "call prc_getDestinationCode(" . intval($post_data['DestinationGroupSetID']) . "," . intval($DestinationGroupID) . ",'".$CountryID."','".$Code."','".$Selected."','".$Description."','".(ceil($post_data['iDisplayStart'] / $post_data['iDisplayLength']))."','".$post_data['iDisplayLength']."')";
            $result = DataTableSql::of($query)->make();

        
         //return generateResponse('',false,false,$result);


        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        


        if(isset($getdata['Export']) && $getdata['Export'] == 1 && !empty($response) && $response->status == 'success') {
            $excel_data = $response->data;
            $excel_data = json_decode(json_encode($excel_data), true);
            Excel::create('Destination Group', function ($excel) use ($excel_data) {
                $excel->sheet('Destination Group', function ($sheet) use ($excel_data) {
                    $sheet->fromArray($excel_data);
                });
            })->download('xls');
        }
        return json_response_api($response,true,true,true);
    }
    public function group_store(){

        $postdata = Input::all();
        if(isset($postdata['RateID'])) {
            $postdata['RateID'] = implode(',', $postdata['RateID']);
        }
        if(isset($postdata['FilterCode'])) {
            $postdata['Code'] = $postdata['FilterCode'];
        }
        if(isset($postdata['FilterDescription'])) {
            $postdata['Description'] = $postdata['FilterDescription'];
        }
        //$response =  NeonAPI::request('destinationgroup/store',$postdata,true,false,false);


        $post_data = $postdata; //Input::all();
        $CompanyID = User::get_companyID();

        $rules['Name'] = 'required|unique:tblDestinationGroup,Name,NULL,CompanyID,CompanyID,' . $CompanyID.',DestinationGroupSetID,'.$post_data['DestinationGroupSetID'];
        $rules['DestinationGroupSetID'] = 'required';
        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
        $insertdata = array();
        $insertdata['Name'] = $post_data['Name'];
        $insertdata['DestinationGroupSetID'] = $post_data['DestinationGroupSetID'];
        $insertdata['CompanyID'] = $CompanyID;
        $insertdata['CreatedBy'] = User::get_user_full_name();
        $insertdata['created_at'] = get_currenttime();
        $DestinationGroup = DestinationGroup::create($insertdata);


        $result = ('Destination Group added successfully');

        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        


        return json_response_api($response);
    }
    public function group_delete($id){
        //$response =  NeonAPI::request('destinationgroup/delete/'.$id,array(),'delete',false,false);


             if (!DestinationGroup::checkForeignKeyById($DestinationGroupID)) {
                try {
                    DB::beginTransaction();
                    $result = DestinationGroupCode::where('DestinationGroupID',$DestinationGroupID)->delete();
                    $result = DestinationGroup::find($DestinationGroupID)->delete();
                    DB::commit();
                    if ($result) {
                        $result =('Destination Group Successfully Deleted');
                    } else {
                        $result = ('Problem Deleting Destination Group.');
                    }
                } catch (\Exception $ex) {
                    Log::info($ex);
                    try {
                        DB::rollback();
                    } catch (\Exception $err) {
                        Log::error($err);
                    }
                    Log::info('Destination Group is in Use');
                    $result = ('Destination Group is in Use, You cant delete this Destination Group.');
                }
            } else {
                $result = ('Destination Group is in Use, You cant delete this Destination Group.');
            }

            $response = new stdClass;
            $response->data =$result;
            $response->status = 'success';
            
        return json_response_api($response);
    }
    public function group_update($id){
        $postdata = Input::all();
        if(isset($postdata['RateID'])) {
            $postdata['RateID'] = implode(',', $postdata['RateID']);
        }
        if(isset($postdata['FilterCode'])) {
            $postdata['Code'] = $postdata['FilterCode'];
        }
        if(isset($postdata['FilterDescription'])) {
            $postdata['Description'] = $postdata['FilterDescription'];
        }
        //$response =  NeonAPI::request('destinationgroup/update/'.$id,$postdata,'put',false,false);

        $DestinationGroupID = $id;
        if ($DestinationGroupID > 0) {
            //$post_data = Input::all();
            $post_data = $postdata;
            $CompanyID = User::get_companyID();

            //$rules['Name'] = 'required|unique:tblDestinationGroup,Name,' . $DestinationGroupID . ',DestinationGroupID,CompanyID,' . $CompanyID;
            $rules['DestinationGroupID'] = 'required';
            $validator = Validator::make($post_data, $rules);
            if ($validator->fails()) {
                return json_validator_response($validator);
            }
                      $DestinationGroup = DestinationGroup::findOrFail($DestinationGroupID);

                    $updatedata = array();
                if (isset($post_data['Name'])) {
                    $updatedata['Name'] = $post_data['Name'];
                }
                $RateID= $Description =  $Code = $Action ='';
                $CountryID = 0;
                if(isset($post_data['RateID'])) {
                    $RateID = $post_data['RateID'];
                }
                if(isset($post_data['Code'])) {
                    $Code = $post_data['Code'];
                }
                if(isset($post_data['CountryID'])) {
                    $CountryID = intval($post_data['CountryID']);
                }
                if(isset($post_data['Description'])) {
                    $Description = $post_data['Description'];
                }
                if(isset($post_data['Action'])) {
                    $Action = $post_data['Action'];
                }
                $DestinationGroup->update($updatedata);
                $insert_query = "call prc_insertUpdateDestinationCode(?,?,?,?,?,?)";
                DB::statement($insert_query,array(intval($DestinationGroup->DestinationGroupID),$RateID,$CountryID,$Code,$Description,$Action));
                $result =  ('Destination Group updated successfully');
            
        } else {
            $result = ('Provide Valid Integer Value.' );
        }

        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        
        return json_response_api($response);
    }
    public function update_name($id){
        $postdata = Input::all();
        //$response =  NeonAPI::request('destinationgroup/update_name/'.$id,$postdata,'put',false,false);

        $DestinationGroupID = $id;
        if ($DestinationGroupID > 0) {
            $post_data = Input::all();
            $CompanyID = User::get_companyID();

            $rules['Name'] = 'required|unique:tblDestinationGroup,Name,' . $DestinationGroupID . ',DestinationGroupID,CompanyID,' . $CompanyID.',DestinationGroupSetID,'.$post_data['DestinationGroupSetID'];
            $rules['DestinationGroupID'] = 'required';
            $validator = Validator::make($post_data, $rules);
            if ($validator->fails()) {
                return json_validator_response($validator);
            }
           
                
                    $DestinationGroup = DestinationGroup::findOrFail($DestinationGroupID);
                 
                $updatedata = array();
                if (isset($post_data['Name'])) {
                    $updatedata['Name'] = $post_data['Name'];
                }
                $DestinationGroup->update($updatedata);
                $result =  ('Destination Group updated successfully');
             
        } else {
            $result =  ('Provide Valid Integer Value.');
        }
        
        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';
        

        return json_response_api($response);
    }
}