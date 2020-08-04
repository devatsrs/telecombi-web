<?php 
class SiteIntegration { 

	protected $support;
	protected $TrackingEmail;
	protected $companyID;
	static    $SupportSlug			=	'support';
	static    $PaymentSlug			=	'payment';
	static    $EmailSlug			=	'email';
	static    $StorageSlug			=	'storage';
	static    $AccountingSlug		=	'accounting';
	static    $AmazoneSlug			=	'amazons3';
	static    $AuthorizeSlug		=	'authorizenet';
	static    $GatewaySlug			=	'billinggateway';
	static    $freshdeskSlug		=	'freshdesk';
	static    $mandrillSlug		=	'mandrill';
	static    $emailtrackingSlug   =   'emailtracking';
	static    $imapSlug      		=   'imap';
	static    $paypalSlug			=	'paypal';
	static    $outlookcalenarSlug	=	'outlook';
	static    $QuickBookSlug		=	'quickbook';
	static    $QuickBookDesktopSlug=	'quickbookdesktop';
	static    $StripeSlug			=	'stripe';
	static    $StripeACHSlug		=	'stripeach';
	static    $SagePaySlug			=	'sagepay';
	static    $SagePayDirectDebitSlug=	'sagepaydirectdebit';
	static    $FideliPaySlug =	'fidelipay';
	static    $XeroSlug =	'xero';
	static    $PeleCardSlug		=	'pelecard';
	static    $MerchantWarriorSlug		=	'merchantwarrior';

 	public function __construct(){
	
		//$this->companyID = 	User::get_companyID();
		$companyID = User::get_companyID();
		$this->companyID = !empty($companyID)?$companyID:User::get_companyID();
	 } 
	 
	/*
	 * Get support settings return current active support
	 */

	public function SetSupportSettings(){
		
		if(self::CheckIntegrationConfiguration(false,self::$freshdeskSlug)){		
			$configuration 		=   self::CheckIntegrationConfiguration(true,self::$freshdeskSlug);
			$data 				= 	array("domain"=>$configuration->FreshdeskDomain,"email"=>$configuration->FreshdeskEmail,"password"=>$configuration->FreshdeskPassword,"key"=>$configuration->Freshdeskkey);
			
			$this->support = new Freshdesk($data);
		}		
	}
	
	public function CheckSupportSettings(){
		 if($this->support){
            return $this->support->CheckConnection();
        }
        return false;		
				
	}
	
	/*
	 * Get support contacts from active support
	 */
	
	public function GetSupportContacts($options = array()){
        if($this->support){
            return $this->support->GetContacts($options);
        }
        return false;
    }
	
	/*
	 * Get support tickets from active support
	 */
	
	public function GetSupportTickets($options = array()){
        if($this->support){
            return $this->support->GetTickets($options);
        }
        return false;
    }
	
	/*
	 * Get support tickets conversation from active support
	 */	

	public function GetSupportTicketConversations($id){
        if($this->support){
            return $this->support->GetTicketConversations($id);
        }
        return false;
    }
	
	/*
	 * Set support proirity
	 */
	
	public function SupportSetPriority($id){
		  if($this->support){
            return $this->support->SetPriority($id);
        }
        return false;			
	}
	
	/*
	 * Set support status
	 */
	
	public function SupportSetStatus($id){
	 if($this->support){
            return $this->support->SetStatus($id);
      }
        return false;	
	}
	
	/*
	 * Set support group
	 */
	
	public function SupportSetGroup($id){
	 if($this->support){
        return $this->support->SetGroup($id);
      }
        return false;	
	}
	
	/*
	 * send mail . check active mail settings 
	 */
	
	public static function SendMail($view,$data,$companyID,$Body){
		$config = self::CheckCategoryConfiguration(true,self::$EmailSlug);
	
		switch ($config->Slug){
			case SiteIntegration::$mandrillSlug:
       		return MandrilIntegration::SendMail($view,$data,$config,$companyID,$Body);
      	  break;
		}	
	}


	/*
	 * check settings addded or not . return true,data or false
	 */ 	
	public static function  CheckIntegrationConfiguration($data=false,$slug,$companyID=0){
		
		$companyID		 =	isset($companyID) && $companyID != 0 ? $companyID : User::get_companyID();
		$Integration	 =	Integration::where(["CompanyID" => $companyID,"Slug"=>$slug])->first();	
	
		if(count($Integration)>0)
		{						
			$IntegrationSubcategory = Integration::select("*");
			$IntegrationSubcategory->join('tblIntegrationConfiguration', function($join)
			{
				$join->on('tblIntegrationConfiguration.IntegrationID', '=', 'tblIntegration.IntegrationID');
	
			})->where(["tblIntegration.CompanyID"=>$companyID])->where(["tblIntegration.IntegrationID"=>$Integration->IntegrationID])->where(["tblIntegrationConfiguration.Status"=>1]);
			 $result = $IntegrationSubcategory->first();
			 if(count($result)>0)
			 {	
				 $IntegrationData =  isset($result->Settings)?json_decode($result->Settings):array();
				 if(count($IntegrationData)>0){
					 if($data ==true){
						return $IntegrationData;
					 }else{
						return true;
					 }
				 }
			 }
		}
		return false;		
	}
	
	/*
	check main category have data or not
	*/
	public static function  CheckCategoryConfiguration($data=false,$slug){	
		
		$companyID		 =	User::get_companyID();
		$Integration	 =	Integration::where(["CompanyId" => $companyID,"Slug"=>$slug])->first();	
	
		if(count($Integration)>0)
		{						
			$IntegrationSubcategory = Integration::select("*");
			$IntegrationSubcategory->join('tblIntegrationConfiguration', function($join)
			{
				$join->on('tblIntegrationConfiguration.IntegrationID', '=', 'tblIntegration.IntegrationID');
	
			})->where(["tblIntegration.CompanyID"=>$companyID])->where(["tblIntegrationConfiguration.ParentIntegrationID"=>$Integration->IntegrationID])->where(["tblIntegrationConfiguration.Status"=>1]);
			 $result = $IntegrationSubcategory->first();
			 if(count($result)>0)
			 {	
				 $IntegrationData =  isset($result->Settings)?json_decode($result->Settings):array();
				 if(count($IntegrationData)>0){
					 if($data ==true){
						return $result;
					 }else{
						return true;
					 }
				 }
			 }
		}
		return false;		
	}	
}