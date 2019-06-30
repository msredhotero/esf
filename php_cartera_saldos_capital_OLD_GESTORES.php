<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Cache-Control: private");
header("Pragma: no-cache"); // HTTP/1.0 
$ewCurSec = 0; // Initialise

if (@$_SESSION["php_project_esf_status"] <> "login") {
	header("Location:  login.php");
	exit();
}

$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=carterasaldoscap.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
<?php

$currentdate = getdate(time());
$currdate = $currentdate["mday"]."/".$currentdate["mon"]."/".$currentdate["year"];	

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);


if($_POST["x_dias_ini"]){
	$_SESSION["x_dias_ini"] = $_POST["x_dias_ini"];
	$_SESSION["x_dias_fin"] = $_POST["x_dias_fin"];		
}else{
	if(empty($_SESSION["x_dias_ini"])){
		$_SESSION["x_dias_ini"] = 1;
		$_SESSION["x_dias_fin"] = 999999999;		
	}
}


$x_nombre_srch = $_POST["x_nombre_srch"];
$x_apepat_srch = $_POST["x_apepat_srch"];
$x_apemat_srch = $_POST["x_apemat_srch"];
$x_crenum_srch = $_POST["x_crenum_srch"];
$x_clinum_srch = $_POST["x_clinum_srch"];
$x_promo_srch = $_POST["x_promo_srch"];
$x_empresa_id = $_POST["x_empresa_id"];
$x_sucursal_srch = $_POST["x_sucursal_srch"];
$x_cresta_srch = $_POST["x_cresta_srch"];


ResetCmd();

$x_posteo = $x_nombre_srch.$x_apepat_srch.$x_apemat_srch.$x_crenum_srch.$x_clinum_srch.$x_promo_srch.$x_sucursal_srch;

if(!empty($x_nombre_srch)){
	$_SESSION["x_nombre_srch"] = $x_nombre_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_nombre_srch"] = "";
	}
}
if(!empty($x_apepat_srch)){
	$_SESSION["x_apepat_srch"] = $x_apepat_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_apepat_srch"] = "";
	}
}
if(!empty($x_apemat_srch)){
	$_SESSION["x_apemat_srch"] = $x_apemat_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_apemat_srch"] = "";
	}
}
if(!empty($x_crenum_srch)){
	$_SESSION["x_crenum_srch"] = $x_crenum_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_crenum_srch"] = "";
	}
}
if(!empty($x_clinum_srch)){
	$_SESSION["x_clinum_srch"] = $x_clinum_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_clinum_srch"] = "";
	}
}
if(!empty($x_promo_srch)){
	if($x_promo_srch < 1000){
		$_SESSION["x_promo_srch"] = $x_promo_srch;
	}else{
		$_SESSION["x_promo_srch"] = "";
	}
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_promo_srch"] = "";
	}
}
if(!empty($x_cresta_srch)){
	$_SESSION["x_cresta_srch"] = $x_cresta_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_cresta_srch"] = "";
	}
}

// EN clientes
if((!empty($_SESSION["x_nombre_srch"])) || (!empty($_SESSION["x_apepat_srch"])) || (!empty($_SESSION["x_apemat_srch"])) || (!empty($_SESSION["x_clinum_srch"])) || (!empty($_SESSION["x_promo_srch"]))){
	$ssrch = "";
	if(!empty($_SESSION["x_nombre_srch"])){
		$ssrch .= "(cliente.nombre_completo like '%".$_SESSION["x_nombre_srch"]."%') AND ";
	}
	if(!empty($_SESSION["x_apepat_srch"])){
		$ssrch .= "(cliente.apellido_paterno like '%".$_SESSION["x_apepat_srch"]."%') AND ";
	}
	if(!empty($_SESSION["x_apemat_srch"])){
		$ssrch .= "(cliente.apellido_materno like '%".$_SESSION["x_apemat_srch"]."%') AND ";
	}
	if(!empty($_SESSION["x_clinum_srch"])){
		$ssrch .= "(cliente.cliente_num+0 = ".$_SESSION["x_clinum_srch"].") AND ";
	}
	if(!empty($_SESSION["x_promo_srch"])){
		$ssrch .= "(solicitud.promotor_id = ".$_SESSION["x_promo_srch"].") AND ";
	}

	$ssrch = substr($ssrch, 0, strlen($ssrch)-5);
	
	$ssrch_sql = "select solicitud.solicitud_id from solicitud join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id where ".$ssrch;
	$rs_qry = phpmkr_query($ssrch_sql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	$nTotalRecs = phpmkr_num_rows($rs_qry);
	if($nTotalRecs >0){
		while ($row_sqry = @phpmkr_fetch_array($rs_qry)) {
			$ssrch_cli .= $row_sqry[0].","; 			
		}
		if(strlen($ssrch_cli) > 0 ){
			$ssrch_cli = " credito.solicitud_id in (".substr($ssrch_cli, 0, strlen($ssrch_cli)-1).") AND ";	
		}else{
			$ssrch_cli = "";
		}
	}else{
		$ssrch_cli = "";
	}
}else{
	$ssrch_cli = "";
}

// En Credito
	if((!empty($_SESSION["x_crenum_srch"])) || (!empty($_SESSION["x_cresta_srch"]))){
		$ssrch_cre = "";
		if(!empty($_SESSION["x_crenum_srch"])){
			$ssrch_cre .= "(credito.credito_num+0 = ".$_SESSION["x_crenum_srch"].") AND ";
		}
		if(!empty($_SESSION["x_cresta_srch"]) && ($_SESSION["x_cresta_srch"] != "100")){
			$ssrch_cre .= "(credito.credito_status_id = ".$_SESSION["x_cresta_srch"].") AND ";
		}
		if(strlen($ssrch_cre) > 0 ){
//			$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);	
		}else{
			$ssrch_cre = "";
		}
	}else{
		$ssrch_cre = "";
	}



if(!empty($x_sucursal_srch)){
	$_SESSION["x_sucursal_srch"] = $x_sucursal_srch;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_sucursal_srch"] = "";
	}
}




