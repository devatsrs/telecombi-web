<?php

class BillingClassController extends \BaseController {


    public function index() {
        return View::make('billingclass.index');
    }
    public function create() {
        /*$emailTemplates = EmailTemplate::getTemplateArray();
        $SendInvoiceSetting = BillingClass::$SendInvoiceSetting;
        $timezones = TimeZone::getTimeZoneDropdownList();
        $billing_type = AccountApproval::$billing_type;
        $taxrates = TaxRate::getTaxRateDropdownIDList();
        $InvoiceTemplates = InvoiceTemplate::getInvoiceTemplateList();
        if(isset($taxrates[""])){unset($taxrates[""]);}
        $privacy = EmailTemplate::$privacy;
        $type = EmailTemplate::$Type;*/
        $BillingClassList = BillingClass::getDropdownIDList(User::get_companyID());
        return View::make('billingclass.create', compact('BillingClassList'));
        //return View::make('billingclass.create', compact('emailTemplates','taxrates','billing_type','timezones','SendInvoiceSetting','InvoiceTemplates','privacy','type'));
    }
    public function edit($id) {

        //$getdata['BillingClassID'] = $id;
        //$response =  NeonAPI::request('billing_class/get/'.$id,$getdata,false,false,false);

        $post_data = ['BillingClassID'=> $id];

        $rules['BillingClassID'] = 'required';
        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
    
        $response = BillingClass::findOrFail($BillingClassID);
 
        $response = new stdClass;
        $response->data = $response;
 

        /*$emailTemplates = EmailTemplate::getTemplateArray();
        $SendInvoiceSetting = BillingClass::$SendInvoiceSetting;
        $timezones = TimeZone::getTimeZoneDropdownList();
        $billing_type = AccountApproval::$billing_type;
        $taxrates = TaxRate::getTaxRateDropdownIDList();
        $InvoiceTemplates = InvoiceTemplate::getInvoiceTemplateList();
        if(isset($taxrates[""])){unset($taxrates[""]);}
        $BillingClass = $response->data;
        $PaymentReminders = json_decode($response->data->PaymentReminderSettings);
        $LowBalanceReminder = json_decode($response->data->LowBalanceReminderSettings);
        $InvoiceReminders = json_decode($response->data->InvoiceReminderSettings);

        //$accounts = BillingClass::getAccounts($id);
        $privacy = EmailTemplate::$privacy;
        $type = EmailTemplate::$Type;*/
        $BillingClassList = BillingClass::getDropdownIDList(User::get_companyID());
        $BillingClass = $response->data;
        $InvoiceReminders = json_decode($response->data->InvoiceReminderSettings);
        $LowBalanceReminder = json_decode($response->data->LowBalanceReminderSettings);
        $BalanceWarning = json_decode($response->data->BalanceWarningSettings);
        return View::make('billingclass.edit', compact('BillingClassList','BillingClass','InvoiceReminders','LowBalanceReminder','BalanceWarning','accounts'));
        //return View::make('billingclass.edit', compact('emailTemplates','taxrates','billing_type','timezones','SendInvoiceSetting','BillingClass','PaymentReminders','LowBalanceReminder','InvoiceTemplates','BillingClassList','InvoiceReminders','accounts','privacy','type'));
        
    }

    public function ajax_datagrid(){
        $getdata = Input::all();
        //$response =  NeonAPI::request('billing_class/datagrid',$getdata,false,false,false);

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
            $Name = '';
            if (isset($post_data['Name'])) {
                $Name = $post_data['Name'];
            }

            $sort_column = $columns[$post_data['iSortCol_0']];
            $query = "call prc_getBillingClass(" . $CompanyID . ",'" . $Name . "'," . (ceil($post_data['iDisplayStart'] / $post_data['iDisplayLength'])) . " ," . $post_data['iDisplayLength'] . ",'" . $sort_column . "','" . $post_data['sSortDir_0'] . "'";
            if (isset($post_data['Export']) && $post_data['Export'] == 1) {
                $result = DB::select($query . ',1)');
            } else {
                $query .= ',0)';
                $result = DataTableSql::of($query)->make();
            }
            $response = $result;
            //return generateResponse('',false,false,$result);
            $response = new stdClass;
            $response->data = $response;
    
        if(isset($getdata['Export']) && $getdata['Export'] == 1 && !empty($response)) {
            $excel_data = $response->data;
            $excel_data = json_decode(json_encode($excel_data), true);
            Excel::create('Billing Class', function ($excel) use ($excel_data) {
                $excel->sheet('Billing Class', function ($sheet) use ($excel_data) {
                    $sheet->fromArray($excel_data);
                });
            })->download('xls');
        }
        return json_response_api($response,true,true,true);
    }

