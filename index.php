<?php
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";
$connect = connectToDB();
menu(); // show menu everywhere
$op = $_REQUEST['op'] ?? '';

switch ($op) {
	case 'info':
		updateForm($_REQUEST['sid']);
		break;
	case 'update':
		updateStudent($_REQUEST['sid'], $_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['did'], $_REQUEST['birthdate'], $_REQUEST['email']);
		updateForm($_REQUEST['sid']);
		break;
	case 'updateTeacherForm':
		updateTeacherForm($_REQUEST['tid']);
		break;
	case 'updateTeacher':
		updateTeacher($_REQUEST['tid'], $_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['birthdate'], $_REQUEST['birthplace'], $_REQUEST['did']);
		updateTeacherForm($_REQUEST['tid']);
		break;
	case 'studentSchedule':
		scheduleStudent($_GET['sid'], "SELECT t.sid, t.cid, c.title, r.rid, r.description, teacher.*, s.dayOfWeek, s.hourOfDay
            FROM take t
            JOIN schedule s ON t.cid = s.cid
            JOIN course c ON t.cid = c.cid
            JOIN teach ON t.cid = teach.cid
            JOIN teacher ON teach.tid = teacher.tid
            JOIN room r ON s.rid = r.rid
            WHERE t.sid = ?");
		break;
	case 'courseSchedule':
		if (isset($_POST['hour']) && isset($_POST['day'])) {
			echo "POST RECEIVED";
			$hour = $_POST['hour'];
			$day = $_POST['day'];
			$cid = $_REQUEST['cid'];
		}
		scheduleStudent($_GET['cid'], "SELECT t.sid, t.cid, c.title, r.rid, r.description, teacher.*, s.dayOfWeek, s.hourOfDay
            FROM take t
            JOIN schedule s ON t.cid = s.cid
            JOIN course c ON t.cid = c.cid
            JOIN teach ON t.cid = teach.cid
            JOIN teacher ON teach.tid = teacher.tid
            JOIN room r ON s.rid = r.rid
            WHERE t.cid = ?");
		break;
	case 'teacherSchedule':
		scheduleStudent($_GET['tid'], "SELECT t.sid, t.cid, c.title, r.rid, r.description, teacher.*, s.dayOfWeek, s.hourOfDay
            FROM take t
            JOIN schedule s ON t.cid = s.cid
            JOIN course c ON t.cid = c.cid
            JOIN teach ON t.cid = teach.cid
            JOIN teacher ON teach.tid = teacher.tid
            JOIN room r ON s.rid = r.rid
            WHERE teacher.tid = ?");
		break;
	case 'roomSchedule':
		scheduleStudent($_GET['rid'], "SELECT t.sid, t.cid, c.title, r.rid, r.description, teacher.*, s.dayOfWeek, s.hourOfDay
            FROM take t
            JOIN schedule s ON t.cid = s.cid
            JOIN course c ON t.cid = c.cid
            JOIN teach ON t.cid = teach.cid
            JOIN teacher ON teach.tid = teacher.tid
            JOIN room r ON s.rid = r.rid
            WHERE r.rid = ?");
		break;
	case 'deleteCourse':
		deleteCourse($_GET['sid'], $_GET['cid']);
		chosenCourses($_GET['sid']);
		break;
	case 'displayCourseList': // New case added
		displayCourseList($connect);
		break;
	case 'teacherList':
		teacherList($connect);
		break;
	case 'takeCourse':
		$studentsDid = $_REQUEST['did'] ?? getStudentDid($_REQUEST['sid']);
		takeCourse($_GET['sid'], $_GET['cid']);
		selection($studentsDid, $_REQUEST['sid']);
		courseList($studentsDid, $_REQUEST['sid']);
		break;
	case 'chosenCourses':
		chosenCourses($_GET['sid']);
		break;
	case 'deleteStudent':
		$sid = $_GET['sid'] ?? 0;
		if ($sid) {
			deleteStudent($sid);
		} else {
			echo "No student ID provided.";
		}
		studentList($_REQUEST['sid'] ?? '', $_GET['col'] ?? 'sid', $_GET['direct'] ?? 'ASC', $_GET['pageno'] ?? '1');
		break;
	case 'deleteTeacher':
		$tid = $_GET['tid'] ?? 0;
		if ($tid) {
			deleteTeacher($tid);
		}
		teacherList($connect);
		break;
	case 'addStudent':
		// Form gönderildiğinde bu kısım çalışacak.
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			addStudentToDb(
				$_POST['fname'],
				$_POST['lname'],
				$_POST['birthdate'],
				$_POST['birthplace'],
				$_POST['did'],
				$_POST['avatarId'] ?? 0
			);
		} else {
			// GET isteklerinde form gösterilecek.
			addStudentForm();
		}
		break;
	case 'addTeacher':
		// Form gönderildiğinde bu kısım çalışacak.
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			addTeacherToDb(
				$_POST['fname'],
				$_POST['lname'],
				$_POST['birthdate'],
				$_POST['birthplace'],
				$_POST['did']
			);
		} else {
			// GET isteklerinde form gösterilecek.
			addTeacherForm();
		}
		break;


	case 'addCourse':
		// Form gönderildiğinde bu kısım çalışacak.
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			addCourseToDb(
				$_POST['title'],
				$_POST['description'],
				$_POST['credits'],
				$_POST['did'],
				$_POST['tid']

			);
		} else {
			// GET isteklerinde form gösterilecek.
			addCourseForm();
		}
		break;
	case 'addDepartment':
		// Form gönderildiğinde bu kısım çalışacak.
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			addDepartmentToDb(
				$_POST['dname'],
				$_POST['comments'],
				$_POST['email']
			);
		} else {
			// GET isteklerinde form gösterilecek.
			addDepartmentForm();
		}
		break;


	case 'departmentList':
		departmentList($connect);
		break;
	case 'chooseCourse':
		if (!isset($_REQUEST['sid'])) {
			echo 'Please first choose a student from the list below<br>';
			studentList($_REQUEST['sid'] ?? '', $_GET['col'] ?? 'sid', $_GET['direct'] ?? 'ASC', $_GET['pageno'] ?? '1');
		} else {
			$studentsDid = $_REQUEST['did'] ?? getStudentDid($_REQUEST['sid']);
			// display department select box
			selection($studentsDid, $_REQUEST['sid']);
			// display courses in the chosen department
			courseList($studentsDid, $_REQUEST['sid']);
		}
		break;
	case 'chooseStudent':
		if (!isset($_REQUEST['sid'])) {
			echo 'Please first choose a student from the list below<br>';
			studentList($_REQUEST['sid'] ?? '', $_GET['col'] ?? 'sid', $_GET['direct'] ?? 'ASC', $_GET['pageno'] ?? '1');
		} else {

			studentList($_REQUEST['sid'], $_GET['col'] ?? 'sid', $_GET['direct'] ?? 'ASC', $_GET['pageno'] ?? '1');
		}
		break;

	default:
		studentList($_REQUEST['sid'] ?? '', $_GET['col'] ?? 'sid', $_GET['direct'] ?? 'ASC', $_GET['pageno'] ?? '1');
}