if(!empty($_SESSION["x_sucursal_srch"])){
	if(!empty($_SESSION["x_sucursal_srch"]) && ($_SESSION["x_sucursal_srch"] != "1000")){
		$ssrch_cre .= "(promotor.sucursal_id = ".$_SESSION["x_sucursal_srch"].") AND ";
		$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
	}
}


if(!empty($x_empresa_id)){
	$_SESSION["x_empresa_id"] = $x_empresa_id;
}else{
	if(strlen($x_posteo) > 0){
		$_SESSION["x_empresa_id"] = "";
	}
}


if(!empty($_SESSION["x_empresa_id"])){
	if(!empty($_SESSION["x_empresa_id"]) && ($_SESSION["x_empresa_id"] != "999999999")){
		$ssrch_cre .= "(credito.credito_id in (select credito_id from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_empresa.fondeo_empresa_id = ".$_SESSION["x_empresa_id"].")) AND ";
		
		if(!empty($_SESSION["x_fondeo_credito_id"])){
			$ssrch_cre .= "(fondeo_credito.fondeo_credito_id = ".$_SESSION["x_fondeo_credito_id"].") AND ";
		}
		
		
		
	}
//	$ssrch_cre = substr($ssrch_cre, 0, strlen($ssrch_cre)-5);		
}


$sWhere = $ssrch_cli.$ssrch_cre;




if(!empty($_POST["x_credito_tipo_id"]) && $_POST["x_credito_tipo_id"] < 1000){
	$_SESSION["x_credito_tipo_id"] = $_POST["x_credito_tipo_id"];
	$sWhere .= " (credito.credito_tipo_id = ".$_SESSION["x_credito_tipo_id"]. ") AND ";	
}


