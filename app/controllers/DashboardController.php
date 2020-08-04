<?php
use Jenssegers\Agent\Agent;
class DashboardController extends BaseController {

    
    public function __construct() {

    }

    public function home() {
        return Redirect::to('/process_redirect');
    }
    public function rmdashboard() {
        return View::make('dashboard.index');
    }
    /*public function salesdashboard(){

            $companyID = User::get_companyID();
            $userID = '';
            $isAdmin = (User::is_admin() || User::is('RateManager')) ? 1 : 0;
            $data = Input::all();


            $start_date = $prev_end_date = $original_startdate = date('Y-m-d', strtotime('-1 week'));
            $end_date = $original_enddate = date('Y-m-d');
            $prev_start_date = date('Y-m-d', strtotime('-2 week'));
            $compare_with = 0;
            $Executive =0;
            if(User::is_admin()){
                $Executive= 1;
            }

            if (isset($data['Startdate'])) {
                $prev_end_date = $start_date = $original_startdate = $data['Startdate'];
            }
            if (isset($data['Enddate'])) {
                $end_date = $original_enddate = $data['Enddate'];
                (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
                $prev_start_date = date('Y-m-d', strtotime($start_date . ' -' . (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) . ' day'));
            }
            if (User::is('AccountManager')) {
                $userID = User::get_userID();
            } else if (isset($data['account_owners']) && $data['account_owners'] > 0) {
                $userID = $data['account_owners'];
                $isAdmin = 0;
            }
            $query = "call prc_salesDashboard ($companyID,'','$userID','$isAdmin','$start_date','$end_date','','','$Executive')";
            if (isset($data['compare_with'])) {
                $compare_with = 1;
                $query = "call prc_salesDashboard ($companyID,'','$userID','$isAdmin','$start_date','$end_date','$prev_start_date','$prev_end_date','$Executive')";
            }

            $dashboardData = DataTableSql::of($query, 'sqlsrv2')->getProcResult(array('TotalSales', 'TotalActiveAccount', 'TotalInActiveAccount', 'DaySales', 'AccountSales', 'AreaSales', 'TotalInActiveAccountList','SalesExecutive' ,'PreSalesExecutive','PrevTotalSales', 'PrevTotalActiveAccount', 'PrevTotalInActiveAccount', 'PrevDaySales', 'PrevAccountSales', 'PrevAreaSales', 'PrevTotalInActiveAccountList'));

            $count = $TotalCharges = $PrevTotalCharges = 0;
            if (isset($dashboardData['data']['TotalSales'])) {
                $TotalCharges = $dashboardData['data']['TotalSales'][0]->TotalCharges;

            }
            if (isset($dashboardData['data']['PrevTotalSales'][0]->PrevTotalCharges)) {
                $PrevTotalCharges = $dashboardData['data']['PrevTotalSales'][0]->PrevTotalCharges;
            }
            if (isset($dashboardData['data']['DaySales'])) {
                while (strtotime($start_date) < strtotime($end_date)) {
                    foreach ($dashboardData['data']['DaySales'] as $saledata) {
                        $sales_data[$count]['sale_date'] = $start_date;
                        if (date("Y-m-d", strtotime($saledata->sales_date)) == $start_date) {
                            $sales_data[$count]['sales'] = $saledata->TotalCharges;
                            break;
                        } else {
                            $sales_data[$count]['sales'] = 0;
                        }
                    }
                    $count++;
                    $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
                }
            }
            $count = 0;

            if (isset($dashboardData['data']['PrevDaySales'])) {
                $start_date = $original_startdate;
                while (strtotime($prev_start_date) < strtotime($prev_end_date)) {
                    foreach ($dashboardData['data']['PrevDaySales'] as $saledata) {
                        $prev_sales_data[$start_date]['sale_date'] = $prev_start_date;
                        if (date("Y-m-d", strtotime($saledata->sales_date)) == $prev_start_date) {
                            $prev_sales_data[$start_date]['sales'] = $saledata->prevTotalCharges;
                            break;
                        } else {
                            $prev_sales_data[$start_date]['sales'] = 0;
                        }
                    }
                    $count++;
                    $prev_start_date = date("Y-m-d", strtotime("+1 day", strtotime($prev_start_date)));
                    $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
                }
            }
            $count = 0;
            if (isset($dashboardData['data']['AreaSales'])) {
                foreach ($dashboardData['data']['AreaSales'] as $topdata) {
                    $top_data[$count]['sales'] = $topdata->TotalCharges;
                    $top_data[$count]['code'] = $topdata->Country;
                    $count++;
                }
            }
            $count = 0;
            if (isset($dashboardData['data']['PrevAreaSales'])) {
                foreach ($dashboardData['data']['PrevAreaSales'] as $topdata) {
                    $prev_top_data[$count]['sales'] = $topdata->prevTotalCharges;
                    $prev_top_data[$count]['code'] = $topdata->Country;
                    $count++;
                }
            }
            if (isset($dashboardData['data']['PrevAccountSales'])) {
                foreach ($dashboardData['data']['PrevAccountSales'] as $prevaccount) {
                    $prevsales[$prevaccount->GatewayAccountID] = $prevaccount->prevTotalCharges;
                }

            }
            if (isset($dashboardData['data']['PreSalesExecutive']) && $compare_with ==1) {
                foreach ($dashboardData['data']['PreSalesExecutive'] as $SalesExecutive) {
                    $prevSalesExecutive[$SalesExecutive->Owner] = $SalesExecutive->TotalCharges;
                }

            }
            $account_owners = User::getOwnerUsersbyRole();

            return View::make('dashboard.sales', compact('dashboardData', 'TotalDueCustomer', 'TotalDueVendor', 'sales_data', 'prev_sales_data', 'top_data', 'prev_top_data', 'compare_with', 'prevsales', 'original_startdate', 'original_enddate', 'account_owners', 'userID','prevSalesExecutive'));
    }*/