function connectToDB()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "uni";

	$connect = new mysqli($servername, $username, $password, $dbname);
	if ($connect->connect_error) {
		die("Connection failed: " . $connect->connect_error);
	}
	return $connect;
}

function menu()
{
	global $connect, $op;
	$sid = $_REQUEST['sid'] ?? '';
	$op = $_REQUEST['op'] ?? '';
	echo "
    <h4>Main Menu</h4>
    <a href='?op=list&sid=$sid'>Student list</a><br>
    <a href='?op=displayCourseList&sid=$sid'>Course List</a><br>
	<a href='?op=teacherList'>Teacher List</a><br>
    <a href='?op=departmentList'>Department List</a><br><br>";

	if ($sid) {
		// Query to join student and take tables to get the grade
		$stmt = $connect->prepare("
            SELECT student.sid, student.fname, student.lname, take.grade ,d.dname
            FROM student
            LEFT JOIN take ON student.sid = take.sid 
			LEFT JOIN department d ON student.did = d.did
            WHERE student.sid = ? 
            LIMIT 1
        ");
		$stmt->bind_param('i', $sid);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			while ($stdRow = $result->fetch_assoc()) {
				if ($op == 'studentSchedule' || $op == 'chooseCourse' || $op == 'chosenCourses') {
					$grade = $stdRow['grade'] ?? 'N/A'; // Default to 'N/A' if grade is not set
					echo "<span style='color:green;'>Sid: {$stdRow['sid']} Name: {$stdRow['fname']} {$stdRow['lname']} DPT: {$stdRow['dname']} GPA: {$grade}</span><br>";
				}
			}
		} else {
			echo "No student data found.";
		}
	}
}


function selection($studentsDid, $sid)
{
	global $connect;
	$stmt = $connect->prepare("SELECT * FROM department");
	$stmt->execute();
	$result = $stmt->get_result();

	echo "<form action='?' method='GET'>
    <select name='did'>";
	echo "<option value='all'>all</option>";
	while ($row = $result->fetch_assoc()) {
		echo "<option value='{$row['did']}'" . ($row['did'] == $studentsDid ? ' selected ' : '') . ">{$row['dname']}</option>";
	}
	echo "</select>
    <input type='submit' value='SHOW COURSES'><br>
    <input type='hidden' name='op' value='chooseCourse'>
    <input type='hidden' name='sid' value='$sid'>
    </form>";
	echo " <td></td><a href='?op=studentList'>Back To Student L</a></td><br><br>";
}

function isCourseSelected($sid, $cid)
{
	// echo $sid, ',', $cid, '\n';
	global $connect;
	$stmt = $connect->prepare("SELECT * FROM take WHERE sid = $sid AND cid = $cid");
	$stmt->execute();
	$stmt->store_result();
	return $stmt->num_rows > 0;
}

function courseList($did, $sid)
{
	global $connect;
	$sql = "
        SELECT c.cid, c.title, c.credits, t.fname AS teacher_fname, t.lname AS teacher_lname
        FROM course c
        JOIN teach te ON c.cid = te.cid
        JOIN teacher t ON te.tid = t.tid
    ";
	if ($did === 'all') {
		$stmt = $connect->prepare($sql);
	} else {
		$sql = $sql . "WHERE c.did = ?";
		$stmt = $connect->prepare($sql);
		$stmt->bind_param('i', $did);
	}
	$stmt->execute();
	$result = $stmt->get_result();

	echo "<table border='1'><tr><th>Code</th><th>Title</th><th>Credits</th><th>Teacher</th><th>Take</th></tr>";
	while ($row = $result->fetch_assoc()) {
		$teacherName = $row['teacher_fname'] . ' ' . $row['teacher_lname'];
		echo "<tr>
            <td>{$row['cid']}</td>
            <td>{$row['title']}</td>
            <td>{$row['credits']}</td>
            <td>{$teacherName}</td>";
		if (isCourseSelected($sid, $row['cid'])) {
			echo "<td>chosen</td>";
		} else {
			echo "<td><a href='?op=takeCourse&did={$did}&sid={$sid}&cid={$row['cid']}'>choose</a></td>";
		}

		echo "</tr>";
	}
	echo '</table>';
}


function chosenCourses($sid)
{
	global $connect, $op;
	$op = $_REQUEST['op'] ?? '';
	$stmt = $connect->prepare("
        SELECT ta.cid, c.title, c.credits, t.fname AS teacher_fname, t.lname AS teacher_lname, SUM(c.credits) OVER() AS summ
        FROM take ta 
        JOIN course c ON ta.cid = c.cid
        JOIN teach te ON c.cid = te.cid
        JOIN teacher t ON te.tid = t.tid
        WHERE ta.sid = ?
    ");
	$stmt->bind_param('i', $sid);
	$stmt->execute();
	$result = $stmt->get_result();

	$totalCredits = 0;
	$courses = []; // Store all rows temporarily

	// Fetch the data
	while ($row = $result->fetch_assoc()) {
		$courses[] = $row; // Store each row in an array
		if ($totalCredits == 0) {
			$totalCredits = $row['summ']; // Store the total credits from the first row
		}
	}

	// Display the total credits above the table
	if ($op == 'chosenCourses' || $op == 'studentSchedule') {
		echo "<span style='color:green;'>Total Credits: {$totalCredits}</span><br>
		<td></td><a href='?op=studentList'>Back To Student L</a></td><br><br>
		";
	}


	// Generate the table
	echo "<table border='1'>
    <tr>
    <th>Code</th>
    <th>Title</th>
    <th>Credits</th>
    <th>Teacher</th>
    <th>Delete</th>
    </tr>";

	foreach ($courses as $row) {
		$teacherName = $row['teacher_fname'] . ' ' . $row['teacher_lname'];
		echo "<tr>
            <td>{$row['cid']}</td>
            <td>{$row['title']}</td>
            <td>{$row['credits']}</td>
            <td>{$teacherName}</td>
            <td><a href='?op=deleteCourse&sid={$sid}&cid={$row['cid']}'>delete</a></td>
        </tr>";
	}
	echo '</table>';
}

function takeCourse($sid, $cid)
{
	global $connect;
	$stmt = $connect->prepare("INSERT IGNORE INTO take (sid, cid) VALUES (?, ?)");
	$stmt->bind_param('is', $sid, $cid);
	$stmt->execute();
	if ($stmt->affected_rows) {
		echo "Course added.<br>";
	} else {
		echo "Unable to add the course.<br>";
	}
}

function deleteCourse($sid, $cid)
{
	global $connect;
	$stmt = $connect->prepare("DELETE FROM take WHERE sid = ? AND cid = ? LIMIT 1");
	$stmt->bind_param('ii', $sid, $cid);
	$stmt->execute();
	if ($stmt->affected_rows) {
		echo "Course deleted.<br>";
	} else {
		echo "Unable to delete the course.<br>";
	}
}

function updateStudent($sid, $fname, $lname, $did, $birthdate, $email)
{
	global $connect;
	$avatarId = guidv4();
	$avatarName = $avatarId . ".png";
	$targetPath = "public/" . basename($avatarName);
	move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $targetPath);
	// Update the student table
	$stmt = $connect->prepare("UPDATE student SET fname = ?, lname = ?, did = ?, birthdate = ?, avatarId = ? WHERE sid = ?");
	$stmt->bind_param('ssissi', $fname, $lname, $did, $birthdate, $avatarName, $sid);
	$stmt->execute();
	echo $stmt->affected_rows . " student records updated.<br>";

	// Update the department table
	$stmt = $connect->prepare("UPDATE department SET email = ? WHERE did = ?");
	$stmt->bind_param('si', $email, $did);
	$stmt->execute();
	echo $stmt->affected_rows . " department email updated.<br>";
}