/*
$sWhere .= " (credito.credito_num+0 in (
33,
66,
171,
442,
456,
461,
536,
599,
610,
627,
736,
917,
921,
926,
1000,
1009,
1014,
1035,
1065,
1083,
1089,
1148,
1179,
1182,
1199,
1206,
1219,
1244,
1248,
1250,
1252,
1256,
1259,
1289,
1295,
1305,
1312,
1318,
1361,
1425,
1433,
1444,
1450,
1475,
1488,
1500,
1505,
1533,
1553,
1577,
1579,
1604,
1618,
1620,
1661,
1671,
1675,
1676,
1709,
1716,
1720,
1751,
1753,
1756,
1773,
1801,
1839,
1887,
1888,
1898,
1903,
1906,
1912,
1922,
1931,
1946,
1965,
1973,
1979,
2000,
2021,
2037,
2047,
2062,
2068,
2069,
2086,
2094,
2098,
2099,
2104,
2105,
2128,
2132,
2133,
2138,
2158,
2159,
2173,
2185,
2191,
2202,
2211,
2212,
2233,
2240,
2242,
2245,
2274,
2293,
2300,
2301,
2302,
2305,
2309,
2313,
2317,
2342,
2349,
2357,
2360,
2378,
2382,
2387,
2402,
2408,
2409,
2419,
2429,
2443,
2449,
2452,
2487,
2498,
2517,
2518,
2534,
2547,
2557,
2560,
2578,
2579,
2591,
2599,
2603,
2604,
2605,
2611,
2613,
2619,
2628,
2634,
2640,
2670,
2677,
2688,
2691,
2699,
2701,
2706,
2707,
2710,
2722,
2743,
2751,
2762,
2782,
2795,
2799,
2807,
2819,
2820,
2833,
2834,
2841,
2850,
2865,
2868,
2874,
2880,
2881,
2882,
2883,
2887,
2893,
2896,
2898,
2904,
2907,
2908,
2914,
2915,
2920,
2923,
2927,
2930,
2931,
2936,
2958,
2959,
2962,
2963,
2964,
2967,
2973,
2977,
2987,
2988,
2989,
2998,
3002,
3006,
3025,
3031,
3034,
3039,
3057,
3064,
3069,
3073,
3074,
3076,
3088,
3092,
3097,
3115,
3116,
3120,
3121,
3128,
3135,
3138,
3149,
3160,
3175,
3176,
3179,
3180,
3188,
3189,
3193,
3194,
3200,
3207,
3208,
3215,
3217,
3218,
3219,
3221,
3223,
3233,
3237,
3238,
3239,
3240,
3241,
3253,
3256,
3290,
3299,
3300,
3303,
3306,
3307,
3308,
3313,
3315,
3318,
3319,
3320,
3322,
3332,
3335,
3338,
3340,
3342,
3344,
3348,
3349,
3350,
3351,
3352,
3354,
3356,
3358,
3360,
3362,
3367,
3376,
3379,
3381,
3383,
3387,
3390,
3391,
3392,
3396,
3397,
3403,
3404,
3405,
3410,
3411,
3412,
3417,
3418,
3421,
3422,
3424,
3425,
3427,
3429,
3430,
3432,
3436,
3438,
3440,
3441,
3444,
3445,
3446,
3447,
3449,
3450,
3451,
3457,
3459,
3460,
3461,
3462,
3465,
3468,
3469,
3472,
3473,
3477,
3478,
3480,
3481,
3482,
3483,
3484,
3490,
3491,
3495,
3496,
3498,
3499,
3501,
3502,
3503,
3505,
3506,
3507,
3508,
3509,
3510,
3516,
3521,
3522,
3523,
3525,
3529,
3531,
3533,
3535,
3538,
3539,
3540,
3541,
3543,
3544,
3546,
3547,
3550,
3551,
3553,
3555,
3557,
3558,
3559,
3562,
3564,
3565,
3566,
3567,
3568,
3569,
3570,
3571,
3572,
3573,
3574,
3575,
3576,
3577,
3579,
3580,
3581,
3582,
3583,
3585,
3586,
3587,
3588,
3589,
3591,
3592,
3593,
3594,
3595,
3596,
3599,
3600,
3600,
3601,
3602,
3603,
3604,
3605,
3606,
3607,
3608,
3609,
3610,
3611,
3613,
3615,
3617,
3618,
3619,
3620,
3621,
3622,
3623,
3624,
3626,
3627,
3629,
3630,
3632,
3633,
3634,
3635,
3636,
3637,
3638,
3639,
3640,
3641,
3642,
3643,
3644,
3645,
3647,
3648,
3649,
3651,
3652,
3653,
3654,
3655,
3657,
3658,
3659,
3660,
3661,
3662,
3664,
3665,
3666,
3667,
3668,
3670,
3671,
3672,
3673,
3674,
3675,
3676,
3677,
3678,
3679,
3680,
3682,
3683,
3684,
3685,
3686,
3688,
3689,
3690,
3691,
3692,
3693,
3694,
3695,
3696,
3697,
3698,
3699,
3700,
3702,
3703,
3704,
3705,
3706,
3707,
3708,
3709,
3710,
3711,
3712,
3713,
3714,
3715,
3717,
3718,
3719,
3720,
3721,
3722,
3723,
3724,
3725,
3726,
3727,
3728,
3729,
3730,
3731,
3732,
3733,
3734,
3735,
3736,
3737,
3738,
3739,
3740,
3741,
3742,
3742,
3744,
3745,
3746,
3747,
3749,
3750,
3751,
3752,
3753,
3754,
3755,
3757,
3758,
3759,
3760,
3761,
3762,
3763,
3764,
3765,
3766,
3767,
3768,
3769,
3770,
3771,
3772,
3773,
3775,
3777,
3778,
3779,
3780,
3781,
3782,
3782,
3783,
3784,
3785,
3786,
3787,
3788,
3789,
3790,
3791,
3792,
3793,
3794,
3795,
3797,
3798,
3799,
3800,
3801,
3802,
3805,
3807,
3808,
3809,
3810,
3811,
3812,
3813,
3814,
3815,
3816,
3817,
3818,
3820,
3821,
3822,
3823,
3824,
3825,
3826,
3828,
3829,
3830,
3831,
3832,
3833,
3834,
3837,
3838,
3839,
3840,
3841,
3842,
3843,
3844,
3845,
3846,
3847,
3848,
3849,
3850,
3851,
3852,
3853,
3854,
3855,
3856,
3857,
3858,
3859,
3860,
3861,
3862,
3863,
3864,
3866,
3867,
3868,
3869,
3870,
3871,
3872,
3873,
3874,
3876,
3877,
3878,
3879,
3880,
3881,
3882,
3883,
3885,
3887,
3888,
3889,
3891,
3892,
3893,
3894,
3895,
3896,
3897,
3898,
3899,
3900,
3901,
3902,
3903,
3904,
3905,
3906,
3907,
3908,
3909,
3910,
3911,
3912,
3913,
3914,
3915,
3916,
3917,
3918,
3919,
3920,
3921,
3922,
3923,
3924,
3926,
3927,
3928,
3930,
3931,
3932,
3933,
3934,
3935,
3936,
3937,
3938,
3939,
3940,
3941,
3942,
3943,
3944,
3945,
3946,
3947,
3948,
3949,
3950,
3951,
3952,
3953,
3954,
3955,
3956,
3957,
3958,
3959,
3960,
3961,
3962,
3963,
3964,
3965,
3966,
3967,
3968,
3969,
3970,
3971,
3972,
3973,
3974,
3975,
3976,
3977,
3978,
3979,
3980,
3981,
3982,
3983,
3984,
3985,
3986,
3988,
3989,
3990,
3991,
3992,
3993,
3994,
3995,
3996,
3997,
3998,
3999,
4000,
4001,
4002,
4003,
4004,
4005,
4006,
4007,
4008,
4009,
4010,
4011,
4012,
4013,
4014,
4015,
4016,
4017,
4018,
4019,
4020,
4021,
4022,
4023,
4024,
4025,
4027,
4028,
4029,
4030,
4031,
4032,
4033,
4034,
4035,
4037,
4038,
4039,
4040,
4041,
4042,
4043,
4044,
4045,
4046,
4047,
4048,
4049,
4050,
4051,
4052,
4053,
4054,
4055,
4056,
4057,
4058,
4059,
4060,
4061,
4062,
4063,
4064,
4065,
4066,
4067,
4068,
4070,
4071,
4072,
4073,
4074,
4075,
4076,
4077,
4078,
4079,
4080,
4082,
4083,
4084,
4085,
4086,
4087,
4088,
4089,
4090,
4091,
4092,
4093,
4094,
4095,
4096,
4097,
4098,
4099,
4100,
4101,
4102,
4103,
4104,
4105,
4106,
4107,
4108,
4109,
4110,
4111,
4112,
4113,
4114,
4115,
4116,
4117,
4118,
4119,
4120,
4121,
4122,
4124,
4125,
4126,
4127,
4128,
4129,
4130,
4131,
4132,
4133,
4134,
4135,
4136,
4137,
4138,
4139,
4140,
4141,
4142,
4143,
4144,
4145,
4146,
4147,
4148,
4149,
4150,
4151,
4152,
4154,
4155,
4156,
4157,
4158,
4159,
4160,
4161,
4162,
4163,
4164,
4165,
4166,
4167,
4168,
4169,
4170,
4171,
4172,
4173,
4174,
4175,
4176,
4177,
4178,
4179,
4180,
4181,
4182,
4183,
4184,
4185,
4186,
4187,
4188,
4189,
4190,
4191,
4192,
4193,
4194,
4195,
4196,
4197,
4198,
4199,
4200,
4201,
4202,
4203,
4204,
4205,
4206,
4207,
4208,
4209,
4210,
4211,
4212,
4213,
4214,
4215,
4216,
4217,
4218,
4219,
4220,
4221)) AND ";	
*/