    public function store($isModal){
        $postdata = Input::all();
        //$response =  NeonAPI::request('billing_class/store',$postdata,true,false,false);


        $post_data = Input::all();
        $CompanyID = User::get_companyID();

        $rules['Name'] = 'required|unique:tblBillingClass,Name,NULL,CompanyID,CompanyID,' . $CompanyID;
        $rules = $rules + BillingClass::$rules;
        $validator = Validator::make($post_data, $rules,BillingClass::$messages);
        if ($validator->fails()) {
            return generateResponse($validator->errors(),true);
        }
        $error_message = self::data_validate($post_data);
        if(!empty($error_message)){
            return generateResponse($error_message, true, true);
        }
      
        $insertdata = array();
        $insertdata =  $post_data;
        $insertdata = self::convert_data($post_data)+$insertdata;
        $insertdata['CompanyID'] = $CompanyID;
        $insertdata['CreatedBy'] = User::get_user_full_name();
        $insertdata['created_at'] = get_currenttime();
        $BillingClass = BillingClass::create($insertdata);

        //return generateResponse('Billing Class added successfully',false,false,$BillingClass);
 
        if(!empty($response)){
            if($isModal==1){
                return json_response_api($response);
            }
            $response->redirect =  URL::to('/billing_class/edit/' . $BillingClass);
        }
        return json_response_api($response);
    }

    public function delete($id){

        //$response =  NeonAPI::request('billing_class/delete/'.$id,array(),'delete',false,false);
        
        $BillingClassID = $id ;
        
        if (intval($BillingClassID) > 0) {

            if (!BillingClass::checkForeignKeyById($BillingClassID)) {
              
                $result = BillingClass::find($BillingClassID)->delete();
                    if ($result) {
                    $response =('Billing Class Successfully Deleted');
                } else {
                    $response =('Problem Deleting Billing Class.');
                }
            
            } else {
                $response =  ('Billing Class is in Use, You cant delete this Billing Class.');
            }
        } else {
            $response = ('Provide Valid Integer Value.');
        }
         

        return json_response_api($response);
    }

    public function update($id){
        $postdata = Input::all();
        //$response =  NeonAPI::request('billing_class/update/'.$id,$postdata,'put',false,false);
        $BillingClassID = $id ;

        if ($BillingClassID > 0) {
            $post_data = Input::all();
            $CompanyID = User::get_companyID();
            $post_data['DeductCallChargeInAdvance'] = empty($post_data['DeductCallChargeInAdvance']) ? 0 : 1;
            $post_data['SuspendAccount'] = empty($post_data['SuspendAccount']) ? 0 : 1;
            $rules['Name'] = 'required|unique:tblBillingClass,Name,' . $BillingClassID . ',BillingClassID,CompanyID,' . $CompanyID;
            $rules = $rules + BillingClass::$rules;
            $validator = Validator::make($post_data, $rules,BillingClass::$messages);
            if ($validator->fails()) {
                return json_response_api($response);
            }

            $error_message = self::data_validate($post_data);
            if(!empty($error_message)){
                return json_response_api($error_message);
            }

            $BillingClass = BillingClass::findOrFail($BillingClassID);
            $updatedata = array();
            $updatedata =  $post_data;
            $updatedata = self::convert_data($post_data,$BillingClass)+$updatedata;
            $updatedata['UpdatedBy'] = User::get_user_full_name();
            $BillingClass->update($updatedata);

            $response = ('Billing Class updated successfully');
             
        } else {
            $response = ('Provide Valid Integer Value.');
        }


        return json_response_api($response);
    }
    public function getInfo($id) {
        
        $getdata['BillingClassID'] = $id;
        //$response =  NeonAPI::request('billing_class/get/'.$id,$getdata,false,true,false);


        $post_data = ['BillingClassID'=> $id];

        $rules['BillingClassID'] = 'required';
        $validator = Validator::make($post_data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
    
        $response = BillingClass::findOrFail($BillingClassID);
 
        $response = new stdClass;
        $response->data = $response;


        return Response::json($response);
    }

}