function updateForm($sid)
{
	global $connect;

	// Fetch student information
	$stmt = $connect->prepare("SELECT student.*, department.email, department.dname, department.did 
        FROM student 
        JOIN department ON student.did = department.did 
        WHERE student.sid = ? LIMIT 1");
	$stmt->bind_param('i', $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	// Fetch department list
	$deptStmt = $connect->prepare("SELECT did, dname FROM department");
	$deptStmt->execute();
	$deptResult = $deptStmt->get_result();
	$avatarUrl = $row['avatarId'] ? ("public/" . $row['avatarId']) : "public/placeholder.png";
	// Start the form
	echo "<form method='POST' enctype='multipart/form-data'>
    <table border='1'>
        <tr>
		<td rowspan='4'  id='picture'>
                    <label for='fileInput' style='display: block; text-align: center;cursor: pointer;'>
					<img src='{$avatarUrl}' alt='Picture Here' style='width: 100px; height: 100px;'></label>
                    <input type='file' id='fileInput' style='opacity: 0;position: absolute;' value='{$row['avatarId']}' name='uploadedFile' accept='image/*'>
                </td>
            <td>Firstname</td>
            <td>
                <input type='text' name='fname' value='{$row['fname']}'>
            </td>
			
		<td>Firstname</td>
            <td>
                <input type='text' name='lname' value='{$row['lname']}'>
            </td>

            
        </tr>
        <tr>
            <td>Sid</td>
            <td><input type='text'  name='sid' value='{$row['sid']}'></td>
            <td>Birthdate</td>
             <td><input type='date' name='birthdate' value='{$row['birthdate']}'></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type='email' name='email' value='{$row['email']}'></td>
        </tr>
        <tr>
            <td>Dept</td>
            <td>
                <select name='did'>";
	// Populate the select box with departments
	while ($deptRow = $deptResult->fetch_assoc()) {
		$selected = ($deptRow['did'] == $row['did']) ? ' selected ' : '';
		echo "<option value='{$deptRow['did']}'{$selected}>{$deptRow['dname']}</option>";
	}
	echo "</select>
            </td>
            <td><input type='submit' name='gonder' value='SAVE'> <a href='?op=list'>Home</a></td>
        </tr>
    </table>
    <input type='hidden' name='op' value='update'>
</form>";
}

function updateTeacher($tid, $fname, $lname, $birthdate, $birthplace, $did)
{

	global $connect;

	// Update teacher information
	$stmt = $connect->prepare("UPDATE teacher SET fname = ?, lname = ?, birthdate = ?, birthplace = ?, did = ? WHERE tid = ?");
	$stmt->bind_param('ssssii', $fname, $lname, $birthdate, $birthplace, $did, $tid);
	$stmt->execute();
	if ($stmt->affected_rows) {
		echo "Teacher updated.<br>";
	} else {
		echo "Unable to update the teacher.<br>";
	}
}
function updateTeacherForm($tid)
{

	global $connect;

	// Fetch teacher information
	$stmt = $connect->prepare("SELECT * FROM teacher WHERE tid = ? LIMIT 1");
	$stmt->bind_param('i', $tid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();

	// Start the form	
	echo "<form method='POST'>
	<table border='1'>
		<tr>
			<td>Firstname</td>
			<td><input type='text' name='fname' value='{$row['fname']}'></td>
			<td>Lastname</td>
			<td><input type='text' name='lname' value='{$row['lname']}'></td>
		</tr>
		<tr>
			<td>Birth Date</td>	
			<td><input type='date' name='birthdate' value='{$row['birthdate']}'></td>
			<td>Birth Place</td>
			<td><input type='text' name='birthplace' value='{$row['birthplace']}'></td>
		</tr>
		<tr>
			<td>Did</td>
			<td><input type='text' name='did' value='{$row['did']}'></td>
		</tr>
		<tr>
			<td><input type='submit' name='gonder' value='SAVE'> <a href='?op=teacherList'>Home</a></td>
		</tr>
	</table>
	<input type='hidden' name='op' value='updateTeacher'>
	<input type='hidden' name='tid' value='{$row['tid']}'>
	</form>";
}
function deleteStudent($sid)
{
	global $connect;
	$stmt = $connect->prepare("DELETE FROM student WHERE sid = ? LIMIT 1");
	$stmt->bind_param('i', $sid);
	$stmt->execute();
	if ($stmt->affected_rows) {
		echo "Student deleted.<br>";
	} else {
		echo "Unable to delete the student.<br>";
	}
}

function deleteTeacher($tid)
{
	global $connect;
	$stmt = $connect->prepare("DELETE FROM teacher WHERE tid = ? LIMIT 1");
	$stmt->bind_param('i', $tid);
	$stmt->execute();
	if ($stmt->affected_rows) {
		echo "Teacher deleted.<br>";
	} else {
		echo "Unable to delete the teacher.<br>";
	}
}

function addStudentForm()
{
	global $connect;
	$deptQuery = "SELECT did, dname FROM department";
	$deptStmt = $connect->prepare($deptQuery);
	$deptStmt->execute();
	$deptResult = $deptStmt->get_result();

	echo "Form for add new students...";
	echo "<form method='POST' action='' enctype='multipart/form-data'>
    <table border='1'>
        <tr>
		<td rowspan='4' id='picture'>
            <label for='fileInput' style='display: block; text-align: center; cursor: pointer;'>
				<img src='' alt='Picture Here' style='width: 100px; height: 100px;'></label>
            <input type='file' id='fileInput' style='opacity: 0; position: absolute;' name='uploadedFile' accept='image/*'>
        </td>
        <td>Firstname</td>
        <td><input type='text' name='fname' required ></td>
		<td>Lastname</td>
        <td><input type='text' name='lname' required ></td>
        </tr>
        <tr>
            <td>Birthdate</td>
            <td><input type='date' name='birthdate' required ></td>
			<td>Birthplace</td>
            <td><input type='text' name='birthplace' required ></td>
        </tr>
        <tr>
			<td>Department</td>
            <td>
                <select name='did' required>";

	// Populate the select options with department data
	while ($deptRow = $deptResult->fetch_assoc()) {
		echo "<option value='{$deptRow['did']}'>{$deptRow['dname']}</option>";
	}

	echo "      </select>
            </td>
        </tr>
        <tr>
            <td colspan='4'>
				<input type='submit' name='gonder' value='Add'>
				<a href='?op=list'>Cancel</a>
			</td>
        </tr>
    </table>
</form>";
}

function addStudentToDb($fname, $lname, $birthdate, $birthplace, $did, $avatarId)
{
	global $connect;

	if ($connect->connect_error) {
		die("Connection failed: " . $connect->connect_error);
	}

	// Formdan gelen verileri al
	$stmt = $connect->prepare("INSERT INTO student (fname, lname, birthdate, birthplace, did, avatarId) VALUES (?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("sssssi", $fname, $lname, $birthdate, $birthplace, $did, $avatarId);

	if ($stmt->execute()) {
		// Başarılı ekleme durumunda yönlendirme
		header("Location: ?op=studentList");
		exit(); // Yönlendirme sonrası script'i sonlandırın.
	} else {
		echo "Error: " . $stmt->error;
	}

	$stmt->close();
}

function addTeacherForm()
{
	global $connect;

	// Fetch all departments
	$deptQuery = "SELECT did, dname FROM department";
	$deptStmt = $connect->prepare($deptQuery);
	$deptStmt->execute();
	$deptResult = $deptStmt->get_result();

	echo "Form for adding new teachers...";
	echo "<form method='POST' action='' enctype='multipart/form-data'>
    <table border='1'>
        <tr>
            <td>Firstname</td>
            <td><input type='text' name='fname' required ></td>
            <td>Lastname</td>
            <td><input type='text' name='lname' required ></td>
        </tr>
        <tr>
            <td>Birthdate</td>
            <td><input type='date' name='birthdate' required ></td>
            <td>Birthplace</td>
            <td><input type='text' name='birthplace' required ></td>
        </tr>
        <tr>
            <td>Department</td>
            <td>
                <select name='did' required>";

	// Populate the select options with department data
	while ($deptRow = $deptResult->fetch_assoc()) {
		echo "<option value='{$deptRow['did']}'>{$deptRow['dname']}</option>";
	}

	echo "      </select>
            </td>
        </tr>
        <tr>
            <td><input type='submit' name='gonder' value='SAVE'> <a href='?op=teacherList'>Home</a></td>
        </tr>
    </table>
    </form>";

	$deptStmt->close();
}

function addTeacherToDb($fname, $lname, $birthdate, $birthplace, $did)
{
	global $connect;

	if ($connect->connect_error) {
		die("Connection failed: " . $connect->connect_error);
	}

	// Formdan gelen verileri al
	$stmt = $connect->prepare("INSERT INTO teacher (fname, lname, birthdate, birthplace, did) VALUES ( ?, ?, ?, ?, ?)");
	$stmt->bind_param("ssssi", $fname, $lname, $birthdate, $birthplace, $did);

	if ($stmt->execute()) {
		// Başarılı ekleme durumunda yönlendirme
		header("Location: ?op=teacherList");
		exit(); // Yönlendirme sonrası script'i sonlandırın.
	} else {
		echo "Error: " . $stmt->error;
	}

	$stmt->close();
}

function addCourseForm()
{
	global $connect;

	$deptQuery = "SELECT did, dname FROM department";
	$deptStmt = $connect->prepare($deptQuery);
	$deptStmt->execute();
	$deptResult = $deptStmt->get_result();

	$tchrQuery = "SELECT tid,CONCAT(teacher.fname, ' ', teacher.lname) AS teacher_name FROM teacher";
	$tchrStmt = $connect->prepare($tchrQuery);
	$tchrStmt->execute();
	$tchrResult = $tchrStmt->get_result();

	echo "Form for adding new courses...";
	echo "<form method='POST' action='' enctype='multipart/form-data'>
    <table border='1'>
        <tr>
            <td>Title</td>
            <td><input type='text' name='title' placeholder='Computer arcitecture..' required ></td>
            <td>Description</td>
            <td><input type='text' name='description' placeholder='CENG 351..'  required ></td>
        </tr>
        <tr>
            <td>Credits</td>
            <td><input type='text' name='credits' required ></td>
        </tr>
        <tr>
            <td>Department</td>
            <td>
                <select name='did' required>";

	// Populate the select options with department data
	while ($deptRow = $deptResult->fetch_assoc()) {
		echo "<option value='{$deptRow['did']}'>{$deptRow['dname']}</option>";
	}

	echo "      </select>
            </td>

			            <td>Teacher</td>
            <td>
                <select name='tid' required>";

	// Populate the select options with department data
	while ($tchrRow = $tchrResult->fetch_assoc()) {
		echo "<option value='{$tchrRow['tid']}'>{$tchrRow['teacher_name']}</option>";
	}

	echo "      </select>
            </td>
        </tr>
        <tr>
            <td><input type='submit' name='gonder' value='SAVE'> <a href='?op=displayCourseList'>Home</a></td>
        </tr>
    </table>
    </form>";

	$deptStmt->close();
}
function addCourseToDb($title, $description, $credits, $did, $tid)
{
	global $connect;

	if ($connect->connect_error) {
		die("Connection failed: " . $connect->connect_error);
	}

	// Formdan gelen verileri al
	$stmt = $connect->prepare("INSERT INTO course (title, description, credits,did) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssii", $title, $description, $credits, $did);

	if ($stmt->execute()) {
		$cid = $stmt->insert_id;
		// add new entry to teach table to match teacher and the course
		$SQL = "INSERT INTO teach (tid, cid) VALUES ($tid, $cid)";
		$connect->query($SQL);
		$connect->close();
		// Başarılı ekleme durumunda yönlendirme
		header("Location: ?op=displayCourseList");
		exit(); // Yönlendirme sonrası script'i sonlandırın.
	} else {
		echo "Error: " . $stmt->error;
	}


	$stmt->close();
}

function addDepartmentForm()
{

	echo "Form for adding new department...";
	echo "<form method='POST' action='' enctype='multipart/form-data'>
    <table border='1'>
        <tr>
            <td>Department name</td>
            <td><input type='text' name='dname' placeholder='Comp. Eng.' required ></td>
            <td>Department comment</td>
            <td><input type='text' name='comments' placeholder='Computer Eng. Department'  required ></td>
        </tr>
        <tr>
            <td>Department email</td>
            <td><input type='text' name='email' placeholder='ceng@fatih.edu.tr' required ></td>
        </tr>
        <tr>

        <tr>
            <td><input type='submit' name='gonder' value='SAVE'> <a href='?op=departmentList'>Home</a></td>
        </tr>
    </table>
    </form>";
}
function addDepartmentToDb($dname, $comments, $email)
{
	global $connect;

	if ($connect->connect_error) {
		die("Connection failed: " . $connect->connect_error);
	}

	// Formdan gelen verileri al
	$stmt = $connect->prepare("INSERT INTO department (dname, comments, email) VALUES ( ?, ?, ?)");
	$stmt->bind_param("sss", $dname, $comments, $email);

	if ($stmt->execute()) {
		// Başarılı ekleme durumunda yönlendirme
		header("Location: ?op=departmentList");
		exit(); // Yönlendirme sonrası script'i sonlandırın.
	} else {
		echo "Error: " . $stmt->error;
	}

	$stmt->close();
}


function studentList($sidChosen, $col, $dir, $pageNo)
{
	global $connect;
	$cid = isset($_GET['cid']) ? $_GET['cid'] : null;
	$did = isset($_GET['did']) ? $_GET['did'] : null;
	$tid = isset($_GET['tid']) ? $_GET['tid'] : null;
	$dname = null;
	$title = null;
	$teacherName = null;

	// cid parametresi
	$start = ($pageNo - 1) * 5;
	if ($did) {
		$deptQuery = "SELECT dname FROM department WHERE did = ?";
		$deptStmt = $connect->prepare($deptQuery);
		$deptStmt->bind_param('i', $did);
		$deptStmt->execute();
		$deptResult = $deptStmt->get_result();
		if ($deptRow = $deptResult->fetch_assoc()) {
			$dname = $deptRow['dname'];
		}
		$deptStmt->close();
	} elseif ($cid) {
		$crsQuery = "SELECT title FROM course WHERE cid = ?";
		$crsStmt = $connect->prepare($crsQuery);
		$crsStmt->bind_param('i', $cid);
		$crsStmt->execute();
		$crsResult = $crsStmt->get_result();
		if ($crsRow = $crsResult->fetch_assoc()) {
			$title = $crsRow['title'];
		}
		$crsStmt->close();
	} elseif ($tid) {
		$teachQuery = "SELECT fname,lname FROM teacher WHERE tid = ?";
		$teachStmt = $connect->prepare($teachQuery);
		$teachStmt->bind_param('i', $tid);
		$teachStmt->execute();
		$teachResult = $teachStmt->get_result();
		if ($teachRow = $teachResult->fetch_assoc()) {
			$teacherName = $teachRow['fname'] . " " . $teachRow['lname'];
		}
		$teachStmt->close();
	}



	// SQL sorgusunu oluştur
	$sql = "
	SELECT student.sid, student.fname, student.lname, department.dname, COUNT(take.cid) AS course_count, AVG(take.grade) AS grade
	FROM student
	LEFT JOIN department ON student.did = department.did
	LEFT JOIN take ON student.sid = take.sid
	LEFT JOIN course ON take.cid = course.cid
	LEFT JOIN teach ON course.cid = teach.cid
";
	if ($cid) {
		$sql .= " WHERE take.cid = ?";
	} elseif ($did) {
		$sql .= " WHERE student.did = ?";
	} elseif ($tid) {
		$sql .= " WHERE teach.tid = ?";
	}

	$sql .= " GROUP BY student.sid ORDER BY $col $dir LIMIT ?, 5";


	$stmt = $connect->prepare($sql);

	if ($cid) {
		// Eğer cid varsa, bind_param içinde iki parametre var: cid ve start
		$stmt->bind_param('ii', $cid, $start); // Burada 'ii' çünkü iki integer parametre var
	} elseif ($did) {
		// Eğer cid yoksa, sadece start parametresi var	
		$stmt->bind_param('ii', $did, $start); // Burada 'i' çözümü iki integer parametre var
	} elseif ($tid) {
		// Eğer cid yoksa, sadece start parametresi var	
		$stmt->bind_param('ii', $tid, $start); // Burada 'i' çözümü iki integer parametre var
	} else {
		// Eğer cid yoksa, sadece start parametresi var
		$stmt->bind_param('i', $start); // Burada 'i' çünkü sadece bir integer parametre var
	}
	$stmt->execute();
	$result = $stmt->get_result();
	//$totalRows = $result->num_rows;
	//$totalPages = ceil($totalRows / 5);

	echo "<h3>Student List</h3>";
	if ($did) {
		echo "<p style='color:green;'>Students of: <strong>" . ($dname) . "</strong> department</p>";
	} elseif ($cid) {
		echo "<p style='color:green;'>Students of: <strong>" . ($title) . "</strong> Course</p>";
	} elseif ($tid) {
		echo "<p style='color:green;'>Students of: <strong>" . ($teacherName) . "</strong> Teacher</p>";
	}
	echo "<table border='1'>
        <tr>
            <th><a href='?op=list&col=sid&direct=" . ($dir == 'ASC' ? 'DESC' : 'ASC') . "&pageno=$pageNo'>Sid</a></th>
            <th><a href='?op=list&col=fname&direct=" . ($dir == 'ASC' ? 'DESC' : 'ASC') . "&pageno=$pageNo'>Fname</a></th>
            <th><a href='?op=list&col=lname&direct=" . ($dir == 'ASC' ? 'DESC' : 'ASC') . "&pageno=$pageNo'>Lname</a></th>
            <th><a href='?op=list&col=dname&direct=" . ($dir == 'ASC' ? 'DESC' : 'ASC') . "&pageno=$pageNo'>Dept</a></th>
            <th><a href='?op=list&col=course_count&direct=" . ($dir == 'ASC' ? 'DESC' : 'ASC') . "&pageno=$pageNo'>Taken courses</a></th>
            <th><a href='?op=list&col=grade&direct=" . ($dir == 'ASC' ? 'DESC' : 'ASC') . "&pageno=$pageNo'>GPA</a></th>
            <th>Weekly Schedule</th>
            <th>Take course</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>";

	while ($row = $result->fetch_assoc()) {
		$grade = $row['grade'] ? $row['grade'] : 'N/A';
		echo "<tr>
            <td>{$row['sid']}</td>
            <td>{$row['fname']}</td>
            <td>{$row['lname']}</td>
            <td>{$row['dname']}</td>
            <td><a href='?op=chosenCourses&sid={$row['sid']}'>View  ({$row['course_count']})</a></td>
            <td>{$grade}</td> 
            <td><a href='?op=studentSchedule&sid={$row['sid']}'>View</a></td>
            <td><a href='?op=chooseCourse&sid={$row['sid']}'>Take</a></td>
            <td><a href='?op=info&sid={$row['sid']}'>Edit</a></td>
            <td><a href='?op=deleteStudent&sid={$row['sid']}'>Delete</a></td>
        </tr>";
	}

	echo "</table>";

	// Pagination logic
	$stmt = $connect->prepare(
		"
	SELECT COUNT(DISTINCT student.sid) AS total
	FROM student
	LEFT JOIN take ON student.sid = take.sid
	LEFT JOIN course ON take.cid = course.cid
	LEFT JOIN teach ON course.cid = teach.cid
	" . ($cid ? "WHERE take.cid = ?" : ($did ? "WHERE student.did = ?" : ($tid ? "WHERE teach.tid = ?" : "")))
	);

	if ($cid) {
		$stmt->bind_param('i', $cid);
	} elseif ($did) {
		$stmt->bind_param('i', $did);
	} elseif ($tid) {
		$stmt->bind_param('i', $tid);
	}

	$stmt->execute();
	$result = $stmt->get_result();
	$totalRows = $result->fetch_assoc()['total'];
	$totalPages = ceil($totalRows / 5);

	echo "<div>";
	for ($i = 1; $i <= $totalPages; $i++) {
		echo "<a href='?op=studentList&cid=$cid&col=$col&direct=$dir&pageno=$i'>$i</a> ";
	}
	echo "</div><br>";
	echo "To add new students click here <a href='?op=addStudent'><button style='cursor:pointer'>New student</button></a>";
}

function displayCourseList($connect)
{
	// Get sort and order from query parameters
	$sort = isset($_GET['sort']) ? $_GET['sort'] : 'cid';
	$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
	$tid = isset($_GET['tid']) ? $_GET['tid'] : null;
	$did = isset($_GET['did']) ? $_GET['did'] : null;
	$dname = null;

	// Check for teacher ID

	// Validate sorting and ordering
	$validSorts = ['cid', 'title', 'dept', 'teacher_name', 'student_count', 'average', 'credits'];
	$validOrders = ['ASC', 'DESC'];
	if (!in_array($sort, $validSorts)) $sort = 'cid';
	if (!in_array($order, $validOrders)) $order = 'ASC';
	if ($did) {
		$deptQuery = "SELECT dname FROM department WHERE did = ?";
		$deptStmt = $connect->prepare($deptQuery);
		$deptStmt->bind_param('i', $did);
		$deptStmt->execute();
		$deptResult = $deptStmt->get_result();
		if ($deptRow = $deptResult->fetch_assoc()) {
			$dname = $deptRow['dname'];
		}
		$deptStmt->close();
	} elseif ($tid) {
		$teachQuery = "SELECT fname,lname FROM teacher WHERE tid = ?";
		$teachStmt = $connect->prepare($teachQuery);
		$teachStmt->bind_param('i', $tid);
		$teachStmt->execute();
		$teachResult = $teachStmt->get_result();
		if ($teachRow = $teachResult->fetch_assoc()) {
			$teacherName = $teachRow['fname'] . " " . $teachRow['lname'];
		}
		$teachStmt->close();
	}

	// SQL query with dynamic sorting and optional teacher filter
	$sql = "
        SELECT 
            course.cid, 
            course.title, 
            department.dname AS dept,
            CONCAT(teacher.fname, ' ', teacher.lname) AS teacher_name,
            COUNT(take.sid) AS student_count,
			AVG(take.grade) AS average,
            course.credits
        FROM 
            course
        JOIN 
            department ON course.did = department.did
        JOIN 
            teach ON course.cid = teach.cid
        JOIN 
            teacher ON teach.tid = teacher.tid
        LEFT JOIN 
            take ON teach.cid = take.cid
        
    ";

	if ($tid) {
		$sql .= " WHERE teach.tid = ?";
	} elseif ($did) {
		$sql .= " WHERE course.did = ?";
	}

	$sql .= " GROUP BY course.cid ORDER BY $sort $order;
    ";

	$stmt = $connect->prepare($sql);

	if ($tid) {
		$stmt->bind_param('i', $tid);
	} elseif ($did) {
		$stmt->bind_param('i', $did);
	}

	$stmt->execute();
	$result = $stmt->get_result();

	echo "<h3>Course List</h3>";
	if ($did) {
		echo "<p style='color:green;'>Courses of: <strong>" . ($dname) . "</strong> department</p>";
	} elseif ($tid) {
		echo "<p style='color:green;'>Courses of: <strong>" . ($teacherName) . "</strong> Teacher</p>";
	}


	if ($result->num_rows > 0) {
		// Generate table headers with sorting links
		echo "<table border='1'>
                <tr>
                    <th><a href='?op=displayCourseList&sort=cid&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&tid=$tid'>Cid</a></th>
                    <th><a href='?op=displayCourseList&sort=title&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&tid=$tid'>Title</a></th>
                    <th><a href='?op=displayCourseList&sort=dept&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&tid=$tid'>Department</a></th>
                    <th><a href='?op=displayCourseList&sort=teacher_name&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&tid=$tid'>Teacher Name</a></th>
                    <th><a href='?op=displayCourseList&sort=student_count&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&tid=$tid'>Students</a></th>
                    <th><a href='?op=displayCourseList&sort=credits&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&tid=$tid'>Credits</a></th>
					<th><a href='?op=displayCourseList&sort=average&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&tid=$tid'>Average</a></th>
                    <th>Weekly Schedule</th>
                </tr>";

		while ($row = $result->fetch_assoc()) {
			$AVG = $row['average'] ? $row['average'] : 'N/A';
			echo "<tr>
                    <td>{$row['cid']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['dept']}</td>
                    <td>{$row['teacher_name']}</td>
                    <td><a href='?op=studentList&cid={$row['cid']}'>View  ({$row['student_count']})</a></td>
                    <td>{$row['credits']}</td>
					<td>$AVG</td>
                    <td><a href='?op=courseSchedule&cid={$row['cid']}'>View</a></td>
                </tr>";
		}
		echo "</table><br>";
		echo "To add new teachers click here <a href='?op=addCourse'><button style='cursor:pointer'>New course</button></a>";
	} else {
		echo "No courses found.";
	}
}

function teacherList($connect)
{
	// Get sort, order, and department ID from query parameters
	$sort = isset($_GET['sort']) ? $_GET['sort'] : 'tid';
	$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
	$did = isset($_GET['did']) ? $_GET['did'] : null; // Check for department ID
	$dname = null;

	// Validate sorting and ordering
	$validSorts = ['tid', 'fname', 'lname', 'department', 'number_of_courses', 'number_of_students', 'weekly_schedule'];
	$validOrders = ['ASC', 'DESC'];
	if (!in_array($sort, $validSorts)) $sort = 'tid';
	if (!in_array($order, $validOrders)) $order = 'ASC';

	// Fetch department name if department ID is provided
	if ($did) {
		$deptQuery = "SELECT dname FROM department WHERE did = ?";
		$deptStmt = $connect->prepare($deptQuery);
		$deptStmt->bind_param('i', $did);
		$deptStmt->execute();
		$deptResult = $deptStmt->get_result();
		if ($deptRow = $deptResult->fetch_assoc()) {
			$dname = $deptRow['dname'];
		}
		$deptStmt->close();
	}

	// SQL query with dynamic sorting and optional department filter
	$sql = "
        SELECT 
            teacher.tid,
            teacher.fname,
            teacher.lname,
            department.dname AS department,
            COUNT(DISTINCT teach.cid) AS number_of_courses,
            COUNT(DISTINCT take.sid) AS number_of_students
        FROM 
            teacher
        JOIN 
            department ON teacher.did = department.did
        LEFT JOIN 
            teach ON teacher.tid = teach.tid
        LEFT JOIN 
            take ON teach.cid = take.cid
    ";

	if ($did) {
		$sql .= " WHERE teacher.did = ?";
	}

	$sql .= " GROUP BY teacher.tid ORDER BY $sort $order; ";

	$stmt = $connect->prepare($sql);

	if ($did) {
		$stmt->bind_param('i', $did);
	}

	$stmt->execute();
	$result = $stmt->get_result();

	echo "<h3>Teacher List</h3>";
	if ($did) {
		echo "<p style='color:green;'>Teachers of: <strong>" . ($dname) . "</strong> department</p>";
	}

	if ($result->num_rows > 0) {
		// Generate table headers with sorting links
		echo "<table border='1'>
                <tr>
                    <th><a href='?op=teacherList&sort=tid&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&did=$did'>Tid</a></th>
                    <th><a href='?op=teacherList&sort=fname&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&did=$did'>First Name</a></th>
                    <th><a href='?op=teacherList&sort=lname&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&did=$did'>Last Name</a></th>
                    <th><a href='?op=teacherList&sort=department&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&did=$did'>Department</a></th>
                    <th><a href='?op=teacherList&sort=number_of_students&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&did=$did'>Students</a></th>
                    <th><a href='?op=teacherList&sort=number_of_courses&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "&did=$did'>Courses</a></th>
                    <th>Weekly Schedule</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>";

		while ($row = $result->fetch_assoc()) {
			echo "<tr>
                    <td>{$row['tid']}</td>
                    <td>{$row['fname']}</td>
                    <td>{$row['lname']}</td>
                    <td>{$row['department']}</td>
                    <td><a href=?op=studentList&tid={$row['tid']}>View  ({$row['number_of_students']})</a></td>
                    <td><a href=?op=displayCourseList&tid={$row['tid']}>View  ({$row['number_of_courses']})</a></td>
                    <td><a href=?op=teacherSchedule&tid={$row['tid']}>View</a></td>
                    <td><a href=?op=updateTeacherForm&tid={$row['tid']}>Edit</a></td>
                    <td><a href=?op=deleteTeacher&tid={$row['tid']}>Delete</a></td>
                </tr>";
		}
		echo "</table><br>";
		echo "To add new teachers click here <a href='?op=addTeacher'><button style='cursor:pointer'>New teacher</button></a>";
	} else {
		echo "No teachers found.";
	}
}


function departmentList($connect)
{
	// Get sort and order from query parameters
	$sort = isset($_GET['sort']) ? $_GET['sort'] : 'did';
	$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

	// Validate sorting and ordering
	$validSorts = ['did', 'dname', 'number_of_students', 'number_of_courses', 'number_of_teachers'];
	$validOrders = ['ASC', 'DESC'];
	if (!in_array($sort, $validSorts)) $sort = 'did';
	if (!in_array($order, $validOrders)) $order = 'ASC';

	$sql = "
        SELECT 
            department.did,
            department.dname,
            COUNT(DISTINCT student.sid) AS number_of_students,
            COUNT(DISTINCT course.cid) AS number_of_courses,
            COUNT(DISTINCT teacher.tid) AS number_of_teachers
        FROM 
            department
        LEFT JOIN 
            student ON department.did = student.did
        LEFT JOIN 
            course ON department.did = course.did
        LEFT JOIN 
            teacher ON department.did = teacher.did
        GROUP BY department.did ORDER BY $sort $order;
    ";

	$result = $connect->query($sql);
	echo "<h3>Department List</h3>";
	if ($result->num_rows > 0) {
		// Generate table headers with sorting links
		echo "<table border='1'>
                <tr>
                    <th><a href='?sort=did&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "'>Department ID</a></th>
                    <th><a href='?sort=dname&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "'>Department Name</a></th>
					<th><a href='?sort=number_of_teachers&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "'>Teachers</a></th>
                    <th><a href='?sort=number_of_students&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "'>Students</a></th>
                    <th><a href='?sort=number_of_courses&order=" . ($order === 'ASC' ? 'DESC' : 'ASC') . "'>Courses</a></th>
                </tr>";
		while ($row = $result->fetch_assoc()) {
			echo "<tr>
                    <td>{$row['did']}</td>
                    <td>{$row['dname']}</td>
					<td><a href=?op=teacherList&did={$row['did']}>View  ({$row['number_of_teachers']})</a></td>
					<td><a href=?op=studentList&did={$row['did']}>View ({$row['number_of_students']})</a></td>
					<td><a href=?op=displayCourseList&did={$row['did']}>View ({$row['number_of_courses']})</a></td>
					
                </tr>";
		}
		echo "</table><br>";
		echo "To add new departments click here <a href='?op=addDepartment'><button style='cursor:pointer'>New department</button></a>";
	} else {
		echo "No departments found.";
	}
}


function scheduleStudent($id, $sql)
{
	global $connect, $op;
	$op = $_REQUEST['op'] ?? '';

	$stmt = $connect->prepare($sql);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$result = $stmt->get_result();

	$Days = ['M' => '', 'T' => '', 'W' => '', 'H' => '', 'F' => ''];
	$schedule = array_fill_keys(range('8', '16'), $Days);

	$header = ''; // Initialize a variable to hold the header content

	while ($row = $result->fetch_assoc()) {
		$hour = (int) $row['hourOfDay'];
		$day = $row['dayOfWeek'];
		if (isset($Days[$day])) {
			$schedule[$hour][$day] =
				"<a href=?op=courseSchedule&cid={$row['cid']}>{$row['cid']} {$row['title']}</a><br>
                <a href=?op=roomSchedule&rid={$row['rid']}>{$row['description']}</a><br>
                <a href=?op=teacherSchedule&tid={$row['tid']}>{$row['fname']} {$row['lname']}</a>";
		}

		// Set the header based on the operation
		if ($op == 'courseSchedule') {
			$header = "<span style='color:green;'>Weekly Schedule for Course: {$row['cid']} {$row['title']}</span><br>
			<a href='?op=displayCourseList'>Back To Course L</a><br><br>";
		} elseif ($op == 'teacherSchedule') {
			$header = "<span style='color:green;'>Weekly Schedule for Instructor: {$row['fname']} {$row['lname']}</span><br>
			<a href='?op=teacherList'>Back To Teacher L</a><br><br>";
		} elseif ($op == 'roomSchedule') {
			$header = "<span style='color:green;'>Weekly Schedule for Room: {$row['description']}</span><br>";
		} elseif ($op = 'studentSchedule') {
			$header = "<a href='?op=studentList'>Back To Student L</a><br>";
		}
	}

	// Output the header
	echo $header;

	// Output the schedule table
	if ($op === 'courseSchedule') {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$hour = $_POST['hour'];
			$day = $_POST['day'];
			$rid = $_POST['rid'] ?? null;

			// Silme işlemi için
			if (isset($_POST['delete'])) {
				$deleteSql = "DELETE FROM schedule WHERE cid = ? AND hourOfDay = ? AND dayOfWeek = ?";
				$stmtDelete = $connect->prepare($deleteSql);
				$stmtDelete->bind_param('iis', $id, $hour, $day);
				$stmtDelete->execute();
			}
			// Ekleme işlemi için
			else {
				$addCourseSql = "INSERT INTO schedule (cid, hourOfDay, dayOfWeek, rid) VALUES (?, ?, ?, ?)";
				$stmtAdd = $connect->prepare($addCourseSql);
				$stmtAdd->bind_param('iisi', $id, $hour, $day, $rid);
				$stmtAdd->execute();
			}
		}

		$stmt = $connect->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		$Days = ['M' => '', 'T' => '', 'W' => '', 'H' => '', 'F' => ''];
		$schedule = array_fill_keys(range('8', '16'), $Days);

		$header = ''; // Başlık içeriğini tutmak için bir değişken

		while ($row = $result->fetch_assoc()) {
			$hour = (int) $row['hourOfDay'];
			$day = $row['dayOfWeek'];
			if (isset($Days[$day])) {
				$schedule[$hour][$day] =
					"<a href=?op=courseSchedule&cid={$row['cid']}>{$row['cid']} {$row['title']}</a><br>
                <a href=?op=roomSchedule&rid={$row['rid']}>{$row['description']}</a><br>
                <a href=?op=teacherSchedule&tid={$row['tid']}>{$row['fname']} {$row['lname']}</a><br>
				<form action='' method='POST' style='display:inline'>
                <input hidden name='hour' value='$hour'/>
                <input hidden name='day' value='$day'/>
                <button style='all:unset; cursor:pointer; color:red;' name='delete' type='submit'>Delete</button>
                </form>";
			}
		}

		// Başlığı çıktıya bas
		echo $header;

		if ($op === 'courseSchedule') {
			// Oda bilgilerini almak için ek bir sorgu
			$stmtRoom = $connect->prepare("SELECT * FROM room");
			$stmtRoom->execute();
			$roomResult = $stmtRoom->get_result();
			$rooms = [];
			while ($row = $roomResult->fetch_assoc()) {
				$rooms[] = $row;
			}

			// Haftalık program tablosunu oluştur
			echo "<table border='1'>";
			echo "<tr><td>Hour</td><td>Mon</td><td>Tue</td><td>Wed</td><td>Thu</td><td>Fri</td></tr>";
			foreach ($schedule as $hour => $days) {
				echo "<tr>
				<td>$hour</td>";
				foreach (['M', 'T', 'W', 'H', 'F'] as $day) {
					if (empty($days[$day])) {
						echo "<td><form action='' method='POST'>
						<input hidden name='hour' value='$hour'/>
						<input hidden name='day' value='$day'/>
                        <button style='all:unset; cursor:pointer; text-decoration: underline;' id='add' type='submit'>Add</button>
						<select name='rid'>";
						foreach ($rooms as $room) {
							echo "<option value='{$room['rid']}'>{$room['description']}</option>";
						}
						echo "</select>
					</form></td>";
					} else {
						echo "<td>{$days[$day]}</td>";
					}
				}
				echo "</tr>";
			}
			echo '</table>';
		}
		# code...
	} else {
		echo "<table border='1'>";
		echo "<tr><td>Hour</td><td>Mon</td><td>Tue</td><td>Wed</td><td>Thu</td><td>Fri</td></tr>";
		foreach ($schedule as $hour => $days) {
			echo "<tr>
        <td>$hour</td>
        <td>{$days['M']}</td>
        <td>{$days['T']}</td>
        <td>{$days['W']}</td>
        <td>{$days['H']}</td>
        <td>{$days['F']}</td>
        </tr>";
		}
		echo '</table>';
	}
}


function getStudentDid($sid)
{
	global $connect;
	$stmt = $connect->prepare("SELECT did FROM student WHERE sid = ? LIMIT 1");
	$stmt->bind_param('i', $sid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	return $row['did'] ?? '';
}

function guidv4($data = null)
{
	// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
	$data = $data ?? random_bytes(16);
	assert(strlen($data) == 16);

	// Set version to 0100
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
	// Set bits 6-7 to 10
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

	// Output the 36 character UUID.
	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
mysqli_close($connect);