if ($sWhere != ""){
	if (substr($sWhere, -5) == " AND ") {
		$sWhere = " AND ".substr($sWhere, 0, strlen($sWhere)-5);
	}else{
		$sWhere = " AND ".$sWhere;
	}
}

// Build SQL
if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
	$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id in (1,3)) AND (vencimiento.vencimiento_status_id = 3) AND (solicitud.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"]. ") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.") group by vencimiento.credito_id order by credito.credito_num+0";
}else{
	if($_SESSION["php_project_esf_status_UserRolID"] == 5) {
		$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id = 4) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].$sWhere.")";
	}else{
		$sSql = "select vencimiento.credito_id from vencimiento join credito on credito.credito_id = vencimiento.credito_id join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id where (credito.credito_status_id not in (2)) ".$sWhere;
	}
	
	$sSql .= " group by vencimiento.credito_id order by credito.credito_num+0";

}


//echo $sSql; // Uncomment to show SQL for debugging
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/skins/aqua/theme.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-sp.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="ew.js"></script>
<script src="lineafondeohint.js"></script>
<?php } ?>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="utilerias/datefunc.js"></script>
<?php } ?>
<script type="text/javascript">
<!--
function filtrar(){
EW_this = document.filtro;
validada = true;

	if (validada && EW_this.x_dias_ini && !EW_hasValue(EW_this.x_dias_ini, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_dias_ini, "TEXT", "Indique el numero de dias de inicio."))
			validada = false;
	}
	if (validada == true && EW_this.x_dias_ini && !EW_checkinteger(EW_this.x_dias_ini.value)) {
		if (!EW_onError(EW_this, EW_this.x_dias_ini, "TEXT", "El numero de dias de inicio es incorrecto, por favor verifiquelo."))
			validada = false;
	}

	if (validada && EW_this.x_dias_fin && !EW_hasValue(EW_this.x_dias_fin, "TEXT" )) {
		if (!EW_onError(EW_this, EW_this.x_dias_fin, "TEXT", "Indique el numero de dias de fin."))
			validada = false;
	}
	if (validada == true && EW_this.x_dias_fin && !EW_checkinteger(EW_this.x_dias_fin.value)) {
		if (!EW_onError(EW_this, EW_this.x_dias_fin, "TEXT", "El numero de dias de fin es incorrecto, por favor verifiquelo."))
			validada = false;
	}


	if(validada == true){
		EW_this.submit();
	}
}
//-->
</script>

<script type="text/javascript">
<!--
var firstrowoffset = 1; // first data row start at
var tablename = 'ewlistmain'; // table name
var lastrowoffset = 0; // footer row
var usecss = true; // use css
var rowclass = 'ewTableRow'; // row class
var rowaltclass = 'ewTableAltRow'; // row alternate class
var rowmoverclass = 'ewTableHighlightRow'; // row mouse over class
var rowselectedclass = 'ewTableSelectRow'; // row selected class
var roweditclass = 'ewTableEditRow'; // row edit class
var rowcolor = '#FFFFFF'; // row color
var rowaltcolor = '#F5F5F5'; // row alternate color
var rowmovercolor = '#FFCCFF'; // row mouse over color
var rowselectedcolor = '#CCFFFF'; // row selected color
var roweditcolor = '#FFFF99'; // row edit color

