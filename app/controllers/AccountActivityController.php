<?php
use Carbon\Carbon;
class AccountActivityController extends \BaseController {

    public function ajax_datagrid($AccountID){
        $data = Input::all();
        $CompanyID = User::get_companyID();
        $select = ["Title", "Description", "Date" ,"ActivityType","created_at","ActivityID"];
        $activities = AccountActivity::where(array('AccountID'=>$AccountID,'CompanyID'=>$CompanyID));
        $today = Carbon::toDay()->toDateTimeString();
        if($data['activityStatus']==1){
            $activities->where('Date','>=',$today);
        }else{
            $activities->where('Date','<=',$today);
        }
        if(!empty($data['activityType'])){
            $activities->where(array('activityType'=>$data['activityType']));
        }
        if(!empty($data['Title'])){
            $activities->where('Title','like','%'.$data['Title'].'%');
        }
        $activities->select($select);
        return Datatables::of($activities)->make();
    }

	/**
	 * Store a newly created resource in storage.
	 * POST /accountsubscription
	 *
	 * @return Response
	 */
	public function store($AccountID)
	{
		$data = Input::all();
        $data["AccountID"] = $AccountID;
        $data['CompanyID'] = User::get_companyID();
        $data["CreatedBy"] = User::get_user_full_name();

        $rules = array(
            'Title' =>      'required',
            'ActivityType'=>'required',
            'Date'=>'required',
            'Time'=>'required'
        );
        $validator = Validator::make($data, $rules);
        $data['Date'] = $data['Date'].' '.$data['Time'];
        unset($data['activityID']);
        unset($data['Time']);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
        if (AccountActivity::create($data)) {
            return Response::json(array("status" => "success", "message" => "Activity Successfully Created"));
        } else {
            return Response::json(array("status" => "failed", "message" => "Problem Creating Activity."));
        }
	}

	public function update($AccountID,$ActivityID)
	{
        $data = Input::all();
        $AccountActivity = AccountActivity::find($ActivityID);
        $data["AccountID"] = $AccountID;
        $data['CompanyID'] = User::get_companyID();
        $data["ModifiedBy"] = User::get_user_full_name();
        $rules = array(
            'Title' =>      'required',
            'ActivityType'=>'required',
            'Date'=>'required',
            'Time'=>'required'
        );
        $validator = Validator::make($data, $rules);
        $data['Date'] = $data['Date'].' '.$data['Time'];
        unset($data['activityID']);
        unset($data['Time']);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
        if ($AccountActivity->update($data)) {
            return Response::json(array("status" => "success", "message" => "Activity Successfully Updated"));
        } else {
            return Response::json(array("status" => "failed", "message" => "Problem Updating Activity."));
        }
	}


	public function delete($AccountID,$ActivityID)
	{
        if( intval($ActivityID) > 0){
            try{
                $AccountActivity = AccountActivity::find($ActivityID);
                $result = $AccountActivity->delete();
                if ($result) {
                    return Response::json(array("status" => "success", "message" => "Activity Successfully Deleted"));
                } else {
                    return Response::json(array("status" => "failed", "message" => "Problem Deleting Activity."));
                }
            }catch (Exception $ex){
                return Response::json(array("status" => "failed", "message" => "Problem Deleting. Exception:". $ex->getMessage()));
            }
        }
	}

    public function ajax_datagrid_email_log($AccountID){
        $data = Input::all();
        $CompanyID = User::get_companyID();
        $where['Accountid'] = $AccountID;
        $where['CompanyID'] = $CompanyID;
        $select = ["Emailfrom","EmailTo","Subject","created_at","CreatedBy","AccountEmailLogID"];
        $emaillog = AccountEmailLog::where(array('AccountID'=>$AccountID,'CompanyID'=>$CompanyID));
        $emaillog->select($select);
        return Datatables::of($emaillog)->make();
    }

