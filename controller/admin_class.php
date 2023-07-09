<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';
		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}

	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		// if request body password is Empty => not change pass
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = '$type' ";

		$check = $this->db->query("Select * from users where username = '$username' and id !='$id' ");
		if ($check && $check->num_rows > 0) {
			return 2;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}

	function get_course()
	{
		$data = array();
		$course = $this->db->query("SELECT * FROM courses order by id asc");
		while ($row = $course->fetch_assoc()) {
			$data['data'][] = $row;
		}
		return json_encode($data);
	}

	function save_course()
	{
		extract($_POST);
		$data = " course = '$course' ";
		$data .= ", description = '$description' ";
		$check = $this->db->query("SELECT * FROM courses where course = '$course' " . (!empty($id) ? ' and id!=$id ' : ''));
		if ($check) {
			if ($check->num_rows > 0) {
				return 2;
			}
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO courses set $data");
		} else {
			$save = $this->db->query("UPDATE courses set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_course()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM courses where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	function get_subject()
	{
		$data = array();
		$course = $this->db->query("SELECT * FROM subjects order by id asc");
		while ($row = $course->fetch_assoc()) {
			$data['data'][] = $row;
		}
		return json_encode($data);
	}


	function save_subject()
	{
		extract($_POST);
		$data = " subject = '$subject' ";
		$data .= ", time_subject = '$time_subject' ";
		$data .= ", description = '$description' ";
		$check = $this->db->query("SELECT * FROM subjects where subject = '$subject' " . (!empty($id) ? ' and id!=$id ' : ''));
		if ($check && $check->num_rows > 0) {
			return 2;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO subjects set $data");
		} else {
			$save = $this->db->query("UPDATE subjects set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_subject()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM subjects where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function get_class()
	{
		$data = array();
		$sql = $sql = "SELECT \n"
			. "class.id,\n"
			. "class.course_id,\n"
			. "courses.course as course_name,\n"
			. "class.level as class,\n"
			. "class.section as subclass,\n"
			. "    CONCAT(\n"
			. "        `courses`.`course`,\n"
			. "        \" \",\n"
			. "        `class`.`level`,\n"
			. "        \"-\",\n"
			. "        `class`.`section`\n"
			. "    ) AS class_name,\n"
			. "class.status \n"
			. "FROM `class` \n"
			. "JOIN `courses` on class.course_id = courses.id;";

		$class = $this->db->query($sql);
		while ($row = $class->fetch_assoc()) {
			$data['data'][] = $row;
		}
		return json_encode($data);
	}

	function save_class()
	{
		extract($_POST);
		if (empty($course_id)) {
			return 3;
		}
		$statusParse = isset($status) ? 1 : 0;

		$data = " course_id = '$course_id' ";
		$data .= ", level = '$class' ";
		$data .= ", section = '$subclass' ";
		$data .= ", status = '$statusParse' ";

		$data2 = " course_id = '$course_id' ";
		$data2 .= "and level = '$class' ";
		$data2 .= "and section = '$subclass' ";

		$check = $this->db->query("SELECT * FROM class where $data2 " . (!empty($id) ? ' and id!=$id ' : ''));
		if ($check && $check->num_rows > 0) {
			return 2;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO class set $data");
		} else {
			$save = $this->db->query("UPDATE class set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_class()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE class set status = 0 where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	function get_faculty()
	{
		$data = array();
		$class = $this->db->query("SELECT * FROM `faculty`");
		while ($row = $class->fetch_assoc()) {
			$data['data'][] = $row;
		}
		return json_encode($data);
	}

	function save_faculty()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'ref_code')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM faculty where id_no ='$id_no' " . (!empty($id) ? " and id != {$id} " : ''));
		if ($check && $check->num_rows > 0) {
			return 2;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO faculty set $data");
			$nid = $this->db->insert_id;
		} else {
			$save = $this->db->query("UPDATE faculty set $data where id = $id");
		}
		// Add new user by faculty
		// Password default = id_no
		if ($save) {
			$user = " name = '$name' ";
			$user .= ", username = '$email' ";
			$user .= ", password = '" . (md5($id_no)) . "' ";
			$user .= ", type = 2 ";
			if (empty($id)) {
				$user .= ", faculty_id = $nid ";
				$save = $this->db->query("INSERT INTO users set $user");
			} else {
				$save = $this->db->query("UPDATE users set $user where faculty_id = $id");
			}
			return 1;
		}
	}
	function delete_faculty()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM faculty where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function get_student()
	{
		$data = array();
		$sql = "SELECT\n"
			. "    `students`.*,\n"
			. "    CONCAT(\n"
			. "        `courses`.`course`,\n"
			. "        \" \",\n"
			. "        `class`.`level`,\n"
			. "        \"-\",\n"
			. "        `class`.`section`\n"
			. "    ) AS class_name\n"
			. "FROM\n"
			. "    `students`\n"
			. "JOIN `class` ON `students`.`class_id` = `class`.`id`\n"
			. "JOIN `courses` ON `class`.`course_id` = `courses`.`id`;";

		$students = $this->db->query($sql);
		while ($row = $students->fetch_assoc()) {
			$data['data'][] = $row;
		}
		return json_encode($data);
	}

	function save_student()
	{
		extract($_POST);
		if (empty($class_id)) {
			return 3;
		}
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM students where id_no ='$id_no' " . (!empty($id) ? " and id != {$id} " : ''));
		if ($check && $check->num_rows > 0) {
			return 2;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO students set $data");
		} else {
			$save = $this->db->query("UPDATE students set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_student()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM students where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	function get_class_subject()
	{
		$email = $_GET['email'];
		$typeAccount = $_GET['typeAccount'];
		$data = array();

		$students = $this->db->query("CALL get_Dashboard_BarChart($email,$typeAccount);");
		while ($row = $students->fetch_assoc()) {
			$data['data'][] = $row;
		}
		return json_encode($data);
	}

	function get_user()
	{
		$data = array();
		$class = $this->db->query("SELECT * FROM `users`");
		while ($row = $class->fetch_assoc()) {
			$data['data'][] = $row;
		}
		return json_encode($data);
	}

	function save_class_subject()
	{
		extract($_POST);
		if (empty($class_id)) {
			return 3;
		}
		if (empty($subject_id)) {
			return 4;
		}
		if (empty($faculty_id)) {
			return 5;
		}
		$data = "";
		$data2 = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if (empty($data)) {
					if ($k == 'status') {
						$statusParse = $v == 'on' ? 1 : 0;
						$data .= ", status_cs=$statusParse ";
						$data2 .= "and status_cs=$statusParse ";
						continue;
					}
					$data .= " $k='$v' ";
					$data2 .= " $k='$v' ";
				} else {
					if ($k == 'status') {
						$statusParse = $v == 'on' ? 1 : 0;
						$data .= ", status_cs=$statusParse ";
						$data2 .= "and status_cs=$statusParse ";
						continue;
					}
					$data .= ", $k='$v' ";
					$data2 .= "and $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM class_subject where $data2 " . (!empty($id) ? " and id != {$id} " : ''));
		if ($check && $check->num_rows > 0) {
			return 2;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO class_subject set $data");
		} else {
			$save = $this->db->query("UPDATE class_subject set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_class_subject()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM class_subject where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function get_class_list()
	{
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id where cs.id = '$class_subject_id' ");
		if (isset($att_id)) {
			$record = $this->db->query("SELECT * FROM attendance_record where attendance_id='$att_id' ");
			if ($record->num_rows > 0) {
				while ($row = $record->fetch_assoc()) {
					$data['record'][] = $row;
					$data['attendance_id'] = $row['attendance_id'];
				}
			}
		}
		while ($row = $get->fetch_assoc()) {
			$data['data'][] = $row;
		}

		$get_time = $this->db->query("SELECT time_remaining FROM class_subject WHERE id = '$class_subject_id' LIMIT 1;");
		while ($row = $get_time->fetch_assoc()) {
			$data['time_remaining'] = $row['time_remaining'];
		}

		return json_encode($data);
	}

	function get_edit_class_list()
	{
		extract($_POST);
		$RESULT = new stdClass();
		$QUERY = "SELECT * FROM `attendance_list` \n"
			. "WHERE `doc` = '$date_attendance' AND `class_subject_id` = '$class_subject_id' LIMIT 1;";
		$LIST = $this->db->query($QUERY);

		if ($LIST && $LIST->num_rows > 0) {
			while ($ROW = $LIST->fetch_array()) {
				$ATTENDANCE_ID = $ROW['id'];
				$RESULT->attendance_id = $ROW['id'];
				$RESULT->note = $ROW['note'];
				$RESULT->startTime = $ROW['start_time'];
				$RESULT->endTime = $ROW['end_time'];
			}
		}

		if (empty($ATTENDANCE_ID)) {
			$RESULT->success = false;
			return json_encode($RESULT);
		}

		$LIST_TYPE = array();
		$QUERY_RECORD = "SELECT * FROM `attendance_record` WHERE `attendance_id` = '$ATTENDANCE_ID';";
		$LIST_RECORD = $this->db->query($QUERY_RECORD);
		if ($LIST_RECORD && $LIST_RECORD->num_rows > 0) {
			while ($ROW = $LIST_RECORD->fetch_array()) {
				$ROW_DATA = new stdClass();
				$ROW_DATA->type = $ROW['type'];
				$ROW_DATA->student_id = $ROW['student_id'];
				array_push($LIST_TYPE, $ROW_DATA);
			}
		}
		$RESULT->listType = $LIST_TYPE;
		$RESULT->success = true;

		return json_encode($RESULT);
	}

	function get_att_record()
	{
		extract($_POST);
		$get = $this->db->query("SELECT s.* FROM students s inner join `class` c on c.id = s.class_id inner join class_subject cs on cs.class_id = c.id where cs.id = '$class_subject_id' ");
		$record = $this->db->query("SELECT ar.*,a.class_subject_id FROM attendance_record ar inner join attendance_list a on a.id =ar.attendance_id where a.class_subject_id='$class_subject_id' and a.doc = '$doc' ");
		$data = array();
		while ($row = $get->fetch_assoc()) {
			$data['data'][] = $row;
		}
		if ($record->num_rows > 0) {
			while ($row = $record->fetch_assoc()) {
				$data['record'][] = $row;
				$data['attendance_id'] = $row['attendance_id'];
			}
		}

		$qry = $this->db->query("SELECT s.subject,co.course,concat(c.level,'-',c.section) as `class` FROM class_subject cs inner join class c on c.id = cs.class_id inner join subjects s on s.id = cs.subject_id inner join courses co on co.id = c.id where cs.id = {$class_subject_id} ");
		$fetch_array = $qry->fetch_array();
		if (!empty($fetch_array)) {
			foreach ($fetch_array as $k => $v) {
				$data['details'][$k] = $v;
			}
		}
		$data['details']['doc'] = date('M d, Y', strtotime($doc));

		return json_encode($data);
	}
	function get_att_report()
	{
		extract($_GET);
		$RESULT = array();
		$QUERY = $this->db->query("CALL get_ReportAttendance($month,$year,$subject_class_id);");
		while ($ROW = $QUERY->fetch_assoc()) {
			$RESULT['data'][] = $ROW;
		}
		return json_encode($RESULT);
	}
	function save_attendance()
	{
		extract(json_decode($_POST['json'], TRUE));

		$QUERY_CHECK = "SELECT `id` FROM `attendance_list` WHERE `doc` = '$doc' AND `class_subject_id` = '$class_subject_id' ";
		$QUERY_CHECK .= (isset($id) && $id != 0 ? "AND `id` = '$id' " : "");
		$CHECK = $this->db->query($QUERY_CHECK);

		// Nếu trong bảng attendance_list đã có bản ghi (> 0)
		/*
			Chỉ cập nhật lại type trong bảng attendance_record 
		*/
		if ($CHECK && $CHECK->num_rows > 0) {
			$attendance_list = $CHECK->fetch_array();
			$SUCCESS = $this->db->query("UPDATE `attendance_list` SET `note` = '$note' WHERE `id` = '$attendance_list[id]' ");

			foreach ($student_id as $key => $value) {
				$QUERY = "UPDATE attendance_record SET `type` = '$type[$key]' WHERE ";
				$QUERY .= " attendance_id = '$attendance_list[id]' ";
				$QUERY .= "and student_id = '$value' ";
				$this->db->query($QUERY);
			}
			return 1; //UPDATE
		} else {
			// Update Time Remaining
			$QUERY_TIME = "UPDATE `class_subject` SET"
				. " `time_remaining` = `time_remaining` - 1\n"
				. " WHERE class_subject.id = '$class_subject_id';";
			$this->db->query($QUERY_TIME);
		}
		// Nếu trong bảng attendance_list chưa có bản ghi (< 0)
		/*
			Thêm mới 1 bản ghi vào bảng attendance_list
			Thêm mới điểm danh vào bảng attendance_record theo id của attendance_list vừa thêm mới
		*/
		$QUERY_SAVE  = " class_subject_id = '$class_subject_id' ";
		$QUERY_SAVE .= ", doc = '$doc' ";
		$QUERY_SAVE .= ", start_time = '$start_time' ";
		$QUERY_SAVE .= ", note = '$note' ";

		$SUCCESS = $this->db->query("INSERT INTO `attendance_list` SET $QUERY_SAVE");
		if ($SUCCESS) {
			$attendance_list_id = $this->db->insert_id;
			foreach ($student_id as $key => $value) {
				$QUERY = "INSERT INTO `attendance_record` SET";
				$QUERY .= " attendance_id = '$attendance_list_id' ";
				$QUERY .= ", student_id = '$value' ";
				$QUERY .= ", type = '$type[$key]' ";
				$this->db->query($QUERY);
			}
			return 2; //INSERT
		}
	}

	function import_excel()
	{
		extract($_POST);
		if ($json) {
			$items = json_decode($json, TRUE);
			$thisDate = date("Y-m-d");
			$VALUES = '';
			$countSuccess = 0;
			$countVaild = 0;
			// $listValid = [];
			foreach ($items as $item) {
				$checkItem = $this->db->query("SELECT * FROM `students` WHERE id_no = '$item[id_no]'");
				if ($checkItem->num_rows > 0) {
					$countVaild += 1;
					// while ($row = $checkItem->fetch_assoc()) {
					// 	array_push($listValid, $row);
					// }
					continue;
				}
				$countSuccess += 1;
				$VALUES .= "('$item[id_no]',";
				$VALUES .= "'$item[class_id]',";
				$VALUES .= "'$item[name]',";
				$VALUES .= "'$thisDate'),";
			}
			$VALUES = substr_replace($VALUES, "", -1);
			$QUERY  = "INSERT INTO `students`(`id_no`, `class_id`, `name`, `date_created`) VALUES $VALUES";
			$this->db->query($QUERY);
		}
		$object = new stdClass();
		$object->countSuccess = $countSuccess;
		$object->countVaild = $countVaild;
		// $object->listValid = $listValid;
		return json_encode($object);
	}

	function get_time_remaining_subject()
	{
		extract($_POST);
		$RESULT = new stdClass();
		$QUERY = "SELECT `time_subject` FROM  `subjects` WHERE `id` = '$id' LIMIT 1";
		$RAWDATA = $this->db->query($QUERY);
		if ($RAWDATA) {
			while ($ROW = $RAWDATA->fetch_assoc()) {
				$RESULT = $ROW;
			}
		}
		return json_encode($RESULT);
	}

	function end_subject()
	{
		try {
			extract(json_decode($_POST['json'], TRUE));

			// Update End Time 
			$QUERY_ENDTIME = "UPDATE `attendance_list` SET `end_time`='$endTime' WHERE `id` = '$attendance_id';";
			$this->db->query($QUERY_ENDTIME);

			// // Update Time Remaining
			// $QUERY_TIME = "UPDATE `class_subject` SET"
			// 	. " `time_remaining` = `time_remaining` - '$timeRemaining'\n"
			// 	. " WHERE class_subject.id = '$class_subject_id';";
			// $this->db->query($QUERY_TIME);
			return 1;
		} catch (\Throwable $th) {
			return 3;
		}
	}

	function get_Dashboard_BarChart()
	{
		// extract($_POST);
		$RESULT = array();

		//Bar Chart
		$QUER = $this->db->query("CALL get_Dashboard_BarChart();");
		while ($ROW = $QUER->fetch_assoc()) {
			$RESULT['data'][] = $ROW;
		}

		return json_encode($RESULT);
	}

	function get_Dashboard_PieChart()
	{
		// extract($_POST);
		$RESULT = array();

		//Pie Chart
		$QUERY = $this->db->query("CALL get_Dashboard_PieChart();");
		while ($ROW = $QUERY->fetch_assoc()) {
			$RESULT['data'][] = $ROW;
		}

		return json_encode($RESULT);
	}
}