//-->
</script>
<?php
// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
?>
<p><span class="phpmaker">
CARTERA CAPITAL</span></p>
<p><span class="phpmaker"><br />
  <br />
  <?php if ($sExport == "") { ?>
  &nbsp;&nbsp;<a href="php_cartera_saldos_capital.php?export=excel&x_dias_ini=<?php echo $x_dias_ini; ?>&x_dias_fin=<?php echo $x_dias_fin; ?>">Exportar a Excel</a>
  <?php } ?>
</span></p>

<?php if ($sExport == "") { ?>
<form action="php_cartera_saldos_capital.php" name="filtro" id="filtro" method="post">
<table width="886" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="125"><span class="phpmaker">Tipo de Credito</span></td>
    <td width="11">&nbsp;</td>
    <td width="147"><span class="phpmaker">
      <?php
		$x_estado_civil_idList = "<select name=\"x_credito_tipo_id\" class=\"texto_normal\">";
		$sSqlWrk = "SELECT `credito_tipo_id`, `descripcion` FROM `credito_tipo`";	
		if($_SESSION["x_credito_tipo_id"] = 1000){
			$x_estado_civil_idList .= "<option value=\"1000\" selected>TODOS</option>";				
		}else{
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		}
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if($_SESSION["x_credito_tipo_id"] < 1000){
				if ($datawrk["credito_tipo_id"] == $_SESSION["x_credito_tipo_id"]) {
					$x_estado_civil_idList .= "' selected";
				}
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
    </span>
    
    
	</td>
    <td width="11">&nbsp;</td>
    <td width="128">&nbsp;</td>
    <td width="11">&nbsp;</td>
    <td width="147">&nbsp;</td>
    <td width="10">&nbsp;</td>
    <td width="116">&nbsp;</td>
    <td width="11">&nbsp;</td>
    <td width="169">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="phpmaker">Nombre</span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_nombre_srch" type="text" id="x_nombre_srch" value="<?php echo $_SESSION["x_nombre_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Apellido Paterno</span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_apepat_srch" type="text" id="x_apepat_srch" value="<?php echo $_SESSION["x_apepat_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Apellido Materno </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">&nbsp;&nbsp;
          <input name="x_apemat_srch" type="text" id="x_apemat_srch" value="<?php echo $_SESSION["x_apemat_srch"]; ?>" size="20" />
    </span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="phpmaker">Numero de Credito</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_crenum_srch" type="text" id="x_crenum_srch" value="<?php echo $_SESSION["x_crenum_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td class="phpmaker">Numero de Cliente </td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <input name="x_clinum_srch" type="text" id="x_clinum_srch" value="<?php echo $_SESSION["x_clinum_srch"]; ?>" size="20" />
    </span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="phpmaker">Sucursal</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
		$x_estado_civil_idList = "<select name=\"x_sucursal_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal join promotor on promotor.sucursal_id = sucursal.sucursal_id Where promotor.promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT sucursal_id, nombre FROM sucursal ";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["sucursal_id"] == $_SESSION["x_sucursal_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
      </span></td>
    <td>&nbsp;</td>
    <td>Fondo:</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
$x_medio_pago_idList = "<select  name=\"x_empresa_id\" onchange=\"cargalineas(this,'txtlineas',0)\">";
$x_medio_pago_idList .= "<option value=''>Seleccione</option>";
$sSqlWrk = "SELECT fondeo_empresa.fondeo_empresa_id, fondeo_empresa.nombre FROM fondeo_empresa order by fondeo_empresa.fondeo_empresa_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		
/*
		$sSqlWrk2 = "SELECT sum(importe) as otorgado FROM credito where credito_id in (select credito_id from fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id where fondeo_credito.fondeo_credito_id = ".$datawrk["fondeo_credito_id"].") and credito.credito_status_id in (1, 3,4,5)";
		$rswrk2 = phpmkr_query($sSqlWrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk2);
		$datawrk2 = phpmkr_fetch_array($rswrk2);
		$x_fondeo_saldo = $datawrk["importe"] - $datawrk2["otorgado"];
		@phpmkr_free_result($rswrk2);
*/		
		
		$x_medio_pago_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["fondeo_empresa_id"] == $_SESSION["x_empresa_id"]) {
			$x_medio_pago_idList .= "' selected";
		}
/*

		if($x_fondeo_saldo > 0){
			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . " Credito No.: " . $datawrk["credito_num"] . " Saldo: " . FormatNumber($x_fondeo_saldo,0,0,0,1) . "</option>";
		}else{
			if(strtoupper($datawrk["nombre"]) == "FONDOS PROPIOS"){
				$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
			}
		}

*/

		if(strtoupper($datawrk["nombre"]) == "FONDOS PROPIOS"){
			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		}else{
			$x_medio_pago_idList .= ">" . $datawrk["nombre"] . "</option>";
		}



		
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_medio_pago_idList .= "</select>";
echo $x_medio_pago_idList;
?>
    </span></td>
    <td>&nbsp;</td>
    <td colspan="3">
<div id="txtlineas" style=" float: left;">

</div>
    </td>
    </tr>
  <tr>
    <td class="phpmaker">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="phpmaker">Promotor</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
		$x_estado_civil_idList = "<select name=\"x_promo_srch\" class=\"texto_normal\">";
		if($_SESSION["php_project_esf_status_UserRolID"] == 7) {
			$sSqlWrk = "SELECT promotor_id, nombre_completo FROM promotor Where promotor_id = ".$_SESSION["php_project_esf_status_PromotorID"];
		}else{
			$sSqlWrk = "SELECT `promotor_id`, `nombre_completo` FROM `promotor`";	
			$x_estado_civil_idList .= "<option value=\"1000\">TODOS</option>";	
		}		
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["promotor_id"] == $_SESSION["x_promo_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["nombre_completo"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Dias Inicio:</span></td>
    <td>&nbsp;</td>
    <td><input name="x_dias_ini" type="text" id="x_dias_ini" onkeypress="return solonumeros(this,event)" value="<?php echo @$_SESSION["x_dias_ini"]; ?>" size="10" maxlength="10" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">Dias Fin:</span></td>
    <td>&nbsp;</td>
    <td><input name="x_dias_fin" type="text" id="x_dias_fin" onkeypress="return solonumeros(this,event)" value="<?php echo @$_SESSION["x_dias_fin"]; ?>" size="10" maxlength="10" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Status:</td>
    <td>&nbsp;</td>
    <td><span class="phpmaker">
      <?php
		$x_estado_civil_idList = "<select name=\"x_cresta_srch\" class=\"texto_normal\">";
		if ($_SESSION["x_credito_status_id_filtro"] == 0){
			$x_estado_civil_idList .= "<option value='100' selected>TODAS</option>";
		}else{
			$x_estado_civil_idList .= "<option value='100' >TODAS</option>";		
		}
		$sSqlWrk = "SELECT `credito_status_id`, `descripcion` FROM `credito_status`";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk) {
			$rowcntwrk = 0;
			while ($datawrk = phpmkr_fetch_array($rswrk)) {
				$x_estado_civil_idList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
				if ($datawrk["credito_status_id"] == $_SESSION["x_cresta_srch"]) {
					$x_estado_civil_idList .= "' selected";
				}
				$x_estado_civil_idList .= ">" . $datawrk["descripcion"] . "</option>";
				$rowcntwrk++;
			}
		}
		@phpmkr_free_result($rswrk);
		$x_estado_civil_idList .= "</select>";
		echo $x_estado_civil_idList;
		?>
    </span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="phpmaker">
      <input type="submit" name="Submit" value="Buscar &nbsp;(*)" />
    </span></td>
    <td>&nbsp;</td>
    <td><span class="phpmaker"><a href="php_cartera_saldos_capital.php?cmd=reset">Mostrar Todos</a>&nbsp;&nbsp; </span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<?php } ?>

<table id="ewlistmain" class="ewTable">
	<!-- Table header -->
	<tr class="ewTableHeader">
		<td valign="top"><span>        
		  Credito Num.
</span></td>
<td valign="top"><span>        
		  Cliente Num.
</span></td>
		<td valign="top">Status</td>		
		<td valign="top"><span>        
Fondo
</span></td>
		<td valign="top">Tipo credito</td>		

		<td valign="top"><span>
Promotor
		</span></td>
		<td valign="top"><span>
		  Cliente
		  </span></td>
          
         <td valign="top"><span>
		  Paterno
		  </span></td>
          
         <td valign="top"><span>
		 Materno
		  </span></td>  
		<td valign="top">Importe</td>
		<td valign="top">Tasa</td>
        <td valign="top">Periodicidad</td>
		<td valign="top">Fecha otorgamiento</td>
		<td valign="top">Fecha vencimiento</td>
		<td valign="top">Capital pagado</td>
		<td valign="top">Interes pagado</td>
		<td valign="top">Moratorio pagado</td>
		<td valign="top"><span>
		  Max. num de Dias vencido
		  </span></td>
		<td valign="top">Dias sin pagar</td>
		<td valign="top"><span>
  Capital
		Vencido</span></td>
		<td valign="top">Capital Deudor</td>
		</tr>
<?php
$x_tot_pagos_venc = 0;
$x_max_dias_venc = 0;
$x_ttotal_importe = 0;
$x_ttotal_importe_d = 0;
$x_ttotal_interes = 0;
$x_ttotal_moratorios = 0;
$x_ttotal_total = 0;

while ($row = @phpmkr_fetch_array($rs)){
	$nRecCount = $nRecCount + 1;
	$nRecActual++;

	// Set row color
	$sItemRowClass = " class=\"ewTableRow\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 1) {
		$sItemRowClass = " class=\"ewTableAltRow\"";
	}

	$x_credito_id = $row["credito_id"];
	$x_periodicidad= "";
	
	//peridicinada de pago 
	$sqlP = "SELECT credito.forma_pago_id,  forma_pago.descripcion FROM credito JOIN forma_pago ON forma_pago.forma_pago_id = credito.forma_pago_id WHERE credito_id = $x_credito_id";
	$rsP = phpmkr_query($sqlP, $conn) or die ("Error al selaccionar la forma de pago". phpmkr_error());
	$rowP = phpmkr_fetch_array($rsP);
	$x_periodicidad = $rowP["descripcion"];
	@phpmkr_free_result($rsP);

	$sSqlWrk = "select credito.fecha_otrogamiento, credito.fecha_vencimiento, credito.tasa, credito.credito_status_id, credito.importe, credito.credito_num, credito.credito_tipo_id, solicitud.solicitud_id from credito join solicitud on solicitud.solicitud_id = credito.solicitud_id where credito.credito_id  = $x_credito_id";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_credito_num = $rowwrk["credito_num"];	
	$x_credito_importe = $rowwrk["importe"];		
	$x_credito_tipo_id = $rowwrk["credito_tipo_id"];	
	$x_credito_status_id = $rowwrk["credito_status_id"];		
	$x_solicitud_id = $rowwrk["solicitud_id"];	
	$x_fecha_otrogamiento = $rowwrk["fecha_otrogamiento"];		
	$x_fecha_vencimiento = $rowwrk["fecha_vencimiento"];			
	$x_tasa = $rowwrk["tasa"];		
	@phpmkr_free_result($rswrk);
		
	$sSqlWrk = "SELECT *, TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento) as dias_venc FROM vencimiento where (credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 3) AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) >= ".$_SESSION["x_dias_ini"].") AND ((TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(vencimiento.fecha_vencimiento)) <= ".$_SESSION["x_dias_fin"].")	
order by vencimiento_num+0	
	";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	
	
	$x_total_importe = 0;
	$x_total_importe_d = 0;	
	$x_total_interes = 0;
	$x_total_moratorios = 0;
	$x_total_total = 0;
	$x_dias_venc_ant = 0;
	$x_dias_venc_ultimo_pago = 0;	
	$x_contador = 0;
	
	while($rowwrk = phpmkr_fetch_array($rswrk)) {
		$x_importe = $rowwrk["importe"];
		$x_interes = $rowwrk["interes"];
		$x_interes_moratorio = $rowwrk["interes_moratorio"];
		$x_dias_venc = $rowwrk["dias_venc"];		

		if($x_dias_venc > $x_dias_venc_ant){
			$x_dias_venc_ant = $x_dias_venc;
		}
		$x_total_importe = $x_total_importe + $x_importe;
		$x_total_interes = $x_total_interes + $x_interes;
		$x_total_moratorios = $x_total_moratorios + $x_interes_moratorio;
		$x_total_total = $x_total_total + ($x_importe + $x_interes + $x_interes_moratorio);
		$x_contador++;
	}
	@phpmkr_free_result($rswrk);
	
	//saldo deudor capital
	//determinar pagado capital
	$sSqlWrk = "select sum(importe) as pagado from vencimiento where credito_id = $x_credito_id and vencimiento_status_id = 2 ";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	$rowwrk = phpmkr_fetch_array($rswrk);
	$x_total_importe_pagado = $rowwrk["pagado"];	
	@phpmkr_free_result($rswrk);

	$x_total_importe_d = $x_credito_importe - $x_total_importe_pagado;
	
	
//dias desde su ultimo pago

	$sSqlWrk = "SELECT TO_DAYS('".ConvertDateToMysqlFormat($currdate)."') - TO_DAYS(recibo.fecha_pago) as dias_up FROM vencimiento join recibo_vencimiento on recibo_vencimiento.vencimiento_id = vencimiento.vencimiento_id join recibo on recibo.recibo_id = recibo_vencimiento.recibo_id where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2) order by recibo.fecha_pago desc limit 1";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_dias_ultimo_pago = $rowwrk["dias_up"];		
@phpmkr_free_result($rswrk);	



//capital pagado
	$sSqlWrk = "SELECT sum(importe) as cap_pag, sum(interes) as int_pag, sum(interes_moratorio) as mor_pag FROM vencimiento where (vencimiento.credito_id = $x_credito_id) AND (vencimiento.vencimiento_status_id = 2)";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
$rowwrk = phpmkr_fetch_array($rswrk);
$x_capital_pagado = $rowwrk["cap_pag"];		
$x_interes_pagado = $rowwrk["int_pag"];		
$x_mora_pagado = $rowwrk["mor_pag"];		
@phpmkr_free_result($rswrk);	



	
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- vencimiento_id -->
		<td align="right"><span>
  <?php echo $x_credito_num; ?>
</span></td>
<td align="left"><?php 
$sSqlWrk = "SELECT cliente.cliente_num as num_cli FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	echo $rowwrk["num_cli"];								
}
@phpmkr_free_result($rswrk);
?></td>
		<td align="left"><?php 

$sSqlWrk = "select descripcion from credito_status where credito_status_id = $x_credito_status_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	echo $rowwrk["descripcion"];								
}
@phpmkr_free_result($rswrk);
?></td>

		<td align="left"><span>

<?php 

$sSqlWrk = "select fondeo_empresa.nombre from  fondeo_colocacion join fondeo_credito on fondeo_credito.fondeo_credito_id = fondeo_colocacion.fondeo_credito_id join fondeo_empresa on fondeo_empresa.fondeo_empresa_id = fondeo_credito.fondeo_empresa_id where fondeo_colocacion.credito_id = $x_credito_id ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$x_fondo = $rowwrk["nombre"];								
}else{
	$x_fondo = "Propio";										
}
@phpmkr_free_result($rswrk);