    public function sendMail($AccountID){
        $data = Input::all();
        $rules = array(
            'Subject'=>'required',
            'Message'=>'required'
        );
       $account = Account::find($AccountID);
        $CompanyID = User::get_companyID();
        if(CompanyConfiguration::get('EMAIL_TO_CUSTOMER') == 1){
            $data['EmailTo'] = $account->Email;//$account->Email;
        }else{
            $data['EmailTo'] = Company::getEmail($CompanyID);//$account->Email;
        }
        $validator = Validator::make($data,$rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
		
        try{
            $status = sendMail('emails.account.AccountEmailSend',$data);
            if($status['status'] == 1){
                $data['AccountID'] 		=  $account->AccountID;
				$data['message_id'] 	=  isset($status['message_id'])?$status['message_id']:"";
                email_log($data);
                return Response::json(array("status" => "success", "message" => "Email sent Successfully"));
            } else {
                return Response::json(array("status" => "failed", "message" => "Problem Sending Email."));
            }
        }catch (Exception $ex){
            return Response::json(array("status" => "failed", "message" => "Problem sending. Exception:". $ex->getMessage()));
        }


    }
	
	public function sendMailApi($AccountID)
	{ 
		$usertype = 0;
        $data = Input::all();
        $rules = array(
            'Subject'=>'required',
            'Message'=>'required'
        );
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
		
		if(isset($data['usertype'])  && $data['usertype']==Messages::UserTypeContact){
				$data['ContactID']   =   $AccountID;
			$usertype 		  		 =    1;
		}else{
			$data['AccountID']		 =   $AccountID;
		
		}
		
        $attachmentsinfo        =	$data['attachmentsinfo']; 
        if(!empty($attachmentsinfo) && count($attachmentsinfo)>0){
            $files_array = json_decode($attachmentsinfo,true);
        }

        if(!empty($files_array) && count($files_array)>0) {
            $FilesArray = array();
            foreach($files_array as $key=> $array_file_data){
                $file_name  = basename($array_file_data['filepath']); 
                $amazonPath = AmazonS3::generate_upload_path(AmazonS3::$dir['EMAIL_ATTACHMENT']);
                $destinationPath = CompanyConfiguration::get('UPLOAD_PATH') . '/' . $amazonPath;

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                copy($array_file_data['filepath'], $destinationPath . $file_name);
                if (!AmazonS3::upload($destinationPath . $file_name, $amazonPath)) {
                    return Response::json(array("status" => "failed", "message" => "Failed to upload file." ));
                }
                $FilesArray[] = array ("filename"=>$array_file_data['filename'],"filepath"=>$amazonPath . $file_name);
                @unlink($array_file_data['filepath']);
            }
            $data['file']		=	json_encode($FilesArray);
		} 
		
		$data['name']			=    Auth::user()->FirstName.' '.Auth::user()->LastName;
		
		$data['address']		=    Auth::user()->EmailAddress; 
	   
        //$response 				= 	NeonAPI::request('accounts/sendemail',$data,true,false,true);				
        

        $usertype = 0;	 //acount by default	
        $rules = array(
			"email-to" =>'required',
            'Subject'=>'required',
            'Message'=>'required'			
        );

		if(isset($data['usertype'])  && $data['usertype']==Messages::UserTypeContact){
			$Contact	 	=	Contact::find($data['ContactID']);	
			$usertype 		  		 =    1;
		}else{
			$account	 	=    Account::find($data['AccountID']);	
		}
		
 	    $data['EmailTo']	= 	$data['email-to'];

        $validator = Validator::make($data,$rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
		$files = '';
        if (isset($data['file']) && !empty($data['file'])) {
            $data['AttachmentPaths'] = json_decode($data['file'],true);
			$files = serialize(json_decode($data['file'],true));			
        }
		
		if(isset($data['EmailParent'])){
			$ParentEmail 		   =  AccountEmailLog::find($data['EmailParent']);
			$data['In-Reply-To']   =  $ParentEmail->MessageID;
		}

        $JobLoggedUser = User::find(User::get_userID());
		if($usertype){
			$replace_array  = Contact::create_replace_array_contact($Contact,array(),$JobLoggedUser);
		}else{
       		 $replace_array = Account::create_replace_array($account,array(),$JobLoggedUser);
		}
        $data['Message'] = template_var_replace($data['Message'],$replace_array);
		// image upload end
		
			
		
        $data['mandrill'] = 0;
		$data = cleanarray($data,[]);	
		//$data['CompanyName'] = $account->AccountName;
		$data['CompanyName'] = User::get_user_full_name(); //logined user's name as from name
		
        try{
			 DB::beginTransaction();
        	 Contact::CheckEmailContact($data['EmailTo'],isset($data['AccountID'])?$data['AccountID']:0);
			 Contact::CheckEmailContact($data['cc'],isset($data['AccountID'])?$data['AccountID']:0);
			 Contact::CheckEmailContact($data['bcc'],isset($data['AccountID'])?$data['AccountID']:0);		
			 
			 if(isset($data['createticket']) && TicketsTable::getTicketLicense()){ //check and create ticket
			 	$email_from_data   	= 	TicketGroups::where(["GroupReplyAddress"=>$data['email-from']])->select('GroupName','GroupID','GroupReplyAddress')->get();
				$TicketData = array(
					"CompanyID"=>User::get_companyID(),
					"Requester"=>$data['EmailTo'],
					"Subject"=>isset($data['Subject'])?$data['Subject']:'',
					"Type"=>0,
					"Group"=>isset($email_from_data[0])?$email_from_data[0]->GroupID:0,
					"Status"=>TicketsTable::getDefaultStatus(),
					"Priority"=>TicketPriority::getDefaultPriorityStatus(),					
					"Description"=>isset($data['Message'])?$data['Message']:'',	 
					"AttachmentPaths"=>$files,
					"TicketType"=>TicketsTable::EMAIL,
					"created_at"=>date("Y-m-d H:i:s"),
					"created_by"=>User::get_user_full_name()
				);
			
				$MatchArray  		  =     TicketsTable::SetEmailType($data['EmailTo']);
				$TicketData 		  = 	array_merge($TicketData,$MatchArray);
				
				$TicketID = TicketsTable::insertGetId($TicketData);	
				if(count($email_from_data)>0){
				 	$data['EmailFrom']	   	  = 	$email_from_data[0]->GroupReplyAddress;
				 	$data['CompanyName']  	  = 	$email_from_data[0]->GroupName;		
				}else{
				 	$data['EmailFrom']	   	  = 	$data['email-from'];
				}
			 }else{
				 $data['EmailFrom']	   = 	$data['email-from'];
			 }
			 
			if(isset($data['email_send'])&& $data['email_send']==1) {
					  
                $status = sendMail('emails.template', $data);
            }else{$status = array("status"=>1);}
			if($status['status']==0){
				 return generateResponse($status['message'],true,true);
			}
			
			$data['message_id'] 	=  isset($status['message_id'])?$status['message_id']:"";			
            $result 				= 	email_log_data($data,'emails.template');
           	$result->message 		= 	'Email Sent Successfully';
			$multiple_addresses		= 	strpos($data['EmailTo'],',');

			if($multiple_addresses == false){
				$user_data 				= 	User::where(["EmailAddress" => $data['EmailTo']])->get();
				if(count($user_data)>0) {
					$result->EmailTo = $user_data[0]['FirstName'].' '.$user_data[0]['LastName'];
				}
			} 
			 if(isset($data['createticket']) && TicketsTable::getTicketLicense()){ //check and create ticket
			 	TicketsTable::find($TicketID)->update(array("AccountEmailLogID"=>$result->AccountEmailLogID));
				 $TicketEmails1		=  new TicketEmails(array("TicketID"=>$TicketID,"TriggerType"=>array("RequesterNewTicketCreated")));				 
				 $TicketEmails 		=  new TicketEmails(array("TicketID"=>$TicketID,"TriggerType"=>"CCNewTicketCreated"));
				if(isset($email_from_data[0])){
				  $TicketEmails 	=  new TicketEmails(array("TicketID"=>$TicketID,"TriggerType"=>array("AgentAssignedGroup")));					
				}
				 
			 }
			  DB::commit(); 
            return generateResponse('',false,false,$result);
        }catch (Exception $ex){ 	
			 DB::rollback(); 
        	 return $this->response->errorInternal($ex->getMessage());
        }




		
		if($response->status=='failed'){
				return  json_response_api($response);
		}else{	
				if(!empty($files_array) && count($files_array)>0) {
					foreach($files_array as $key=> $array_file_data){
						  @unlink($array_file_data['filepath']);
					}
				}									
				$response 		 = 	$response->data;
				$response->type  = 	Task::Mail;			
				$response->LogID = 	$response->AccountEmailLogID;
		}
			
			$key 			= $data['scrol']!=""?$data['scrol']:0;	
			$current_user_title = Auth::user()->FirstName.' '.Auth::user()->LastName;
			if($usertype){
				return View::make('contacts.timeline.show_ajax_single', compact('response','current_user_title','key'));  
			}else{
				return View::make('accounts.show_ajax_single', compact('response','current_user_title','key'));  
			}
	}


	function EmailAction(){
		$data 		   		= 	  Input::all();
		$action_type   		=     $data['action_type'];
		$email_number  		=     $data['email_number'];
		$usertype 			= 	  0;
		if(isset($data['ContactID']) && !empty($data['ContactID'])){
			$AccountID  		=     $data['ContactID'];
			$ContactData 		= 	  Contact::where(array('ContactID'=>$AccountID))->select('FirstName','LastName')->first();
			$AccountName 		= 	  $ContactData->FirstName.' '.$ContactData->LastName;
			$AccountEmail 		= 	  Contact::where(array('ContactID'=>$AccountID))->pluck('Email');		
			$usertype			=	  1;
		}else{
			$AccountID  		=     $data['AccountID'];
			$AccountName 		= 	  Account::where(array('AccountID'=>$AccountID))->pluck('AccountName');
			$AccountEmail 		= 	  Account::where(array('AccountID'=>$AccountID))->pluck('Email');
		}
		//$response_email     =     NeonAPI::request('account/get_email',array('EmailID'=>$email_number),false,true);
        $data = array('EmailID'=>$email_number);
        $rules['EmailID'] = 'required';
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return json_validator_response($validator);
        }
        try {
            $response_email = AccountEmailLog::find($data['EmailID']);
        } catch (\Exception $e) {
            Log::info($e);
            //return Response::json(array("status" => "failed", "message" =>  $e->getMessage()));
            $response_email = [];
        }



			$response_data      =  	  $response_email['data'];	
			$parent_id          =  	  $response_data['EmailParent'];	
			if(!empty($parent_id)){
				$parent_data 	=	 AccountEmailLog::find($parent_id);
			}else{$parent_data = array();}
			$emailTemplates 			= 	 $this->ajax_getEmailTemplate(EmailTemplate::PRIVACY_OFF,EmailTemplate::ACCOUNT_TEMPLATE);
			
			$FromEmails	 				= 	TicketGroups::GetGroupsFrom();			
			
			if($action_type=='forward'){ //attach current email attachments
			$data['uploadtext']  = 	 UploadFile::DownloadFileLocal($response_data['AttachmentPaths'],'reply');
			}
			if($usertype){
			return View::make('contacts.timeline.emailaction', compact('data','response_data','action_type','parent_data','emailTemplates','AccountName','AccountEmail','uploadtext','FromEmails')); 
			}else{
			return View::make('accounts.emailaction', compact('data','response_data','action_type','parent_data','emailTemplates','AccountName','AccountEmail','uploadtext','FromEmails'));  			
			}

        
	}	
	
    public function delete_email_log($AccountID,$logID){
        if( intval($logID) > 0){
            try{
                $accountemaillog = AccountEmailLog::find($logID);
                $result = $accountemaillog->delete();
                if ($result) {
                    return Response::json(array("status" => "success", "message" => "Email log Successfully Deleted"));
                } else {
                    return Response::json(array("status" => "failed", "message" => "Problem Deleting Email log."));
                }
            }catch (Exception $ex){
                return Response::json(array("status" => "failed", "message" => "Problem Deleting. Exception:". $ex->getMessage()));
            }
        }
    }

    public function view_email_log($AccountID,$logID){
       return AccountEmailLog::find($logID);
    }

    public function getAttachment($emailID,$attachmentID){
        //$response = NeonAPI::request('emailattachment/'.$emailID.'/getattachment/'.$attachmentID,[],true,true,true);
        $response = [];
        if(intval($emailID)>0) {
            $email = AccountEmailLog::find($emailID);
            $attachments = unserialize($email->AttachmentPaths);
            $attachment = $attachments[$attachmentID];
            if(!empty($attachment)){
                $response = $attachment;
            }
        }

            $Comment  = 	json_response_api($response,true,false,false);
            $FilePath =  	AmazonS3::preSignedUrl($Comment['filepath']);
            if(file_exists($FilePath)){
                download_file($FilePath);
            }else{
                header('Location: '.$FilePath);
            }
            exit;

    }
	
	 function GetReplyAttachment($emailID,$attachmentID)
	 {
		  
		 $email 		= 	AccountEmailLog::where(['AccountEmailLogID'=>$emailID])->first();		 
		 $attachments 	= 	unserialize($email->AttachmentPaths);
		 if($attachments)
		 { 
			 if(isset($attachments[$attachmentID]))
			 {
		 		$file			=	$attachments[$attachmentID];
				$FilePath 		= 	AmazonS3::preSignedUrl($file['filepath']);
                 if(file_exists($FilePath)){
                     download_file($FilePath);
                 }elseif(is_amazon() == true){
					header('Location: '.$FilePath); exit;
                 }
			 }
		 }			
	 }	
	 
	 public function ajax_getEmailTemplate($privacy, $type){
        $filter = array();
        /*if($type == EmailTemplate::ACCOUNT_TEMPLATE){
            $filter =array('Type'=>EmailTemplate::ACCOUNT_TEMPLATE);
        }elseif($type== EmailTemplate::RATESHEET_TEMPLATE){
            $filter =array('Type'=>EmailTemplate::RATESHEET_TEMPLATE);
        }*/
		$filter =array('StaticType'=>EmailTemplate::DYNAMICTEMPLATE);
        if($privacy == 1){
            $filter ['UserID'] =  User::get_userID();
        }
        return EmailTemplate::getTemplateArray($filter);
    }

}