    public function billingdashboard(){

       $companyID = User::get_companyID();
       $DefaultCurrencyID = Company::where("CompanyID",$companyID)->pluck("CurrencyId");
        $original_startdate = date('Y-m-d', strtotime('-1 week'));
        $original_enddate = date('Y-m-d');
        $company_gateway =  CompanyGateway::getCompanyGatewayIdList();
        $invoice_status_json = json_encode(Invoice::get_invoice_status());
        $StartDateDefault 	= 	date("Y-m-d",strtotime(''.date('Y-m-d').' -1 months'));
        $StartDateDefault1 	= 	date("Y-m-d",strtotime(''.date('Y-m-d').' -1 week'));
        $DateEndDefault  	= 	date('Y-m-d');
        $monthfilter = 'Weekly';
        if(Cache::has('billing_Chart_cache_'.User::get_companyID().'_'.User::get_userID())){
            $monthfilter = Cache::get('billing_Chart_cache_'.User::get_companyID().'_'.User::get_userID());
        }
        $GetDashboardPL = 'Daily';
        if(Cache::has('GetDashboardPL_'.User::get_companyID().'_'.User::get_userID())){
            $GetDashboardPL = Cache::get('GetDashboardPL_'.User::get_companyID().'_'.User::get_userID());
        }
        $GetDashboardPR = 'Daily';
        if(Cache::has('GetDashboardPR_'.User::get_companyID().'_'.User::get_userID())){
            $GetDashboardPR = Cache::get('GetDashboardPR_'.User::get_companyID().'_'.User::get_userID());
        }
        $BillingDashboardWidgets 	= 	CompanyConfiguration::get('BILLING_DASHBOARD');
        if(!empty($BillingDashboardWidgets)) {
            $BillingDashboardWidgets			=	explode(",",$BillingDashboardWidgets);
        }
       return View::make('dashboard.billing',compact('DefaultCurrencyID','original_startdate','original_enddate','company_gateway','invoice_status_json','StartDateDefault','DateEndDefault','monthfilter','BillingDashboardWidgets','StartDateDefault1','GetDashboardPR','GetDashboardPL'));

    }
    public function monitor_dashboard(){

        $companyID = User::get_companyID();
        $DefaultCurrencyID = Company::where("CompanyID",$companyID)->pluck("CurrencyId");
        $original_startdate = date('Y-m-d', strtotime('-1 week'));
        $original_enddate = date('Y-m-d');
        $isAdmin = 1;
        $where['Status'] = 1;
        $where['VerificationStatus'] = Account::VERIFIED;
        $where['CompanyID']=User::get_companyID();
        if(User::is('AccountManager')){
            $where['Owner'] = User::get_userID();
            $isAdmin = 0;
        }
        $agent = new Agent();
        $isDesktop = $agent->isDesktop();
        $newAccountCount = Account::where($where)->where('created_at','>=',$original_startdate)->count();
        $MonitorDashboardSetting 	= 	array_filter(explode(',',CompanyConfiguration::get('MONITOR_DASHBOARD')));

        return View::make('dashboard.dashboard',compact('DefaultCurrencyID','original_startdate','original_enddate','isAdmin','newAccountCount','isDesktop','MonitorDashboardSetting'));

    }
	
	public function CrmDashboard(){ 
        $companyID 			= 	User::get_companyID();
        $DefaultCurrencyID 	= 	Company::where("CompanyID",$companyID)->pluck("CurrencyId");
		$Country 			= 	Country::getCountryDropdownIDList();
        $account 			= 	Account::getAccountIDList();
		$currency 			= 	Currency::getCurrencyDropdownIDList();
		$UserID 			= 	User::get_userID();
		//$isAdmin 			= 	(User::is_admin() || User::is('RateManager')) ? 1 : 0;
        $isAdmin 			= 	(User::is_admin()) ? 1 : 0;
		$users			 	= 	User::getUserIDListAll(0);
		//$StartDateDefault 	= 	date("m/d/Y",strtotime(''.date('Y-m-d').' -1 months'));
		//$DateEndDefault  	= 	date('m/d/Y');
		$StartDateDefaultforcast 	= 	date("Y-m-d",strtotime(''.date('Y-m-d').' +6 months'));
		$StartDateDefault 	= 	date("Y-m-d",strtotime(''.date('Y-m-d').' -1 months'));
		$DateEndDefault  	= 	date('Y-m-d');
	    $account_owners 	= 	User::getUserIDList();
        $boards 			= 	CRMBoard::getBoards();
		$TaskBoard			= 	CRMBoard::getTaskBoard();
        $taskStatus 		= 	CRMBoardColumn::getTaskStatusList($TaskBoard[0]->BoardID);
		$CloseStatus		=	Opportunity::Close;
		$CrmAllowedReports	=	array();
		$where['Status']=1;
        if(User::is('AccountManager')){
            $where['Owner'] = User::get_userID();
        }
		$leadOrAccount 		= 	Account::where($where)->select(['AccountName', 'AccountID'])->orderBy('AccountName')->lists('AccountName', 'AccountID');
		  if(!empty($leadOrAccount)){
            $leadOrAccount = array(""=> "Select a Company")+$leadOrAccount;
        }
        $tasktags 						= 	json_encode(Tags::getTagsArray(Tags::Task_tag));
		$CompanyCrmDashboardSetting 	= 	CompanyConfiguration::get('CRM_DASHBOARD');
		if(!empty($CompanyCrmDashboardSetting))
		{
			$CrmAllowedReports			=	explode(",",$CompanyCrmDashboardSetting);
		}
		
		
		 return View::make('dashboard.crm', compact('companyID','DefaultCurrencyID','Country','account','currency','UserID','isAdmin','users','StartDateDefault','DateEndDefault','account_owners','boards','TaskBoard','taskStatus','leadOrAccount','StartDateDefaultforcast','CloseStatus',"CrmAllowedReports"));	
	}