echo $x_fondo; 
?>
		</span></td>
		<td align="left"><?php 
		$sSqlWrk = "SELECT descripcion FROM credito_tipo Where credito_tipo_id = $x_credito_tipo_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_promotor = $rowwrk["descripcion"];								
		}else{
			$x_promotor = "";										
		}
		@phpmkr_free_result($rswrk);

echo $x_promotor; 

?></td>

		<td align="left"><span>
<?php 
		$sSqlWrk = "SELECT promotor.nombre_completo FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join promotor on promotor.promotor_id = solicitud.promotor_id Where credito.credito_id = $x_credito_id ";
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_promotor = $rowwrk["nombre_completo"];								
		}else{
			$x_promotor = "";										
		}
		@phpmkr_free_result($rswrk);

echo $x_promotor; 

?>
</span></td>
		<td align="left"><span>
  <?php 
  $x_cliente = "";
  $x_paterno = "";
  $x_materno = "";
		if($x_credito_tipo_id == 1){
			$sSqlWrk = "SELECT cliente.nombre_completo as cliente_nombre, cliente.apellido_paterno, cliente.apellido_materno FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id join solicitud_cliente on solicitud_cliente.solicitud_id = solicitud.solicitud_id join cliente on cliente.cliente_id = solicitud_cliente.cliente_id Where credito.credito_id = $x_credito_id ";
		}else{
			$sSqlWrk = "SELECT solicitud.grupo_nombre as cliente_nombre FROM credito join solicitud on solicitud.solicitud_id = credito.solicitud_id Where credito.credito_id = $x_credito_id ";
		}

		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$x_cliente = $rowwrk["cliente_nombre"];
			$x_paterno = $rowwrk["apellido_paterno"];
			$x_materno = $rowwrk["apellido_materno"];								
		}else{
			$x_cliente = "";	
			@phpmkr_free_result($rswrk);
		}
		@phpmkr_free_result($rswrk);
		echo $x_cliente;
