<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if ($action == 'login') {
	$login = $crud->login();
	if ($login)
		echo $login;
}

if ($action == 'logout') {
	$crud->logout();
}

if ($action == 'save_user') {
	$save = $crud->save_user();
	if ($save)
		echo $save;
}
if ($action == 'delete_user') {
	$save = $crud->delete_user();
	if ($save)
		echo $save;
}
if ($action == "save_course") {
	$save = $crud->save_course();
	if ($save)
		echo $save;
}

if ($action == "delete_course") {
	$delete = $crud->delete_course();
	if ($delete)
		echo $delete;
}
if ($action == "save_subject") {
	$save = $crud->save_subject();
	if ($save)
		echo $save;
}
if ($action == "delete_subject") {
	$save = $crud->delete_subject();
	if ($save)
		echo $save;
}

if ($action == "save_class") {
	$save = $crud->save_class();
	if ($save)
		echo $save;
}
if ($action == "delete_class") {
	$save = $crud->delete_class();
	if ($save)
		echo $save;
}
if ($action == "save_faculty") {
	$save = $crud->save_faculty();
	if ($save)
		echo $save;
}
if ($action == "delete_faculty") {
	$save = $crud->delete_faculty();
	if ($save)
		echo $save;
}

if ($action == "save_student") {
	$save = $crud->save_student();
	if ($save)
		echo $save;
}
if ($action == "delete_student") {
	$save = $crud->delete_student();
	if ($save)
		echo $save;
}
if ($action == "save_class_subject") {
	$save = $crud->save_class_subject();
	if ($save)
		echo $save;
}
if ($action == "delete_class_subject") {
	$save = $crud->delete_class_subject();
	if ($save)
		echo $save;
}
if ($action == "get_class_list") {
	$get = $crud->get_class_list();
	if ($get)
		echo $get;
}
if ($action == "get_att_record") {
	$get = $crud->get_att_record();
	if ($get)
		echo $get;
}
if ($action == "get_att_report") {
	$get = $crud->get_att_report();
	if ($get)
		echo $get;
}
if ($action == "save_attendance") {
	$save = $crud->save_attendance();
	if ($save)
		echo $save;
}
if ($action == "import_excel") {
	$save = $crud->import_excel();
	if ($save)
		echo $save;
}
if ($action == "get_course") {
	$save = $crud->get_course();
	if ($save)
		echo $save;
}
if ($action == "get_subject") {
	$save = $crud->get_subject();
	if ($save)
		echo $save;
}
if ($action == "get_class") {
	$save = $crud->get_class();
	if ($save)
		echo $save;
}
if ($action == "get_faculty") {
	$save = $crud->get_faculty();
	if ($save)
		echo $save;
}
if ($action == "get_student") {
	$save = $crud->get_student();
	if ($save)
		echo $save;
}

if ($action == "get_class_subject") {
	$get = $crud->get_class_subject();
	if ($get)
		echo $get;
}

if ($action == "get_user") {
	$save = $crud->get_user();
	if ($save)
		echo $save;
}

if ($action == "get_edit_class_list") {
	$save = $crud->get_edit_class_list();
	if ($save)
		echo $save;
}

if ($action == "get_time_remaining_subject") {
	$save = $crud->get_time_remaining_subject();
	if ($save)
		echo $save;
}

if ($action == "end_subject") {
	$save = $crud->end_subject();
	if ($save)
		echo $save;
}

if ($action == "get_Dashboard_BarChart") {
	$get = $crud->get_Dashboard_BarChart();
	if ($get) 
		echo $get;
}

if ($action == "get_Dashboard_PieChart") {
	$get = $crud->get_Dashboard_PieChart();
	if ($get) 
		echo $get;
}

if ($action == "get_Details_Record") {
	$get = $crud->get_Details_Record();
	if ($get) 
		echo $get;
}
ob_end_flush();
