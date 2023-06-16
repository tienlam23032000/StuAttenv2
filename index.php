<?php
session_start();
ob_start();
include './view/layout/Header.php';
if (!isset($_SESSION['login_id'])) {
    include './view/auth/Login.php';
    return;
}
include './view/auth/Rule.php';
if (!isset($_GET['page'])) {
    $arrayRule = $_SESSION['login_type'] == 1 ? $adminRule : $staffRule;
    header("Refresh:0; url=index.php?page=" . array_key_first($arrayRule));
    return;
}

//Global Variable 
$global = new stdClass();
$global->nameUser = $_SESSION['login_name'];
$global->typeUser = $_SESSION['login_type'];
$global->arrayRule = $global->typeUser == 1 ? $adminRule : $staffRule;
$global->thisPage = $_GET['page'];

$variable = new stdClass();
$variable->admin = 1;
$variable->staff = 2;

if (!array_key_exists($global->thisPage, $global->arrayRule)) {
    include './view/layout/404.php';
    return;
}

echo '<div aria-live="polite" aria-atomic="true" style="position: relative;">';
include './view/layout/SideBar.php';
echo "<main id='main' class='main'>";

switch ($global->thisPage) {
    case "dashboard":
        include './view/components/Dashboard.php';
        break;
    case "courses":
        include './view/components/Course.php';
        break;
    case "subjects":
        include './view/components/Subjects.php';
        break;
    case "class":
        include './view/components/Class.php';
        break;
    case "faculty":
        include './view/components/Faculty.php';
        break;
    case "students":
        include './view/components/Students.php';
        break;
    case "classSubjects":
        include './view/components/Class_Subjects.php';
        break;
    case "users":
        include './view/components/Users.php';
        break;
    case "attendanceList":
        include './view/components/Attendance_List.php';
        break;
    case "attendanceRecord":
        include './view/components/Attendance_Record.php';
        break;
    case "attendanceReport":
        include './view/components/Attendance_Report.php';
        break;
}
echo '</main>';
include './view/layout/Footer.php';
echo '</div>';
ob_end_flush();
?>

<?php include './view/layout/EndPage.php'; ?>