?>
</span></td>
<td align="left"><span>
  <?php 
		if($x_credito_tipo_id == 1){
			echo $x_paterno;
		}
?>
</span></td>
<td align="left"><span>
  <?php 
		if($x_credito_tipo_id == 1){
			echo $x_materno;
		}
?>
</span></td>
		<td align="right"><?php echo $x_credito_importe; ?></td>
		<td align="center"><?php echo $x_tasa; ?></td>
        <td align="center"><?php echo $x_periodicidad; ?></td>
		<td align="center"><?php echo FormatDateTime($x_fecha_otrogamiento,7); ?></td>
		<td align="center"><?php echo FormatDateTime($x_fecha_vencimiento,7); ?></td>
		<td align="right"><?php echo FormatNumber($x_capital_pagado,2,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($x_interes_pagado,2,0,0,1) ?></td>
		<td align="right"><?php echo FormatNumber($x_mora_pagado,2,0,0,1) ?></td>
		<td align="right"><span>
  <?php echo $x_dias_venc_ant; ?>
</span></td>
		<td align="right"><?php echo $x_dias_ultimo_pago; ?></td>
		<!-- importe -->
		<td align="right"><span>
<?php echo FormatNumber($x_total_importe,2,0,0,1) ?>
</span></td>
		<td align="right"><?php echo FormatNumber($x_total_importe_d,2,0,0,1) ?></td>
		<!-- interes -->
		<!-- interes_moratorio -->		</tr>
<?php

	$x_tot_pagos_venc = $x_tot_pagos_venc + $x_contador;
	if($x_max_dias_venc < $x_dias_venc_ant){
		$x_max_dias_venc = $x_dias_venc_ant;
	}
	$x_ttotal_importe = $x_ttotal_importe + $x_total_importe;	
 	$x_ttotal_importe_d = $x_ttotal_importe_d + $x_total_importe_d;	
	$x_ttotal_interes = $x_ttotal_interes + $x_total_interes;
	$x_ttotal_moratorios = $x_ttotal_moratorios + $x_total_moratorios;
	$x_ttotal_total = $x_ttotal_total + $x_total_total;
}
?>
<tr>
		<td valign="top" colspan="3"><span>