    public function TicketDashboard(){
        $iDisplayLength = 10;
        $status			 			=    TicketsTable::getTicketStatus(0);
        $statusOnHold = TicketsTable::getTicketStatusOnHold();
        $unresovled = $status;
        $key = array_search('Resolved', $status);
        unset($unresovled[$key]);
        return View::make('dashboard.ticket',compact('iDisplayLength','status','unresovled','statusOnHold'));

    }
	
	public function GetUsersTasks(){
       $data = Input::all();
        $data['iDisplayStart'] +=1;
		if(User::is('AccountManager')){
            $data['AccountOwner'] = User::get_userID();
        }
        //$response = NeonAPI::request('dashboard/GetUsersTasks',$data,true);

        $companyID = User::get_companyID();
        if(!isset($data['fetchType'])){
            $data['fetchType'] = 'Grid';
        }

        $data['AccountOwner'] 	= 	isset($data['AccountOwner'])?empty($data['AccountOwner'])?'':$data['AccountOwner']:'';
        $data['taskClosed'] 	= 	isset($data['taskClosed'])?empty($data['taskClosed']) || $data['taskClosed']=='false'?0:$data['taskClosed']:0;
		
		if(isset($data['DueDateFilter']) && !empty($data['DueDateFilter'])){
			if($data['DueDateFilter']=='duetoday'){
					$data['DueDateFrom'] = date('Y-m-d')." 00:00:00";
					$data['DueDateTo']   = date('Y-m-d')." 23:59:59";
			}else if($data['DueDateFilter']=='duesoon'){
					$data['DueDateFrom'] = Task::DueSoon;
					$data['DueDateTo']   = Task::DueSoon;
			}else if($data['DueDateFilter']=='overdue'){
					$data['DueDateFrom'] = Task::Overdue;
					$data['DueDateTo']   = Task::Overdue;
			}else{
					$data['DueDateFrom'] = Task::All;
					$data['DueDateTo']   = Task::All;
			}
			
		}
       
        $rules['iDisplayStart'] 	= 	'required|Min:1';
        $rules['iDisplayLength'] 	= 	'required';
        $rules['sSortDir_0'] 		= 	'required';
        
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }

        $columns 		= 	['Subject', 'DueDate', 'Status','UserID','RelatedTo'];
        $sort_column 	= 	$columns[$data['iSortCol_0']];
        $query = "call prc_GetTasksGrid (" . $companyID . ",".$data['id'].",'','" . $data['AccountOwner']. "', '0', '0','".$data['DueDateFrom']."','".$data['DueDateTo']."','0','0',".(ceil($data['iDisplayStart'] / $data['iDisplayLength'])) . " ," . $data['iDisplayLength'] . ",'" . $sort_column . "','" . $data['sSortDir_0'] . "')"; 
        
        $response = DataTableSql::of($query)->make();
        //return generateResponse('',false,false,$result);
        

