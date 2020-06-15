<?php
if ( Request::is('/') || Request::is('login') || Request::is('forgot_password') || Request::is('dashboard') || Request::is('registration') || Request::is('super_admin') || Request::is('global_user_select_company') ||  Request::is('reset_password')) {

    $css = [
        
 
        

        "assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css",
        "/assets/css/font-icons/entypo/css/entypo.css",
            "assets/css/font-icons/font-awesome/css/font-awesome.css",
//        "https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic",
        "https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i",

        // Dashboard
        "assets/js/rickshaw/rickshaw.min.css",
        "/assets/css/bootstrap.css",
        "/assets/css/neon-core.css",
        "/assets/css/neon-theme.css",
        "/assets/css/neon-forms.css",
        "/assets/css/custom.css",
        "assets/css/dark-bottom.css",

        //new
        "assets2/vendors/bootstrap4/css/custom.bootstrap.css",

    ];
}else{ /*if ( Request::is('users') || Request::is('users/add') || Request::is('users/edit/*') || Request::is('trunks') || Request::is('trunk/*') || Request::is('trunks/*')  || Request::is('codedecks') ) {*/

    $css = [

        "assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css",
        "assets/css/font-icons/entypo/css/entypo.css",
        "assets/css/font-icons/font-awesome/css/font-awesome.css",
//        "https://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic",
        "https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i",

        "assets/css/bootstrap.css",
        "assets/css/neon-core.css",
        "assets/css/neon-theme.css",
        "assets/css/neon-forms.css",
        "assets/js/datatables/responsive/css/datatables.responsive.css",
        "assets/js/select2/select2-bootstrap.css",
        "assets/js/select2/select2.css",
        "assets/js/selectboxit/jquery.selectBoxIt.css",
        "assets/bootstrap3-editable/css/bootstrap-editable.css",
        "assets/js/icheck/skins/minimal/_all.css",
		"assets/js/perfectScroll/css/perfect-scrollbar.css",
		"assets/js/odometer/themes/odometer-theme-default.css",	
        "assets/js/daterangepicker/daterangepicker.css",
        

        //new
        "assets2/vendors/bootstrap4/css/custom.bootstrap.css",

        // New editor
        "assets/js/summernote/summernote.css",
        "assets/css/custom.css",
        "assets/css/dark-bottom.css",



    ];

    if(NeonCookie::getCookie('customer_alignment')=="right"){
        $css[]="assets/css/bootstrap-rtl.min.css";
        $css[]="assets/css/custom-rtl.css";
    }

}
$css[]  = 'assets/css/skins/black.css';
?>
@foreach ($css as $addcss)
@if( strstr($addcss,"http"))
<link rel="stylesheet" type="text/css" href="{{$addcss}}" />
@else
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('/'); ?>/{{$addcss}}" />
@endif
@endforeach