<b>Totales</b>
		</span></td>
		<td valign="top">&nbsp;</td>
		<td valign="top"><span>

		</span></td>		
		<td valign="top"><span>
		  
		  </span></td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>		
		<td align="right"><span>
  <?php echo FormatNumber($x_ttotal_importe,2,0,0,1) ?>
		  </span></td>
		<td align="right"><?php echo FormatNumber($x_ttotal_importe_sp,2,0,0,1) ?></td>
		<td align="right"><span>
<?php echo FormatNumber($x_ttotal_importe_d,2,0,0,1) ?>        
		</span></td>
		<td align="right"><?php echo FormatNumber($x_ttotal_interes,2,0,0,1) ?></td>
		</tr>
</table>
<?php
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
<?php

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";

			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";		
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";				

			

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["x_nombre_srch"] = "";
			$_SESSION["x_apepat_srch"] = "";
			$_SESSION["x_apemat_srch"] = "";
			$_SESSION["x_crenum_srch"] = "";
			$_SESSION["x_clinum_srch"] = "";
			$_SESSION["x_promo_srch"] = "";
			$_SESSION["x_empresa_id"] = "";		
			$_SESSION["x_fondeo_credito_id"] = "";			
			$_SESSION["x_credito_tipo_id"] = "";				

		// Reset Sort Criteria
		}

		// Reset Start Position (Reset Command)
	}
}

?>
