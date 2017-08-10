
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1 " name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    <link rel="stylesheet" href="<?php echo include_webroot;?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo include_webroot;?>plugins/datepicker/datepicker3.css">
    
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo include_webroot;?>plugins/timepicker/bootstrap-timepicker.min.css">
     <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo include_webroot;?>plugins/iCheck/all.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo include_webroot;?>plugins/select2/select2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo include_webroot;?>dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo include_webroot;?>dist/css/skins/_all-skins.css">
    <link rel="stylesheet" href="<?php echo include_webroot;?>/plugins/fullcalendar/fullcalendar.min.css" rel='stylesheet' >
    <link rel="stylesheet" href="<?php echo include_webroot;?>/plugins/fullcalendar/fullcalendar.print.css" media="print">  
    
    <!--<link rel="stylesheet" href="<?php echo include_webroot;?>dist/css/rateit.css">-->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
    .font-icon i{
        font-size:22px;
    }
    .dataTable{
        font-size:13px;
    }
    body.modal-open .datepicker {
        z-index: 1200 !important;
    }
    table.transaction-detail{
        font-size:13px;
    }
    table.transaction-detail tr th{
        background-color:#3c8dbc;
        color:white;
    }
    table.transaction-detail.table>tbody>tr>td{
        padding:2px;
    }
    .table-no-width{
        width:5%;
    }
    .text-align-right{
        text-align:right;
    }
    .control-label-text{
        padding-top: 7px;
        margin-bottom: 0;
        text-align: left;
        font-weight: 500;
    }
    label.error {
        margin: 5px 0px 0px 0px;
        color: red ;
        font-style: italic
    }
    input.error,textarea.error,select.error{
        border: 1px solid red !important;
    }
    
    /* Alert Boxes
=================================================================== */
.alert {
	font-family: Arial, sans-serif;
	font-size: 12px;
	line-height: 18px;
	margin-bottom: 15px;
	position: relative;
	padding: 14px 40px 14px 18px;
	-webkit-box-shadow:  0px 1px 1px 0px rgba(180, 180, 180, 0.1);
	box-shadow:  0px 1px 1px 0px rgba(180, 180, 180, 0.1);
	-webkit-border-radius: 0px;
	border-radius: 0px;
}

.alert.alert-success {
	background-color: #edf6e5 !important;
	color: #7a9659 !important;
	border: 1px solid #9fc76f !important;
}

.alert.alert-error {
	background-color: #fdeaea !important;
	color: #ca6f74 !important;
	border: 1px solid #f27b81 !important;
}

.alert {
	background-color: #fffee1 !important;
	color: #daac50 !important;
	border: 1px solid #f5c056 !important;
}

.alert.alert-info {
	background-color: #e9f8ff !important;
	color: #5d9fa9 !important;
	border: 1px solid #75c7d3 !important;
}
.panel-default{
    background-color: #fff;
    border-color: #fff;
    color: #fff;
    border: 1px solid #eee;
}





/*casper*/

div.tab {
    float: left;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    width: 15%;
    height: 500px;
    overflow: scroll;
}

/* Style the buttons inside the tab */
div.tab button {
    display: block;
    background-color: inherit;
    color: black;
    padding: 10px 20px;
    width: 100%;
    border: none;
    outline: none;
    text-align: left;
    cursor: pointer;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current "tab button" class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */




/* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    border: 1px solid #ccc;
    border-top: none;
}

/* Style the close button */
.topright {
    float: right;
    cursor: pointer;
    font-size: 20px;
}

.topright:hover {color: red;}

.tabcontent {
    float: left;
    border: 1px solid #ccc;
    width: 85%;
    border-left: none;
    height: 500px;
}

.content_left {
    float: left;
    padding: 20px 12px;
    border-right: 1px solid #ccc;
    width: 40%;
    height: 500px;
}

.tabcontent div div{
    width:70%;
    margin:6px;
}

.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
    z-index: 2;
    color: #fff;
    cursor: default;
    background-color: #00a65a;
    border-color: #00a65a;
}


.content_right {
    float: right;
    width: 60%;
    height: 500px;
}

.modal-body a{
    color: red;
}

.modal-body{
    height: 400px;
    overflow-y: scroll;
    border-color: black;
    border: 2px;
    border-style: solid
}

.dropdown-menu > li > a {
    color: #fff;
}

.btn-client{
    width:26px; 
    height:26px;
    border-radius: 20px;
    padding: 0px;
}

.table-cursor tbody tr{
    cursor: pointer
}

/*.col-sm-12{
    height: 400px;
    overflow-y: scroll;
}*/

#aRemarks_content table a{
    color:red;
}
#pRemarks_content table a{
    color:red;
}
#client_applicant_content table a{
    color:red;
}
/* Popup container - can be anything you want */
.popup {
    position: relative;
    display: inline-block;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}


/* The actual popup */
.popup .popuptext {
    visibility: hidden;
    width: 160px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 8px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -80px;
}

.box.box-primary{
        padding: 5px;
}

/* Popup arrow */
.popup .popuptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
    visibility: visible;
    -webkit-animation: fadeIn 1s;
    animation: fadeIn 1s;
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
    from {opacity: 0;} 
    to {opacity: 1;}
}

@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity:1 ;}
}

.user_content-left{
    width:70%;
    float:left;
}
.user_content-right{
    width:29%;
    float:right;
}

.file-preview{
    height:500px;
}
.form-horizontal .form-group{
    margin-right:0px!important;
    margin-left:0px!important;
}
.file-drop-zone{
    height: 96%!important;
}
.navbar-nav > .notifications-menu > .dropdown-menu > li .menu, .navbar-nav > .messages-menu > .dropdown-menu > li .menu, .navbar-nav > .tasks-menu > .dropdown-menu > li .menu{
    max-height: 300px;
}

.fc-day-number{
        color: #000;
}

.fc-day {
    /*background-color: #797979;*/
    background-color: #fff;
    /*background-color: #03884b;*/
}

.fc-unthemed .fc-today {
    /*background: #227982;*/
    background-color: #539a84;
}

.fc-ltr .fc-basic-view .fc-day-number {
    text-align: left;
}

.fc-time{
   display : none;
}
</style>