        return json_response_api($response,true,true,true);
    }
	
	function GetPipleLineData(){
		 $data 			= 	 Input::all();			
         //$response 		= 	 NeonAPI::request('dashboard/GetPipleLineData',$data,true);
         
         $companyID 			= 	User::get_companyID();
         $userID 			= 	'';
         $data 				= 	Input::all();
         if(isset($data['UsersID'])){
             if(is_array($data['UsersID'])){
                 $UserID = 	implode(",",array_filter($data['UsersID']));
             }else{
                 $UserID = 	$data['UsersID'];
             }			
         }else{
             $UserID = 	$data['UsersID'];
         }
         $CurrencyID			=	(isset($data['CurrencyID']) && !empty($data['CurrencyID']))?$data['CurrencyID']:0;
         $array_return 		= 	array();
         $array_users		=	array();
         $array_worth		=	array();
         $TotalOpportunites  =   0;
         $TotalWorth			=	0;
         $query  			= 	"call prc_GetCrmDashboardPipeLine (".$companyID.",'".$UserID."','".$CurrencyID."')";  
         $result 			= 	DB::select($query);
         
         foreach($result as $result_data){
             $array_return['data'][] = array("Worth"=>$result_data->TotalWorth,"Opportunites"=>$result_data->TotalOpportunites,'User'=>$result_data->AssignedUserText,'CurrencyCode'=>$result_data->v_CurrencyCode_);
             $TotalOpportunites 			=   $result_data->TotalOpportunites+$TotalOpportunites;	
             $TotalWorth					=	$TotalWorth+$result_data->TotalWorth;	
         }
         
         foreach($result as $result_data){
             $array_users[]		=	$result_data->AssignedUserText;
             $array_worth[]		=	$result_data->TotalWorth;
         }
         
         if(!isset($array_return['data'])){
             $array_return['data']				= 	array('Worth'=>0,"Opportunites"=>0,'CurrencyCode'=>'');
         }
         $round								=	isset($result_data->RoundVal)?$result_data->RoundVal:0;
         $array_return['CurrencyCode'] 		= 	isset($result_data->v_CurrencyCode_)?$result_data->v_CurrencyCode_:'';
         $array_return['TotalOpportunites']	=	$TotalOpportunites;
         $array_return['users']				=	implode(",",$array_users);
         $array_return['worth']				=	implode(",",$array_worth);
         $array_return['TotalWorth']			=	number_format((int)$TotalWorth,(int)$round);
         //return generateResponse('',false,false,json_encode($array_return));
 
         return $response = Response::json($array_return);



		// if($response->status=='failed'){
		// 	return json_response_api($response,false,true);
		// }else{
		// 	return $response->data;
		// }
		
     }
	
	public function getSalesdata(){ //crm dashboard
		 $data 			= 	 Input::all();
         //$response 		= 	 NeonAPI::request('dashboard/GetSalesdata',$data,true);
         

         $companyID 			= 	User::get_companyID();
         $userID 		    	= 	'';
         //$data 				= 	Input::all();		
         $rules = array(
             'Closingdate' =>      'required',                 
         );
         $message	 = array("Closingdate.required"=> "Close Date field is required.");
         $validator   = Validator::make($data, $rules,$message);
         if ($validator->fails()) {
             return json_validator_response($validator);
             //return generateResponse($validator->errors(),true);
         }
         $UserID				=	(isset($data['UsersID']) && is_array($data['UsersID']))?implode(",",array_filter($data['UsersID'])):$data['UsersID'];
         $CurrencyID			=	(isset($data['CurrencyID']) && !empty($data['CurrencyID']))?$data['CurrencyID']:0; 
         $array_return 	    	= 	array();
         $array_return1 		= 	array();
         $array_date			=	array();
         $worth				    =	0;
         $array_dates		    =	array();	
         $array_users		    =	array();
         $array_worth		    =	array();				
         $total_opp			    =	0;
         $array_final 		    = 	array("count"=>0,"status"=>"success");
         $Closingdate		    =	explode(' - ',$data['Closingdate']);
         $StartDate			    =   $Closingdate[0]." 00:00:00";
         $EndDate			    =	$Closingdate[1]." 23:59:59";		
         $statusarray		    =	(isset($data['Status']) && !empty($data['Status']))?implode(",",$data['Status']):'';
         $query  			    = 	"call prc_GetCrmDashboardSales (".$companyID.",'".$UserID."', '".$statusarray."','".$CurrencyID."','".$StartDate."','".$EndDate."')"; 
         $result 			    = 	DB::select($query);
         $TotalWorth			=	0;
         
         foreach($result as $result_data){
             if(!in_array($result_data->AssignedUserText,$array_users)){			
                 $array_users[]   = $result_data->AssignedUserText;
             }
             $total_opp = $total_opp+$result_data->Opportunitescount;
             $array_worth[] = $result_data->TotalWorth;
         }
         
         foreach($result as $result_data){			
             if(!in_array($result_data->MonthName,$array_dates)){			
                 $array_dates[]   = $result_data->MonthName;
             }
         }
         
         foreach($result as $result_data){
             if(isset($array_date[$result_data->MonthName][$result_data->AssignedUserText])){
                 $current_data = $array_date[$result_data->MonthName][$result_data->AssignedUserText];	
                 $array_date[$result_data->MonthName][$result_data->AssignedUserText] 	 = 	$result_data->TotalWorth+$current_data;
             }else{
                 $array_date[$result_data->MonthName][$result_data->AssignedUserText] 	 = 	$result_data->TotalWorth;
             }			
             $worth = $worth+$result_data->TotalWorth;
         }
         
         $array_data = array();
         
         foreach($array_users as $array_users_data){
             foreach($array_dates as $array_dates_data){
                 if(isset($array_date[$array_dates_data][$array_users_data])){
                     $array_data[$array_users_data][] = $array_date[$array_dates_data][$array_users_data];
                 }else{
                     $array_data[$array_users_data][] = 0;
                 }
             }
         }
 
         
         foreach($array_data as $key => $array_data_loop){
             $array_return1[] = array("user"=>$key,"worth"=>implode(",",$array_data_loop));			
         }
         
         if(count($array_users)>0){
             $worth = number_format($worth,$result_data->round_number);
             $array_final = array("data"=>$array_return1,"dates"=>implode(",",$array_dates),'TotalWorth'=>$worth,"count"=>count($array_users),"CurrencyCode"=>$result_data->v_CurrencyCode_,"TotalOpportunites"=>$total_opp,"worth"=>implode(",",$array_worth),"users"=>implode(",",$array_users),"status"=>"success");
         }		
         
         return $response = Response::json($array_final);
        // return generateResponse('',false,false,json_encode($array_final));
 
        // if($response->status=='failed'){
		// 	return json_response_api($response,false,true);
		// }else{
		// 	return $response->data;
		// }
	}
	
	function CrmDashboardSalesRevenue(){		
		 $data 			= 	 Input::all();			
		 //$response 		= 	 NeonAPI::request('dashboard/CrmDashboardSalesRevenue',$data,true);
        
         $companyID 			= 	User::get_companyID();
         $userID 			    = 	'';
         //$data 				= 	Input::all();		
         $rules = array(
             'Duedate' =>      'required',                 
         );
         $message	 = array("Duedate.required"=> "Date field is required.");
         $validator   = Validator::make($data, $rules,$message);
         if ($validator->fails()) {
             return json_validator_response($validator); //generateResponse($validator->errors(),true);
         }
         $UserID				=	(isset($data['UsersID']) && is_array($data['UsersID']))?implode(",",array_filter($data['UsersID'])):$data['UsersID'];
         $CurrencyID			=	(isset($data['CurrencyID']) && !empty($data['CurrencyID']))?$data['CurrencyID']:0;
         $array_return 		= 	array();
         $array_return1 		= 	array();
         $array_date			=	array();
         $worth				=	0;
         $array_dates		=	array();	
         $array_users		=	array();
         $array_worth		=	array();				
         $array_users_id		=	array();
         $total_opp			=	0;
         $array_final 		= 	array("count"=>0,"status"=>"success");
         $Duedate			=	explode(' - ',$data['Duedate']);
         $StartDate			=   $Duedate[0]." 00:00:00";
         $EndDate			=	$Duedate[1]." 23:59:59";		
         $query  			= 	"CALL `prc_GetCrmDashboardSalesManager`(".$companyID.",'".$UserID."','".$CurrencyID."','".$data['ListType']."','".$StartDate."','".$EndDate."') ";  	 
         
         $result 			= 	DB::select($query);
         $TotalWorth			=	0;
         
         foreach($result as $result_data){
             if(!in_array($result_data->AssignedUserText,$array_users)){			
                 $array_users[]   = $result_data->AssignedUserText;
                 $array_users_id[$result_data->AssignedUserText] = $result_data->AssignedUserID;
             }
             $array_worth[] = $result_data->Revenue;
         }
         
         
         if($data['ListType']=='Monthly'){
             
             foreach($result as $result_data){			
                 if(!in_array($result_data->MonthName,$array_dates)){			
                     $array_dates[]   = $result_data->MonthName;
                 }
             }
             
             foreach($result as $result_data){
                 if(isset($array_date[$result_data->MonthName][$result_data->AssignedUserText])){
                     $current_data = $array_date[$result_data->MonthName][$result_data->AssignedUserText];	
                     $array_date[$result_data->MonthName][$result_data->AssignedUserText] 	 = 	$result_dataRevenue+$current_data;
                 }else{
                     $array_date[$result_data->MonthName][$result_data->AssignedUserText] 	 = 	$result_data->Revenue;
                 }			
                 $worth = $worth+$result_data->Revenue;
             }
         }
         
         if($data['ListType']=='Weekly'){
             foreach($result as $result_data){			
                 if(!in_array($result_data->Week,$array_dates)){			
                     $array_dates[]   = $result_data->Week;
                 }
             }
             
             foreach($result as $result_data){
                 if(isset($array_date[$result_data->Week][$result_data->AssignedUserText])){
                     $current_data = $array_date[$result_data->Week][$result_data->AssignedUserText];	
                     $array_date[$result_data->Week][$result_data->AssignedUserText] 	 = 	$result_dataRevenue+$current_data;
                 }else{
                     $array_date[$result_data->Week][$result_data->AssignedUserText] 	 = 	$result_data->Revenue;
                 }			
                 $worth = $worth+$result_data->Revenue;
             }
         
         }
         
         
         $array_data = array();
         
         foreach($array_users as $key => $array_users_data){  
             foreach($array_dates as $array_dates_data){ 
                 if(isset($array_date[$array_dates_data][$array_users_data])){
                     $array_data[$array_users_data][] = $array_date[$array_dates_data][$array_users_data];
                 }else{
                     $array_data[$array_users_data][] = 0;
                 }
             }
         }
         foreach($array_data as $key => $array_data_loop){
             $array_return1[] = array("user"=>$key,"worth"=>implode(",",$array_data_loop),"id"=>$array_users_id[$key]);			
         }
         
         if(count($array_users)>0){
             $worth = number_format($worth,$result_data->round_number);
             $array_final = array("data"=>$array_return1,"dates"=>implode(",",$array_dates),'TotalWorth'=>$worth,"count"=>count($array_users),"CurrencyCode"=>$result_data->v_CurrencyCode_,"worth"=>implode(",",$array_worth),"status"=>"success");
         }
        // return generateResponse('',false,false,json_encode($array_final));
 
        
        return $response = Response::json($array_final);

        //  if($response->status=='failed'){
		// 	return json_response_api($response,false,true);
		// }else{
		// 	return $response->data;
		// }
	}
	
	function GetRevenueDrillDown(){
		 $data 			= 	 Input::all();			
         //$response 		= 	 NeonAPI::request('dashboard/CrmDashboardUserRevenue',$data,true);
         


         $companyID 			= 	User::get_companyID();
         //$data 				= 	Input::all();
         $Duedate			=	explode(' - ',$data['duedate']);
         $StartDate			=   $Duedate[0]." 00:00:00";
         $EndDate			=	$Duedate[1]." 23:59:59";
         $split				=	$data['ListType']=='Weekly'?'-':'/';
         $date_range		=	explode($split,$data['date_range']);
         $WeekOrMonth		=	$date_range[0];
         $Year				=	$date_range[1];
         $CurrencyID			=	(isset($data['CurrencyID']) && !empty($data['CurrencyID']))?$data['CurrencyID']:0;
         $array_return		=	array();
         
        $query  			= 	"CALL `prc_GetCrmDashboardSalesUser`(".$companyID.",'".$data['userid']."','".$CurrencyID."','".$data['ListType']."','".$StartDate."','".$EndDate."','".$WeekOrMonth."','".$Year."') ";
        $result = DB::select($query); 
        
         foreach($result as $result_data){ 
             $array_return[] = array("Account"=>$result_data->AssignedUserText,"Revenue"=>$result_data->Revenue,'round_number'=>$result_data->round_number,'CurrencyCode'=>$result_data->CurrencyCode,"User"=>$data['name_user'],"date_range"=>$data['date_range'],"ListType"=>$data['ListType']);			
         }
                   
        //return generateResponse('',false,false,json_encode($array_return));

             
        $data = Response::json($array_return);
        return View::make('dashboard.RevenueDrillDown', compact('data'));

		// if($response->status=='failed'){
		// 	return json_response_api($response,false,true);
		// }else{
		// 	$data = json_decode($response->data);
        //     return View::make('dashboard.RevenueDrillDown', compact('data'));
		// }
	}
	
	
	public function GetForecastData(){ //crm dashboard
		 $data 			= 	 Input::all();			
         //$response 		= 	 NeonAPI::request('dashboard/GetForecastData',$data,true);
         

         $companyID 			= 	User::get_companyID();
         $userID 			= 	'';
         //$data 				= 	Input::all();		
         $rules = array(
             'Closingdate' =>      'required',                 
         );
         $message	 = array("Closingdate.required"=> "Close Date field is required.");
         $validator   = Validator::make($data, $rules,$message);
         if ($validator->fails()) {
             return json_validator_response($validator);
             //return generateResponse($validator->errors(),true);
         }
         $UserID				=	(isset($data['UsersID']) && is_array($data['UsersID']))?implode(",",array_filter($data['UsersID'])):$data['UsersID'];
         $CurrencyID			=	(isset($data['CurrencyID']) && !empty($data['CurrencyID']))?$data['CurrencyID']:0;
         $array_return 		= 	array();
         $array_return1 		= 	array();
         $array_date			=	array();
         $worth				=	0;
         $total_opp			=	0;
         $array_dates		=	array();	
         $array_users		=	array();				
         $array_final 		= 	array("count"=>0,"status"=>"success");
         $Closingdate		=	explode(' - ',$data['Closingdate']);
         $StartDate			=   $Closingdate[0]." 00:00:00";
         $EndDate			=	$Closingdate[1]." 23:59:59";		
         $statusarray		=	Opportunity::Open;
         $query  			= 	"call prc_GetCrmDashboardForecast (".$companyID.",'".$UserID."', '".$statusarray."','".$CurrencyID."','".$StartDate."','".$EndDate."')";   
         $result 			= 	DB::select($query);
         $TotalWorth			=	0;
         
         foreach($result as $result_data){
             if(!in_array($result_data->AssignedUserText,$array_users)){			
                 $array_users[]   = $result_data->AssignedUserText;
             }
             $total_opp			=	$total_opp+$result_data->Opportunitescount;
         }
         
         foreach($result as $result_data){			
             if(!in_array($result_data->MonthName,$array_dates)){			
                 $array_dates[]   = $result_data->MonthName;
             }
         }
         
         foreach($result as $result_data){
             if(isset($array_date[$result_data->MonthName][$result_data->AssignedUserText])){
                 $current_data = $array_date[$result_data->MonthName][$result_data->AssignedUserText];	
                 $array_date[$result_data->MonthName][$result_data->AssignedUserText] 	 = 	$result_data->TotalWorth+$current_data;
             }else{
                 $array_date[$result_data->MonthName][$result_data->AssignedUserText] 	 = 	$result_data->TotalWorth;
             }			
             $worth = $worth+$result_data->TotalWorth;
         }
         
         $array_data = array();
         
         foreach($array_users as $array_users_data){
             foreach($array_dates as $array_dates_data){
                 if(isset($array_date[$array_dates_data][$array_users_data])){
                     $array_data[$array_users_data][] = $array_date[$array_dates_data][$array_users_data];
                 }else{
                     $array_data[$array_users_data][] = 0;
                 }
                 
             }
         }
 
         
         foreach($array_data as $key => $array_data_loop){
             $array_return1[] = array("user"=>$key,"worth"=>implode(",",$array_data_loop));
         }
         
         if(count($array_users)>0){
             $worth = number_format($worth,$result_data->round_number);
             $array_final = array("data"=>$array_return1,"dates"=>implode(",",$array_dates),'TotalWorth'=>$worth,"count"=>count($array_users),"CurrencyCode"=>$result_data->CurrencyCode,"TotalOpportunites"=>$total_opp,"status"=>"success");
         }		
         
         //return generateResponse('',false,false,json_encode($array_final));
         return json_encode($array_final);


		// if($response->status=='failed'){
		// 	return json_response_api($response,false,true);
		// }else{
		// 	return $response->data;
		// }
	}
	
	
	
	 public function GetOpportunites(){
        $data = Input::all();   
        $data['iDisplayStart'] +=1;
        if(User::is('AccountManager')){
            $data['AccountOwner'] = User::get_userID();
        }
        //$response = NeonAPI::request('dashboard/get_opportunities_grid',$data,true);



        $companyID 	    				= 	User::get_companyID();
        //$data 						= 	Input::all();
		if(isset($data['AccountOwner'])){
			if(is_array($data['AccountOwner'])){
				$UserID = 	implode(",",array_filter($data['AccountOwner']));
			}else{
				$UserID =	$data['AccountOwner'];
				}			
		}else{
			$UserID = 	'';
		}
		
        $data['CurrencyID'] 		= 	isset($data['CurrencyID'])?empty($data['CurrencyID'])?0:$data['CurrencyID']:0;
	    $rules['iDisplayStart'] 	= 	'required|Min:1';
        $rules['iDisplayLength'] 	= 	'required';
        $rules['sSortDir_0'] 		= 	'required';
		
        $validator 					= 	Validator::make($data, $rules);
		if ($validator->fails()) {
            return json_validator_response($validator);
			//return generateResponse($validator->errors(),true);
		}

         $columns 				= 	['OpportunityName', 'Status','UserID','RelatedTo','ExpectedClosing','Value','Rating'];
         $sort_column 			= 	$columns[$data['iSortCol_0']];
		 
        $query = "call prc_GetOpportunityGrid (" . $companyID . ",'0', '','', '" . $UserID . "', 0,'1', ".$data['CurrencyID'].", '0',".(ceil($data['iDisplayStart'] / $data['iDisplayLength'])) . "," . $data['iDisplayLength'] . ",'" . $sort_column . "','" . $data['sSortDir_0'] . "')"; 

        $response = DataTableSql::of($query)->make();


        // try {
        //     $result = DataTableSql::of($query)->make();
        //     return generateResponse('',false,false,$result);
        // }catch (\Exception $ex){
        //     Log::info($ex);
        //     return $this->response->errorInternal($ex->getMessage());
        // }








        return json_response_api($response,true,true,true);
    }
	

    public function ajax_get_recent_due_sheets(){
        $companyID = User::get_companyID();
        $query = "call prc_GetDashboardDataRecentDueRateSheet (".$companyID.")";
        $dashboardData = DataTableSql::of($query)->getProcResult(array('TotalDueCustomer','TotalDueVendor'));
        $TotalDueCustomerarray = $dashboardData['data']['TotalDueCustomer'];
        $TotalDueVendorarray = $dashboardData['data']['TotalDueVendor'];
        $jsondata['TotalDueCustomerarray'] = $TotalDueCustomerarray;
        $jsondata['TotalDueVendorarray'] = $TotalDueVendorarray;
        return json_encode($jsondata);
    }

    public function ajax_get_recent_leads(){
        $companyID = User::get_companyID();
        $query = "call prc_GetDashboardRecentLeads (".$companyID.")";
        $LeadsResult = DataTableSql::of($query)->getProcResult(array('getRecentLeads'));
        $leads = [];
        $jsondata['leads'] = '';
        foreach ($LeadsResult['data']['getRecentLeads'] as $index=>$lead){
            $leads['AccountName'] = $lead->AccountName;
            $leads['Accounturl'] = URL::to('/leads/'.$lead->AccountID.'/show');
            $leads['Phone'] = $lead->Phone;
            $leads['Email'] = $lead->Email;
            $leads['created_by'] = $lead->created_by;
            $leads['daydiff'] = \Carbon\Carbon::createFromTimeStamp(strtotime($lead->created_at))->diffForHumans();
            $jsondata['leads'][] = $leads;
        }
        return json_encode($jsondata);
    }

    public function ajax_get_jobs(){
        $companyID = User::get_companyID();
        $userID = User::get_userID();
        //$isAdmin = (User::is_admin() || User::is('RateManager'))?1:0;
        $isAdmin = (User::is_admin())?1:0;
        $query = "call prc_GetDashboardDataJobs (".$companyID.','.$userID.','.$isAdmin.")";
        $JobResult = DataTableSql::of($query)->getProcResult(array('AllHeaderJobs','CountJobs'));
        $Jobs = [];
        $jsondata['Jobs'] = '';
        if(!empty($JobResult['data']['AllHeaderJobs'])) {
            foreach ($JobResult['data']['AllHeaderJobs'] as $index => $job) {
                $Jobs['JobID'] = $job->JobID;
                $Jobs['Title'] = $job->Title;
                $Jobs['Status'] = $job->Status;
                $Jobs['CreatedBy'] = $job->CreatedBy;
                $Jobs['daydiff'] = \Carbon\Carbon::createFromTimeStamp(strtotime($job->created_at))->diffForHumans();
                $jsondata['Jobs'][] = $Jobs;
            }
        }
        $jsondata['JobsCount'] = $JobResult['data']['CountJobs'];
        return json_encode($jsondata);
    }

    public function ajax_get_processed_files(){
        $companyID = User::get_companyID();
        $userID = User::get_userID();
        //$isAdmin = (User::is_admin() || User::is('RateManager'))?1:0;
        $isAdmin = (User::is_admin())?1:0;
        $query = "call prc_GetDashboardProcessedFiles (".$companyID.','.$userID.','.$isAdmin.")";
        $fileResult = DataTableSql::of($query)->getProcResult(array('RecentJobFiles'));
        $jobFiles = [];
        $jsondata['jobFiles'] = '';
        if(!empty($fileResult['data']['RecentJobFiles'])) {
            foreach ($fileResult['data']['RecentJobFiles'] as $index => $jobFile) {
                $jobFiles['JobID'] = $jobFile->JobID;
                $jobFiles['Title'] = $jobFile->Title;
                $jobFiles['Status'] = $jobFile->Status;
                $jobFiles['CreatedBy'] = $jobFile->CreatedBy;
                $jobFiles['daydiff'] = \Carbon\Carbon::createFromTimeStamp(strtotime($jobFile->created_at))->diffForHumans();
                $jsondata['jobFiles'][] = $jobFiles;
            }
        }
        return json_encode($jsondata);
    }

    public function ajax_get_recent_accounts(){
        $companyID 			= 	 User::get_companyID();        
		$data 				= 	 Input::all();	
		$UserID				=	(isset($data['UsersID']) && is_array($data['UsersID']))?implode(",",array_filter($data['UsersID'])):0;
        $AccountManager 	= 	 0;
        if (User::is('AccountManager')) { // Account Manager
            $AccountManager = 1;
        }
        $query = "call prc_GetDashboardRecentAccounts ('".$companyID."','".$UserID."','".$AccountManager."')"; 
        $accountResult = DataTableSql::of($query)->getProcResult(array('getRecentAccounts'));
        $accounts = [];
        $jsondata['accounts'] = '';
        if(!empty($accountResult['data']['getRecentAccounts'])) {
            foreach ($accountResult['data']['getRecentAccounts'] as $index => $account) {
                $accounts['AccountName'] = $account->AccountName;
                $accounts['Accounturl'] = URL::to('/accounts/'.$account->AccountID.'/show');
                $accounts['Phone'] = $account->Phone;
                $accounts['Email'] = ($account->Email==null || $account->Email=='null')?'':$account->Email;
                $accounts['created_by'] = $account->created_by;
                $accounts['daydiff'] = \Carbon\Carbon::createFromTimeStamp(strtotime($account->created_at))->diffForHumans();
                $jsondata['accounts'][] = $accounts;
            }
        }
        return json_encode($jsondata);
    }

    public function ajax_get_missing_accounts(){
        $companyID = User::get_companyID();
        $query = "call prc_getMissingAccounts (".$companyID.",".intval(Input::get('CompanyGatewayID')).")";
        $missingAccounts = DataTableSql::of($query, 'sqlsrv2')->getProcResult(array('getMissingAccounts'));
        $jsondata['missingAccounts']=$missingAccounts['data']['getMissingAccounts'];
        return json_encode($jsondata);
    }

    public function delete_gateway_missing_account($id){
        try {
            $result = DB::Connection('sqlsrv2')->statement('delete from tblGatewayAccount where AccountID IS NULL AND CompanyGatewayID=' . intval($id) );
            if($result){
                return Response::json(array("status" => "success", "message" => "Missing Gateway Accounts deleted successfully."));
            }
        }catch(Exception $ex){
            return Response::json(array("status" => "failed", "message" => $ex->getMessage()));
        }
    }

    public function getTopAlerts(){
        $getdata = Input::all();
        $getdata['iDisplayLength'] = 10;
        $getdata['iDisplayStart'] = 1;
        $getdata['sSortDir_0'] = 'desc';
        $getdata['iSortCol_0'] = 2;
        $getdata['AlertType'] = '';
        $getdata['StartDate'] = date('Y-m-d 00:00:00');
        $getdata['EndDate'] = date('Y-m-d 23:59:59');
        $alertType = array("" => "Select") + Alert::$qos_alert_type + Alert::$call_monitor_alert_type;
        if ($getdata['Type'] == 'today') {
            $getdata['StartDate'] = date('Y-m-d');
            $getdata['EndDate'] = date('Y-m-d 23:59:59');
        } else if ($getdata['Type'] == 'yesterday') {
            $getdata['StartDate'] = date('Y-m-d', strtotime('-1 day'));
            $getdata['EndDate'] = $getdata['StartDate'] . ' 23:59:59';
        } else if ($getdata['Type'] == 'yesterday2') {
            $getdata['StartDate'] = date('Y-m-d', strtotime('-2 days'));
            $getdata['EndDate'] = $getdata['StartDate'] . ' 23:59:59';
        }
        //$response = NeonAPI::request('alert/history', $getdata, false, false, false);


        //$post_data = Input::all();
        $post_data = $getdata;
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
        $AlertType = $AlertID = '0';
        if (isset($post_data['AlertID'])) {
            $AlertID = $post_data['AlertID'];
        }
        if (isset($post_data['AlertType'])) {
            $AlertType = $post_data['AlertType'];
        }
        $post_data['StartDate'] = !empty($post_data['StartTime'])?$post_data['StartDate'].' '.$post_data['StartTime']:$post_data['StartDate'];
        $post_data['EndDate'] = !empty($post_data['EndTime'])?$post_data['EndDate'].' '.$post_data['EndTime']:$post_data['EndDate'];
        $post_data['Search'] = !empty($post_data['Search'])?$post_data['Search']:'';

        $sort_column = $columns[$post_data['iSortCol_0']];
        $query = "call prc_getAlertHistory(" . $CompanyID . ",'" . intval($AlertID) . "','" . $AlertType . "','".$post_data['StartDate']."','".$post_data['EndDate']."','".$post_data['Search']."'," . (ceil($post_data['iDisplayStart'] / $post_data['iDisplayLength'])) . " ," . $post_data['iDisplayLength'] . ",'" . $sort_column . "','" . $post_data['sSortDir_0'] . "'";
        if (isset($post_data['Export']) && $post_data['Export'] == 1) {
            $result = DB::select($query . ',1)');
        } else {
            $query .= ',0)';
            $result = DataTableSql::of($query)->make();
        }
        //$response = $result;
    
        $response = new stdClass;
        $response->data =$result;
        $response->status = 'success';



        if(!empty($response) && $response->status == 'success') {
            $html = View::make('dashboard.alert_history', compact('response', 'alertType'))->render();
        }else{
            return json_response_api($response,true,true,true);
        }
        return $html;

    }

}