<?php
session_start();
ini_set('display_errors', 1);
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}
    
	function login(){
        extract($_POST);
    
        $tables = array("","users","faculty_list","student_list","superior_list");
        $userTypes = array("","admin","faculty","student","superior");
    
        // Initialize a variable to store the detected user type
        $detectedUserType = null;
    
        // Iterate through tables to check for the user
        for($i = 1; $i <= 4; $i++) {
            $qry = $this->db->query("SELECT *, id,CONCAT(firstname, ' ', lastname) 
            AS name FROM {$tables[$i]} WHERE email = '".$email."' AND password = '".md5($password)."'");
    
            if($qry->num_rows > 0){
                // User found in this table, set the detected user type
                $detectedUserType = $i;
    
                // Save user information to session
                foreach ($qry->fetch_array() as $key => $value) {
                    if($key != 'password' && !is_numeric($key))
                        $_SESSION['login_'.$key] = $value;
                }
    
                // Set other session variables
                if (isset($userTypes[$detectedUserType])) {
                    $_SESSION['login_type'] = $userTypes[$detectedUserType];
                    $_SESSION['login_view_folder'] = $userTypes[$detectedUserType].'/';
                } else {
                    echo 'Invalid user type detected.';
                }
                // Get academic information
                $academic = $this->db->query("SELECT * FROM academic_list WHERE is_default = 1");
                if ($academic->num_rows > 0) {
                    $academicData = $academic->fetch_assoc();
                    foreach ($academicData as $k => $v) {
                        if (!is_numeric($k)) {
                            $_SESSION['academic'][$k] = $v;
                        }
                    }
                }

                return 1; // Login success

            }
        }
    
        // If no user found in any table, return failure
        return 2; // Login failure
    }
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '".$student_code."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['rs_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");
		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password'){
					if(empty($v))
						continue;
					$v = md5($v);

				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set $data");

		}else{
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if($save){
			if(empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if(!in_array($key, array('id','cpass','password')) && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					$_SESSION['login_id'] = $id;
				if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user(){
		extract($_POST);
		$data = "";
		$type = array("","users","faculty_list","student_list","superior_list");
	foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table','password')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
        $user = $_SESSION['login_type'];

        if(isset($_SESSION['login_type']) && $user == 'admin')
        {
            $check = $this->db->query("SELECT * FROM `users` where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
            $login = "users"; 
            if($check > 0){
                return 2;
                exit;
            }
        }
        else if(isset($_SESSION['login_type']) && $user == 'student')
        {
            $check = $this->db->query("SELECT * FROM `student_list` where email ='$email' "
            .(!empty($id) ? " and id != {$id} " : ''))->num_rows;
            $login = "student_list";
            if($check > 0){
                return 2;
                exit;
            }
        }
        else if(isset($_SESSION['login_type']) && $user == 'faculty')
        {
            $check = $this->db->query("SELECT * FROM `faculty_list` where 
            email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
            $login = "faculty_list";
            if($check > 0){
                return 2;
                exit;
            }
        }
        else if(isset($_SESSION['login_type']) && $user == 'superior')
        {
            $check = $this->db->query("SELECT * FROM `superior_list` where 
            email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
            $login = "superior_list";
            if($check > 0){
                return 2;
                exit;
            }
        }
        else
        {

        }
		
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(!empty($password))
			$data .= " ,password=md5('$password') ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO $login set $data");
		}else{
			echo "UPDATE $login set $data where id = $id";
			$save = $this->db->query("UPDATE $login set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;
			return 1;
		}
        return 1;
	}
    
    function fetch_instructor() {
        if (isset($_POST['faculty_id'])) {
            $facultyId = $_POST['faculty_id'];
    
            // Query to fetch instructor information from the database
            $query = "SELECT * FROM faculty_list WHERE id = $facultyId";
            $result = $this->db->query($query);
    
            if ($result && $result->num_rows > 0) {
                $instructorData = $result->fetch_assoc();
    
                // Query to count the number of evaluations for this instructor
                $evaluationQuery = "SELECT COUNT(*) AS evaluation_count FROM evaluation_list_superior WHERE faculty_id = $facultyId";
                $evaluationResult = $this->db->query($evaluationQuery);
    
                if ($evaluationResult && $evaluationResult->num_rows > 0) {
                    $evaluationData = $evaluationResult->fetch_assoc();
                    $tse = (int) $evaluationData['evaluation_count'];
                } else {
                    $tse = 0; // Default to 0 if no evaluations found
                }
    
                // Prepare response data including tse (number of evaluations)
                $response = array(
                    'success' => true,
                    'data' => $instructorData,
                    'tse' => $tse
                );
    
                return json_encode($response);
            } else {
                return json_encode(array('success' => false, 'message' => 'Instructor not found'));
            }
        } else {
            return json_encode(array('success' => false, 'message' => 'Invalid request'));
        }
    }
    function fetch_ratings(){
        global $conn;

if (isset($_POST['faculty_id'])) {
    $facultyId = $_POST['faculty_id'];

    // Query para kunin ang ratings mula sa database base sa $facultyId
    $query = "SELECT * FROM evaluation_answers_superior WHERE faculty_id = $facultyId";
    $result = $conn->query($query);

    if ($result) {
        $ratingsData = array();

        // Kumpletuhin ang array ng ratings mula sa query result
        while ($row = $result->fetch_assoc()) {
            // Dito mo isama ang mga ratings na kailangan mo mula sa result set
            // Halimbawa, kung mayroon kang 'rating' field sa result:
            $ratingsData[] = $row['rating'];
        }

        // Bilangin ang bilang ng superiors na nag-evaluate
        $queryCount = "SELECT COUNT(DISTINCT superior_id) as total_superiors FROM evaluation_list_superior WHERE faculty_id = $facultyId";
        $resultCount = $conn->query($queryCount);
        $rowCount = $resultCount->fetch_assoc();
        $totalSuperiorsEvaluated = $rowCount['total_superiors'];

        // Kumuha ng pangalan ng instructor
        $queryInstructor = "SELECT CONCAT(firstname, ' ', lastname) AS name FROM faculty_list WHERE id = $facultyId";
        $resultInstructor = $conn->query($queryInstructor);
        $rowInstructor = $resultInstructor->fetch_assoc();
        $instructorName = $rowInstructor['name'];

        // Pagkatapos ng query, i-prepare ang response data
        $response = array(
            'success' => true,
            'ratings' => $ratingsData, // Array ng ratings mula sa database
            'tse' => $totalSuperiorsEvaluated,
            'instructorName' => $instructorName // Pangalan ng instructor
        );

        echo json_encode($response);
    } else {
        // Failed to execute query
        echo json_encode(array('success' => false, 'message' => 'Failed to fetch ratings'));
    }
} else {
    // Invalid request
    echo json_encode(array('success' => false, 'message' => 'Invalid request'));
}
    }
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_system_settings(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k => $v){
			if(!is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if($_FILES['cover']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'],'../assets/uploads/'. $fname);
			$data .= ", cover_img = '$fname' ";

		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set $data where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if($save){
			foreach($_POST as $k => $v){
				if(!is_numeric($k)){
					$_SESSION['system'][$k] = $v;
				}
			}
			if($_FILES['cover']['tmp_name'] != ''){
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image(){
		extract($_FILES['file']);
		if(!empty($tmp_name)){
			$fname = strtotime(date("Y-m-d H:i"))."_".(str_replace(" ","-",$name));
			$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path =explode('/',$_SERVER['PHP_SELF']);
			$currentPath = '/'.$path[1]; 
			if($move){
				return $protocol.'://'.$hostName.$currentPath.'/assets/uploads/'.$fname;
			}
		}
	}
	function save_subject(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM subject_list where code = '$code' and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO subject_list set $data");
		}else{
			$save = $this->db->query("UPDATE subject_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_subject(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM subject_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_class(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM class_list where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		if(isset($user_ids)){
			$data .= ", user_ids='".implode(',',$user_ids)."' ";
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO class_list set $data");
		}else{
			$save = $this->db->query("UPDATE class_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_class(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM class_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_academic() {
        extract($_POST);
    
        // Check if academic_period is set
        if (!isset($academic_period)) {
            return 'Missing academic period';
        }
    
        // Split academic_period into year, end_year, and semester
        list($year, $end_year, $semester) = explode('-', $academic_period);
    
        // Check if year, end_year, and semester are set and in the correct format
        if (!$year || !$end_year || !$semester || !is_numeric($year) || !is_numeric($end_year) || !is_numeric($semester)) {
            return 'Invalid academic period';
        }
    
        // Check if academic year and semester already exist in the database
        $chk = $this->db->query("SELECT * FROM academic_list WHERE year = '$year-$end_year' AND semester = '$semester'")->num_rows;
        if ($chk > 0) {
            return 2;
        }
    
        // Insert the new academic year into the database
        $save = $this->db->query("INSERT INTO academic_list (year, semester, is_default, status) VALUES ('$year-$end_year', '$semester', 0, 2)");
    
        // Check if insertion was successful
        if ($save) {
            return 1; // Return 1 if insertion was successful
        } else {
            return 'Error saving academic year'; // Return error message if there was a problem with the insertion
        }
    }
	function delete_academic(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM academic_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_criteria(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM criteria_list where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		
		if(empty($id)){
			$lastOrder= $this->db->query("SELECT * FROM criteria_list order by abs(order_by) desc limit 1");
		$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
		$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO criteria_list set $data");
		}else{
			$save = $this->db->query("UPDATE criteria_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_criteria(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM criteria_list where id = $id");
		if($delete){
			return 1;
		}
	}
    function save_criteria_superior(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','user_ids')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$chk = $this->db->query("SELECT * FROM criteria_list_superior where (".str_replace(",",'and',$data).") and id != '{$id}' ")->num_rows;
		if($chk > 0){
			return 2;
		}
		
		if(empty($id)){
			$lastOrder= $this->db->query("SELECT * FROM criteria_list_superior order by abs(order_by) desc limit 1");
		$lastOrder = $lastOrder->num_rows > 0 ? $lastOrder->fetch_array()['order_by'] + 1 : 0;
		$data .= ", order_by='$lastOrder' ";
			$save = $this->db->query("INSERT INTO criteria_list_superior set $data");
		}else{
			$save = $this->db->query("UPDATE criteria_list_superior set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	
    function delete_criteria_superior(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM criteria_list_superior where id = $id");
		if($delete){
			return 1;
		}
	}
    function get_feedbacks() {
        if (isset($_GET['faculty_id'])) {
            $facultyId = $_GET['faculty_id'];
            $feedbacks = [];
    
            // Gumamit ng prepared statement para maiwasan ang SQL injection
            $stmt = $this->db->prepare("SELECT ec.comments, fl.firstname, fl.lastname 
                                        FROM evaluation_comments ec
                                        JOIN faculty_list fl ON ec.faculty_id = fl.id
                                        WHERE ec.faculty_id = ?");
            $stmt->bind_param("i", $facultyId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $comment = $row['comments'];
                    $positiveCount = 0;
                    $negativeCount = 0;
                    $neutralCount = 0;
    
                    // Gamitin ang preg_split para i-split ang mga salita, kasama na ang mga special characters
                    $words = preg_split('/[^\p{L}\p{N}\']+/u', $comment, -1, PREG_SPLIT_NO_EMPTY);
    
                    foreach ($words as $word) {
                        $lowercaseWord = strtolower($word);
    
                        // Check kung ang salita ay nasa listahan ng existing sentiment terms
                        $termType = $this->isSentimentTerm($lowercaseWord);
    
                        if ($termType) {
                            // Increment ang appropriate count base sa term type
                            if ($termType === 'Positive') {
                                $positiveCount++;
                            } elseif ($termType === 'Negative') {
                                $negativeCount++;
                            } elseif ($termType === 'Neutral') {
                                $neutralCount++;
                            }
                        }
                    }
    
                    // I-set ang mga counts sa feedback row
                    $row['positive_count'] = $positiveCount;
                    $row['negative_count'] = $negativeCount;
                    $row['neutral_count'] = $neutralCount;
    
                    // I-push ang feedback na may mga counts sa $feedbacks array
                    $feedbacks[] = $row;
                }
                $stmt->close();
                return json_encode($feedbacks);
            } else {
                $stmt->close();
                return json_encode([]);
            }
        } else {
            return json_encode([]);
        }
    }
    
    function isSentimentTerm($word) {
        global $conn; // Gamitin ang global connection variable
    
        // Query para kunin ang mga positive, negative, at neutral terms mula sa database
        $query = "SELECT term_type FROM sentiment_terms WHERE term = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $word);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($termType);
            $stmt->fetch();
            $stmt->close();
            return $termType; // I-return ang term type (positive, negative, neutral)
        }
    
        return false; // Kung hindi matatagpuan ang $word sa existing terms, i-return ang false
    }
    
    function get_feedbacks_superior() {
        if (isset($_GET['faculty_id'])) {
            $facultyId = $_GET['faculty_id'];
            $feedbacks = [];
    
            // Gumamit ng prepared statement para maiwasan ang SQL injection
            $stmt = $this->db->prepare("SELECT ec.comments, fl.firstname, fl.lastname 
                                        FROM evaluation_comments_superior ec
                                        JOIN faculty_list fl ON ec.faculty_id = fl.id
                                        WHERE ec.faculty_id = ?");
            $stmt->bind_param("i", $facultyId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $comment = $row['comments'];
                    $positiveCount = 0;
                    $negativeCount = 0;
                    $neutralCount = 0;
    
                    // Gamitin ang preg_split para i-split ang mga salita, kasama na ang mga special characters
                    $words = preg_split('/[^\p{L}\p{N}\']+/u', $comment, -1, PREG_SPLIT_NO_EMPTY);
    
                    foreach ($words as $word) {
                        $lowercaseWord = strtolower($word);
    
                        // Check kung ang salita ay nasa listahan ng existing sentiment terms
                        $termType = $this->isSentimentTerm($lowercaseWord);
    
                        if ($termType) {
                            // Increment ang appropriate count base sa term type
                            if ($termType === 'Positive') {
                                $positiveCount++;
                            } elseif ($termType === 'Negative') {
                                $negativeCount++;
                            } elseif ($termType === 'Neutral') {
                                $neutralCount++;
                            }
                        }
                    }
    
                    // I-set ang mga counts sa feedback row
                    $row['positive_count'] = $positiveCount;
                    $row['negative_count'] = $negativeCount;
                    $row['neutral_count'] = $neutralCount;
    
                    // I-push ang feedback na may mga counts sa $feedbacks array
                    $feedbacks[] = $row;
                }
                $stmt->close();
                return json_encode($feedbacks);
            } else {
                $stmt->close();
                return json_encode([]);
            }
        } else {
            return json_encode([]);
        }
    }
    
    function isSentimentTermSuperior($word) {
        global $conn; // Gamitin ang global connection variable
    
        // Query para kunin ang mga positive, negative, at neutral terms mula sa database
        $query = "SELECT term_type FROM sentiment_terms WHERE term = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $word);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($termType);
            $stmt->fetch();
            $stmt->close();
            return $termType; // I-return ang term type (positive, negative, neutral)
        }
    
        return false; // Kung hindi matatagpuan ang $word sa existing terms, i-return ang false
    }
    public function get_sentiment_terms_faculty_students($faculty_id) {
        global $conn;
    
        $result = array(
            'positive' => array(),
            'negative' => array(),
            'neutral' => array()
        );
    
        // Kumuha ng lahat ng comments ng napiling faculty member
        $comments_query = $conn->query("SELECT comments FROM evaluation_comments WHERE faculty_id = $faculty_id");
        $comments = array();
    
        while ($row = $comments_query->fetch_assoc()) {
            // Collect all comments into an array
            $comments[] = $row['comments'];
        }
    
        // Kung may comments, suriin ang mga ito para sa mga terms na positive, negative, at neutral
        if (!empty($comments)) {
            $allComments = implode(' ', $comments); // Combine all comments into a single string
    
            // Kunin ang positive terms na present sa comments
            $positive_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Positive'");
            while ($row = $positive_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['positive'][] = $term;
                }
            }
    
            // Kunin ang negative terms na present sa comments
            $negative_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Negative'");
            while ($row = $negative_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['negative'][] = $term;
                }
            }
    
            // Kunin ang neutral terms na present sa comments
            $neutral_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Neutral'");
            while ($row = $neutral_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['neutral'][] = $term;
                }
            }
        }
    
        // I-return ang result array bilang JSON
        return json_encode($result);
    }
    public function get_sentiment_terms($faculty_id) {
        global $conn;
    
        $result = array(
            'positive' => array(),
            'negative' => array(),
            'neutral' => array()
        );
    
        // Kumuha ng lahat ng comments ng napiling faculty member
        $comments_query = $conn->query("SELECT comments FROM evaluation_comments WHERE faculty_id = $faculty_id");
        $comments = array();
    
        while ($row = $comments_query->fetch_assoc()) {
            // Collect all comments into an array
            $comments[] = $row['comments'];
        }
    
        // Kung may comments, suriin ang mga ito para sa mga terms na positive, negative, at neutral
        if (!empty($comments)) {
            $allComments = implode(' ', $comments); // Combine all comments into a single string
    
            // Kunin ang positive terms na present sa comments
            $positive_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Positive'");
            while ($row = $positive_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['positive'][] = $term;
                }
            }
    
            // Kunin ang negative terms na present sa comments
            $negative_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Negative'");
            while ($row = $negative_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['negative'][] = $term;
                }
            }
    
            // Kunin ang neutral terms na present sa comments
            $neutral_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Neutral'");
            while ($row = $neutral_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['neutral'][] = $term;
                }
            }
        }
    
        // I-return ang result array bilang JSON
        return json_encode($result);
    }
    public function get_sentiment_terms_superior($faculty_id) {
        global $conn;
    
        $result = array(
            'positive' => array(),
            'negative' => array(),
            'neutral' => array()
        );
    
        // Kumuha ng lahat ng comments ng napiling faculty member
        $comments_query = $conn->query("SELECT comments FROM evaluation_comments_superior WHERE faculty_id = $faculty_id");
        $comments = array();
    
        while ($row = $comments_query->fetch_assoc()) {
            // Collect all comments into an array
            $comments[] = $row['comments'];
        }
    
        // Kung may comments, suriin ang mga ito para sa mga terms na positive, negative, at neutral
        if (!empty($comments)) {
            $allComments = implode(' ', $comments); // Combine all comments into a single string
    
            // Kunin ang positive terms na present sa comments
            $positive_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Positive'");
            while ($row = $positive_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['positive'][] = $term;
                }
            }
    
            // Kunin ang negative terms na present sa comments
            $negative_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Negative'");
            while ($row = $negative_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['negative'][] = $term;
                }
            }
    
            // Kunin ang neutral terms na present sa comments
            $neutral_terms_query = $conn->query("SELECT term FROM sentiment_terms WHERE term_type = 'Neutral'");
            while ($row = $neutral_terms_query->fetch_assoc()) {
                $term = $row['term'];
                if (stripos($allComments, $term) !== false) {
                    $result['neutral'][] = $term;
                }
            }
        }
    
        // I-return ang result array bilang JSON
        return json_encode($result);
    }

    function save_questions() {
        extract($_POST);
        $question = stripslashes($question);
    
        if (empty($id)) {
            // Check if the question already exists
            $stmt = $this->db->prepare("SELECT * FROM questions_list WHERE questions = ?");
            $stmt->bind_param("s", $question);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            if ($result->num_rows > 0) {
                return 2; // Question already exists
            }
    
            // Insert new question
            $stmt = $this->db->prepare("INSERT INTO questions_list (questions) VALUES (?)");
            $stmt->bind_param("s", $question);
            $query_success = $stmt->execute();
            $stmt->close();
    
        } else {
            // Update existing question
            // Check if the updated question already exists (excluding itself)
            $stmt = $this->db->prepare("SELECT * FROM questions_list WHERE questions = ? AND id != ?");
            $stmt->bind_param("si", $question, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            if ($result->num_rows > 0) {
                return 2; // Question already exists
            }
    
            // Update the question
            $stmt = $this->db->prepare("UPDATE questions_list SET questions = ? WHERE id = ?");
            $stmt->bind_param("si", $question, $id);
            $query_success = $stmt->execute();
            $stmt->close();
        }
    
        if ($query_success) {
            return 1; // Successfully saved
        } else {
            return 0; // Error
        }
    }
    function save_questions_superior() {
        extract($_POST);
    
        // Remove backslashes added by real_escape_string
        $question = stripslashes($question);
    
        // Prepare and execute query to check if the question exists
        if (empty($id)) {
            $stmt = $this->db->prepare("SELECT * FROM questions_list_superior WHERE questions = ?");
            $stmt->bind_param("s", $question);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            if ($result->num_rows > 0) {
                return 2; // Question already exists
            }
    
            // Insert new question
            $stmt = $this->db->prepare("INSERT INTO questions_list_superior (questions) VALUES (?)");
            $stmt->bind_param("s", $question);
            $query_success = $stmt->execute();
            $stmt->close();
        } else {
            // Update existing question
            // Check if the updated question already exists (excluding itself)
            $stmt = $this->db->prepare("SELECT * FROM questions_list_superior WHERE questions = ? AND id != ?");
            $stmt->bind_param("si", $question, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            if ($result->num_rows > 0) {
                return 2; // Question already exists
            }
    
            // Update the question
            $stmt = $this->db->prepare("UPDATE questions_list_superior SET questions = ? WHERE id = ?");
            $stmt->bind_param("si", $question, $id);
            $query_success = $stmt->execute();
            $stmt->close();
        }
    
        if ($query_success) {
            return 1; // Successfully saved
        } else {
            return 0; // Error
        }
    }
    function get_question(){
        extract($_POST);
        if (!isset($id) || empty($id)) {
            return json_encode(array("error" => "No question ID provided."));
        }
    
        // Attempt to fetch question from the database
        $query = $this->db->query("SELECT * FROM questions_list where id = $id");
        if (!$query) {
            return json_encode(array("error" => "Database query failed."));
        }
    
        // Check if question exists
        if ($query->num_rows == 0) {
            return json_encode(array("error" => "Question not found."));
        }
    
        // Fetch question data
        $row = $query->fetch_assoc();
        // Encode question data to JSON format
        $json_data = json_encode($row);
        if (!$json_data) {
            return json_encode(array("error" => "JSON encoding failed."));
        }
    
        // Return JSON response
        return $json_data;
    }
    function get_question_superior(){
        extract($_POST);
        if (!isset($id) || empty($id)) {
            return json_encode(array("error" => "No question ID provided."));
        }
    
        // Attempt to fetch question from the database
        $query = $this->db->query("SELECT * FROM questions_list_superior where id = $id");
        if (!$query) {
            return json_encode(array("error" => "Database query failed."));
        }
    
        // Check if question exists
        if ($query->num_rows == 0) {
            return json_encode(array("error" => "Question not found."));
        }
    
        // Fetch question data
        $row = $query->fetch_assoc();
        // Encode question data to JSON format
        $json_data = json_encode($row);
        if (!$json_data) {
            return json_encode(array("error" => "JSON encoding failed."));
        }
    
        // Return JSON response
        return $json_data;
    }
    function delete_questions(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM questions_list where id = $id");
        if($delete){
            return 1;
        }
    }
    function delete_questions_superior(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM questions_list_superior where id = $id");
        if($delete){
            return 1;
        }
    }
    function make_default(){
        extract($_POST);
    
        // Get the academic year details
        $academic_year = $this->db->query("SELECT * FROM academic_list WHERE id = $id")->fetch_assoc();
        $year = intval($academic_year['year']);
        $semester = intval($academic_year['semester']);
    
        // Get the current year
        $current_year = intval(date('Y'));
    
        // Calculate the academic year range based on the retrieved year and semester
        $academic_year_start = $year;
        $academic_year_end = $year + 1;
        $academic_year_range = $academic_year_start . '-' . $academic_year_end;
    
        // Calculate the current academic year based on the current year
        $current_academic_year_start = $current_year;
        $current_academic_year_end = $current_year + 1;
    
        // Check if the selected academic year matches the current academic year or extends into the current year
        if ($academic_year_start <= $current_year && $academic_year_end >= $current_year) {
            // Set is_default to 0 for all academic years
            $update_all = $this->db->query("UPDATE academic_list SET is_default = 0");
    
            if ($update_all) {
                // Set is_default to 1 for the selected academic year
                $update_selected = $this->db->query("UPDATE academic_list SET is_default = 1 WHERE id = $id");
    
                // Set status to 2 for all academic years except the selected one
                $close_others = $this->db->query("UPDATE academic_list SET status = 2 WHERE id != $id");
                
                $close_restriction = $this->db->query("UPDATE academic_list SET restriction = 0 WHERE id != $id");
                // Set status to 1 for the selected academic year
                $activate_selected = $this->db->query("UPDATE academic_list SET status = 2 WHERE id = $id");
    
                // Retrieve the details of the selected academic year
                $qry = $this->db->query("SELECT * FROM academic_list WHERE id = $id")->fetch_assoc();
    
                // Check if all queries executed successfully
                if ($update_selected && $close_others && $activate_selected && $close_restriction) {
                    // Store academic year details in session excluding numeric keys
                    foreach ($qry as $k => $v) {
                        if (!is_numeric($k)) {
                            $_SESSION['academic'][$k] = $v;
                        }
                    }
                    return 1; // Return success response
                }
            }
        } else {
            return 0; // Return failure response if academic year is not current
        }
    }
    public function close_evaluation()
    {
        extract($_POST);
        $sql = "SELECT * FROM academic_list WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $academic = $result->fetch_assoc();

            // Check if the academic year is the default one (is_default = 1)
            if ($academic['is_default'] == 1) {
                // Update the status to 'Closed' (status = 2)
                $updateSql = "UPDATE academic_list SET status = 2 WHERE id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bind_param("i", $id);
                $updateStmt->execute();

                if ($updateStmt->affected_rows > 0) {
                    return 1; // Success: Evaluation closed
                } else {
                    return 0; // Error: Update failed
                }
            } else {
                return 0; // Error: Not the default academic year
            }
        } else {
            return 0; // Error: Academic year not found
        }
    }
    
    public function start_evaluation()
    {
        extract($_POST);
        $sql = "SELECT * FROM academic_list WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $academic = $result->fetch_assoc();

            // Check if the academic year is the default one (is_default = 1)
            if ($academic['is_default'] == 1) {
                // Update the status to 'Closed' (status = 2)
                $updateSql = "UPDATE academic_list SET status = 1 WHERE id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bind_param("i", $id);
                $updateStmt->execute();

                if ($updateStmt->affected_rows > 0) {
                    return 1; // Success: Evaluation closed
                } else {
                    return 0; // Error: Update failed
                }
            } else {
                return 2; // Error: Not the default academic year
            }
        } else {
            return 0; // Error: Academic year not found
        }
    }
    public function restrict()
    {
        extract($_POST);
        $sql = "SELECT * FROM academic_list WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $academic = $result->fetch_assoc();

            // Check if the academic year is the default one (is_default = 1)
            if ($academic['is_default'] == 1) {
                // Update the status to 'Closed' (status = 2)
                $updateSql = "UPDATE academic_list SET restriction = 0 WHERE id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bind_param("i", $id);
                $updateStmt->execute();

                if ($updateStmt->affected_rows > 0) {
                    return 1; // Success: Evaluation closed
                } else {
                    return 0; // Error: Update failed
                }
            } else {
                return 0; // Error: Not the default academic year
            }
        } else {
            return 0; // Error: Academic year not found
        }
    }
    public function unrestrict()
    {
        extract($_POST);
        $sql = "SELECT * FROM academic_list WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $academic = $result->fetch_assoc();

            // Check if the academic year is the default one (is_default = 1)
            if ($academic['is_default'] == 1) {
                // Update the status to 'Closed' (status = 2)
                $updateSql = "UPDATE academic_list SET restriction = 1 WHERE id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bind_param("i", $id);
                $updateStmt->execute();

                if ($updateStmt->affected_rows > 0) {
                    return 1; // Success: Evaluation closed
                } else {
                    return 0; // Error: Update failed
                }
            } else {
                return 2; // Error: Not the default academic year
            }
        } else {
            return 0; // Error: Academic year not found
        }
    }
	function save_criteria_order(){
		extract($_POST);
		$data = "";
		foreach($criteria_id as $k => $v){
			$update[] = $this->db->query("UPDATE criteria_list set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}
    function save_criteria_order_superior(){
		extract($_POST);
		$data = "";
		foreach($criteria_id as $k => $v){
			$update[] = $this->db->query("UPDATE criteria_list_superior set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}

	function save_question() {
        global $conn;
    
        // Extract POST data
        extract($_POST);
    
        // Validate required fields
        if (empty($academic_id) || empty($question) || empty($criteria_id)) {
            echo 0; // Return error response if required fields are empty
            return;
        }
    
        // Check if the same question already exists in any criteria
        $checkQuery = $conn->prepare("SELECT id FROM question_list WHERE question = ? AND academic_id = ?");
        $checkQuery->bind_param('si', $question,$academic_id);
        $checkQuery->execute();
        $checkResult = $checkQuery->get_result();
    
        if ($checkResult->num_rows > 0) {
            echo 2; // Return error response if the question already exists in any criteria
            return;
        }
    
        // Determine the order_by value
        $lastOrder = 0;
        $lastOrderQuery = $conn->prepare("SELECT MAX(order_by) AS max_order FROM question_list WHERE academic_id = ?");
        $lastOrderQuery->bind_param('i', $academic_id);
        $lastOrderQuery->execute();
        $lastOrderResult = $lastOrderQuery->get_result()->fetch_assoc();
        $lastOrder = $lastOrderResult['max_order'] ? $lastOrderResult['max_order'] + 1 : 1;
    
        // Prepare data for insertion or update
        $stmt = null;
        if (empty($id)) {
            // INSERT query
            $stmt = $conn->prepare("INSERT INTO question_list (academic_id, question, criteria_id, order_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('issi', $academic_id, $question, $criteria_id, $lastOrder);
        } else {
            // UPDATE query
            $stmt = $conn->prepare("UPDATE question_list SET academic_id = ?, question = ?, criteria_id = ?, order_by = ? WHERE id = ?");
            $stmt->bind_param('issii', $academic_id, $question, $criteria_id, $lastOrder, $id);
        }
    
        // Execute the prepared statement
        $result = $stmt->execute();
    
        // Check if query was successful
        if ($result) {
            echo 1; // Return success response
        } else {
            echo 0; // Return error response
        }
    
        // Close the statement
        $stmt->close();
    }
    function delete_question(){
        global $conn; // Siguraduhin na ang $conn ay global variable at tama ang pag-access sa database
    
        if(isset($_POST['id'])) {
            $id = $_POST['id'];
            $delete = $conn->query("DELETE FROM question_list WHERE id = $id");
    
            if($delete) {
                echo 1; // Ito ang magiging response sa AJAX kung matagumpay ang pag-delete
            } else {
                echo "Error deleting question: " . $conn->error; // Ito ang error message kung may issue sa pag-delete
            }
        } else {
            echo "Invalid request. No ID specified."; // Ito ang error message kung walang ID na naipasa sa POST request
        }
    }
    function delete_question_superior(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM question_list_superior where id = $id");
		if($delete){
			return 1;
		}
	}
    function save_question_superior() {
        global $conn;
    
        // Extract POST data
        extract($_POST);
    
        // Validate required fields
        if (empty($academic_id) || empty($question) || empty($criteria_id)) {
            echo 0; // Return error response if required fields are empty
            return;
        }
    
        // Check if the same question already exists in any criteria
        $checkQuery = $conn->prepare("SELECT id FROM question_list_superior WHERE question = ? AND academic_id = ?");
        $checkQuery->bind_param('si', $question,$academic_id);
        $checkQuery->execute();
        $checkResult = $checkQuery->get_result();
    
        if ($checkResult->num_rows > 0) {
            echo 2; // Return error response if the question already exists in any criteria
            return;
        }
    
        // Determine the order_by value
        $lastOrder = 0;
        $lastOrderQuery = $conn->prepare("SELECT MAX(order_by) AS max_order FROM question_list_superior WHERE academic_id = ?");
        $lastOrderQuery->bind_param('i', $academic_id);
        $lastOrderQuery->execute();
        $lastOrderResult = $lastOrderQuery->get_result()->fetch_assoc();
        $lastOrder = $lastOrderResult['max_order'] ? $lastOrderResult['max_order'] + 1 : 1;
    
        // Prepare data for insertion or update
        $stmt = null;
        if (empty($id)) {
            // INSERT query
            $stmt = $conn->prepare("INSERT INTO question_list_superior (academic_id, question, criteria_id, order_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('issi', $academic_id, $question, $criteria_id, $lastOrder);
        } else {
            // UPDATE query
            $stmt = $conn->prepare("UPDATE question_list_superior SET academic_id = ?, question = ?, criteria_id = ?, order_by = ? WHERE id = ?");
            $stmt->bind_param('issii', $academic_id, $question, $criteria_id, $lastOrder, $id);
        }
    
        // Execute the prepared statement
        $result = $stmt->execute();
    
        // Check if query was successful
        if ($result) {
            echo 1; // Return success response
        } else {
            echo 0; // Return error response
        }
    
        // Close the statement
        $stmt->close();
    }
    function toggleEvaluationStatus($id) {
        global $conn;
        if(empty($id)){
            return 2;
        }
        $query = $conn->query("SELECT * FROM academic_list WHERE id = $id");
        if($query->num_rows <= 0){
            return 3;
        }
        $status = $query->fetch_assoc()['status'];
        $status = $status == 0 ? 1 : ($status == 1 ? 2 : 0);
        $update = $conn->query("UPDATE academic_list SET is_default = '$status' AND status = '$status' WHERE id = $id");
        if($update){
            return 1;
        }else{
            return 0;
        }
    }
	
	function save_question_order(){
		extract($_POST);
		$data = "";
		foreach($qid as $k => $v){
			$update[] = $this->db->query("UPDATE question_list set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}
    function save_question_order_superior(){
		extract($_POST);
		$data = "";
		foreach($qid as $k => $v){
			$update[] = $this->db->query("UPDATE question_list_superior set order_by = $k where id = $v");
		}
		if(isset($update) && count($update)){
			return 1;
		}
	}
	function save_faculty(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM faculty_list where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		$check = $this->db->query("SELECT * FROM faculty_list where school_id ='$school_id' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 3;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO faculty_list set $data");
		}else{
			$save = $this->db->query("UPDATE faculty_list set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function delete_faculty(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM faculty_list where id = ".$id);
		if($delete)
			return 1;
	}
    function save_superior(){
		extract($_POST);

    // Build data string for INSERT or UPDATE
    $data = "";
    foreach ($_POST as $k => $v) {
        if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
            if (empty($data)) {
                $data .= " $k = '$v' ";
            } else {
                $data .= ", $k = '$v' ";
            }
        }
    }
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		  // Check if email already exists for update (excluding current ID)
          if (!empty($id)) {
            $email_check_query = "SELECT * FROM superior_list WHERE email = '$email' AND id != $id";
        } else {
            $email_check_query = "SELECT * FROM superior_list WHERE email = '$email'";
        }
        $check_email_result = $this->db->query($email_check_query);
        if ($check_email_result->num_rows > 0) {
            return 2; // Email already exists
        }
    
        // Upload avatar if file is provided
        if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", avatar = '$fname' ";
        }
    
        // Insert or update record in the database
        if (empty($id)) {
            // Insert new record
            $save_query = "INSERT INTO superior_list SET $data";
        } else {
            // Update existing record
            $save_query = "UPDATE superior_list SET $data WHERE id = $id";
        }
    
        $save_result = $this->db->query($save_query);
    
        if ($save_result) {
            return 1; // Successfully saved
        } else {
            return 0; // Failed to save
        }
    }
    function delete_superior(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM superior_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_student(){
        extract($_POST);
        $data = "";
    
        // Build the data string for SQL query
        foreach($_POST as $k => $v){
            if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
                if(empty($data)){
                    $data .= " $k='$v' ";
                } else {
                    $data .= ", $k='$v' ";
                }
            }
        }
    
        // Encrypt the password if provided
        if(!empty($password)){
            $data .= ", password=md5('$password') ";
        }
    
        // Handle file upload if an image is provided
        if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
            $data .= ", avatar = '$fname' ";
        }
    
        // Perform INSERT or UPDATE based on whether ID is provided
        if(empty($id)){
            $save = $this->db->query("INSERT INTO student_list SET $data");
        } else {
            $save = $this->db->query("UPDATE student_list SET $data WHERE id = $id");
        }
    
        if($save){
            return 1; // Success
        } else {
            return 0; // Failed to save
        }
    }
    function signup_account(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
                if(empty($data)){
                    $data .= " $k='$v' ";
                }else{
                    $data .= ", $k='$v' ";
                }
            }
        }
        if(!empty($password)){
            $data .= ", password=md5('$password') ";
        }
    
        $check = $this->db->query("SELECT * FROM account_request where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
        if($check > 0){
            return 2;
        }
        if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
            $data .= ", avatar = '$fname' ";
        }
        if(empty($id)){
            $save = $this->db->query("INSERT INTO account_request set $data");
        }else{
            $save = $this->db->query("UPDATE account_request set $data where id = $id");
        }
        if($save){
            return 1;
        }
    }
    
    function add_student(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
					$data .= ", password=md5('$password') ";

		}
		$check = $this->db->query("SELECT * FROM student_list where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO student_list set $data");
		}else{
		}

		if($save){
			return 1;
		}
	}
	function delete_student(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM student_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_task(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'description')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_list set $data");
		}else{
			$save = $this->db->query("UPDATE task_list set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}
	function delete_task(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_progress(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'progress')
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!isset($is_complete))
			$data .= ", is_complete=0 ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO task_progress set $data");
		}else{
			$save = $this->db->query("UPDATE task_progress set $data where id = $id");
		}
		if($save){
		if(!isset($is_complete))
			$this->db->query("UPDATE task_list set status = 1 where id = $task_id ");
		else
			$this->db->query("UPDATE task_list set status = 2 where id = $task_id ");
			return 1;
		}
	}
	function delete_progress(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_progress where id = $id");
		if($delete){
			return 1;
		}
	}
	function save_restriction(){
		extract($_POST);
		$filtered = implode(",",array_filter($rid));
		if(!empty($filtered))
			$this->db->query("DELETE FROM restriction_list where id not in ($filtered) and academic_id = $academic_id");
		else
			$this->db->query("DELETE FROM restriction_list where  academic_id = $academic_id");
		foreach($rid as $k => $v){
			$data = " academic_id = $academic_id ";
			$data .= ", faculty_id = {$faculty_id[$k]} ";
			$data .= ", class_id = {$class_id[$k]} ";
			$data .= ", subject_id = {$subject_id[$k]} ";
			if(empty($v)){
				$save[] = $this->db->query("INSERT INTO restriction_list set $data ");
			}else{
				$save[] = $this->db->query("UPDATE restriction_list set $data where id = $v ");
			}
		}
			return 1;
	}
    public function get_evaluation_status() {
        global $conn;
        // Check for required session data (adjust as needed)
        if (isset($_SESSION['academic']['year']) && isset($_SESSION['academic']['semester'])) {
            $year = $_SESSION['academic']['year'];
            $semester = $_SESSION['academic']['semester'];

            try {
                // Prepare and execute query to fetch evaluation status
                $query = "SELECT status FROM evaluation_status WHERE academic_year = :year AND semester = :semester";
                $stmt = $this->$conn->prepare($query);
                $stmt->bindParam(':year', $year);
                $stmt->bindParam(':semester', $semester);
                $stmt->execute();

                // Fetch the status from the query result
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return $result['status']; // Return the status value
                } else {
                    return 0; // Default status if no record found
                }
            } catch (PDOException $e) {
                return 'Error fetching evaluation status: ' . $e->getMessage();
            }
        } else {
            return 'Academic year or semester not set in session.';
        }
    }
	function save_evaluation() {
        extract($_POST);
        
        // Build the data string for evaluation_list table
        $data = " student_id = {$_SESSION['login_id']} ";
        $data .= ", academic_id = $academic_id ";
        $data .= ", subject_id = $subject_id ";
        $data .= ", class_id = $class_id ";
        $data .= ", restriction_id = $restriction_id ";
        $data .= ", faculty_id = $faculty_id ";
        $data .= ", comments = '$comments' "; // Add comments to the data
        
        // Insert data into evaluation_list table
        $save = $this->db->query("INSERT INTO evaluation_list SET $data");
        
        if ($save) {
            $eid = $this->db->insert_id;
        
            // Loop through each question to save answers
            foreach ($qid as $k => $v) {
                $data = " evaluation_id = $eid ";
                $data .= ", question_id = $v ";
                $data .= ", rate = {$rate[$v]} ";
                $ins[] = $this->db->query("INSERT INTO evaluation_answers SET $data ");
            }
            
            // Insert comments into evaluation_comments table
            $commentData = " evaluation_id = $eid ";
            $commentData .= ", student_id = {$_SESSION['login_id']} ";
            $commentData .= ", faculty_id = $faculty_id ";
            $commentData .= ", section_id = $class_id "; // Assuming section_id corresponds to class_id
            $commentData .= ", subject_id = $subject_id ";
            $commentData .= ", comments = '$comments' ";
            $commentData .= ", created_at = NOW()"; // Add timestamp for created_at
            $commentInsert = $this->db->query("INSERT INTO evaluation_comments SET $commentData");
        
            if (isset($ins) && $commentInsert) {
                return 1; // Success
            }
        }
        
        return 0; // Error
    }
    function save_evaluation_superior() {
        extract($_POST);
        // Build the data string for evaluation_list table
        $data = " superior_id = {$_SESSION['login_id']} ";
        $data .= ", academic_id = $academic_id ";
        $data .= ", faculty_id = $faculty_id ";
        $data .= ", comments = '$comments' "; // Add comments to the data
        
        // Insert data into evaluation_list table
        $save = $this->db->query("INSERT INTO evaluation_list_superior SET $data");
        if (!$save) {
            die('Invalid query: ' . mysqli_error($this->db));
        }

        if ($save) {
            $eid = $this->db->insert_id;
        
            // Loop through each question to save answers
            foreach ($qid as $k => $v) {
                $data = " evaluation_id = $eid ";
                $data .= ", question_id = $v ";
                $data .= ", rate = {$rate[$v]} ";
                $ins[] = $this->db->query("INSERT INTO evaluation_answers_superior SET $data ");
            }
            
            // Insert comments into evaluation_comments table
            $commentData = " evaluation_id = $eid ";
            $commentData .= ", superior_id = {$_SESSION['login_id']} ";
            $commentData .= ", faculty_id = $faculty_id ";
            $commentData .= ", comments = '$comments' ";
            $commentData .= ", created_at = NOW()"; // Add timestamp for created_at
            $commentInsert = $this->db->query("INSERT INTO evaluation_comments_superior SET $commentData");
        
            if (isset($ins) && $commentInsert) {
                return 1; // Success
            }
        }
        
        return 0; // Error
    }
    
    public function certificate() {
        // Define the path to the certificate.php file in the student folder
        $certificate_file = 'certificate.php';

        // Check if the file exists
        if (file_exists($certificate_file)) {
            // Read the contents of the certificate file
            $certificate_content = file_get_contents($certificate_file);

            // Return the certificate content
            return $certificate_content;
        } else {
            // If the file does not exist, return an error message or handle as needed
            return 'Certificate file not found.';
        }
    }
	function get_class(){
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT c.id,concat(c.curriculum,' ',c.level,' - ',c.section) as 
        class,s.id as sid,concat(s.code,' - ',s.subject) as subj FROM restriction_list r 
        inner join class_list c on c.id = r.class_id inner join subject_list s on s.id = r.subject_id 
        where r.faculty_id = {$fid} and academic_id = {$_SESSION['academic']['id']} ");
		while($row= $get->fetch_assoc()){
			$data[]=$row;
		}
		return json_encode($data);

	}
    
	function get_report() {
        extract($_POST);
        $data = array();
    
        // Kunin ang ID ng guro mula sa POST data
        $faculty_id = isset($faculty_id) ? $faculty_id : 0;
    
        // Gawin ang query para lamang sa guro na napili sa dropdown at sa mga asignaturang kanyang tinuturuan
        $get = $this->db->query("SELECT * FROM evaluation_answers WHERE evaluation_id IN 
            (SELECT evaluation_id FROM evaluation_list WHERE academic_id = {$_SESSION['academic']['id']} 
            AND faculty_id = $faculty_id AND subject_id = $subject_id AND class_id = $class_id)");
    
        $answered = $this->db->query("SELECT * FROM evaluation_list WHERE
            academic_id = {$_SESSION['academic']['id']} AND faculty_id = $faculty_id
            AND subject_id = $subject_id AND class_id = $class_id");
    
        $rate = array();
        while ($row = $get->fetch_assoc()) {
            if (!isset($rate[$row['question_id']][$row['rate']])) {
                $rate[$row['question_id']][$row['rate']] = 0;
            }
            $rate[$row['question_id']][$row['rate']] += 1;
        }
    
        $ta = $answered->num_rows;
        $r = array();
        foreach ($rate as $qk => $qv) {
            foreach ($qv as $rk => $rv) {
                $r[$qk][$rk] = $rate[$qk][$rk]; // Bilang ng mga nag-rate sa bawat rating
            }
        }
    
        $data['tse'] = $ta;
        $data['data'] = $r;
        return json_encode($data);
    }
    function get_report_superior() {
        // Extract faculty_id from POST data
        $fid = isset($_POST['fid']) ? $_POST['fid'] : 0;
        
        // Initialize data array
        $data = array();
        
        // Query to count evaluators for the selected faculty member
        $answered = $this->db->query("SELECT COUNT(*) AS num_evaluators FROM evaluation_list_superior 
            WHERE academic_id = {$_SESSION['academic']['id']} AND faculty_id = $fid");
        
        // Process result of count query
        if ($answered && $answered->num_rows > 0) {
            $row = $answered->fetch_assoc();
            $ta = isset($row['num_evaluators']) ? intval($row['num_evaluators']) : 0;
        } else {
            $ta = 0;
        }
        
        // Query to get evaluation answers for the selected faculty member
        $get = $this->db->query("SELECT *
        FROM evaluation_answers_superior eas
        JOIN evaluation_list_superior el ON eas.evaluation_id = el.evaluation_id
        WHERE el.academic_id = {$_SESSION['academic']['id']} AND el.faculty_id = $fid");
        
        // Process evaluation answers to calculate ratings
        $rate = array();
        if ($get && $get->num_rows > 0) {
            while ($row = $get->fetch_assoc()) {
                $question_id = $row['question_id'];
                $rate_value = $row['rate'];
    
                if (!isset($rate[$question_id][$rate_value])) {
                    $rate[$question_id][$rate_value] = 0;
                }
                $rate[$question_id][$rate_value]++;
            }
        }
        
        // Format ratings data for JSON response
        $r = array();
        foreach ($rate as $qk => $qv) {
            $r[$qk] = $qv; // Number of evaluators for each rating
        }
        
        // Prepare data for JSON response
        $data['tse'] = $ta;
        $data['data'] = $r;
        
        // Return JSON-encoded data
        return json_encode($data);
    }
    public function fetch_evaluation_information() {
        global $conn;
    
        // Siguraduhing mayroong POST data para sa faculty_id
        if (isset($_POST['faculty_id'])) {
            $faculty_id = $_POST['faculty_id'];
    
            // Kunin ang mga POST data para sa subject_id, class_id, at academic_id
            $subject_id = $_POST['subject_id'];
            $class_id = $_POST['class_id'];
            $academic_id = $_POST['academic_id'];
    
            // Gumawa ng query para kunin ang detalye ng instructor batay sa mga criteria mula sa restriction_list
            $query = "SELECT f.*, r.subject_id, r.class_id, r.academic_id,
                              s.subject, 
                              CONCAT(c.curriculum, ' ', c.level, ' - ', c.section) AS section_name
                      FROM faculty_list f
                      INNER JOIN restriction_list r ON f.id = r.faculty_id
                      INNER JOIN subject_list s ON r.subject_id = s.id
                      INNER JOIN class_list c ON r.class_id = c.id
                      WHERE f.id = $faculty_id
                      AND r.subject_id = $subject_id
                      AND r.class_id = $class_id
                      AND r.academic_id = $academic_id";
    
            // Ipasa ang query sa database at kunin ang resulta
            $result = $conn->query($query);
    
            // Surin kung may resulta ang query
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                // I-format ang response bilang JSON
                $response = array(
                    'avatar' => 'assets/uploads/' . $row['avatar'], // URL ng larawan ng instructor
                    'name' => $row['firstname'] . ' ' . $row['lastname'], // Buong pangalan ng instructor
                    'subject' => $row['subject'], // Pangalan ng subject na itinuturo ng instructor
                    'section' => $row['section_name'] // Pangalan ng section kung saan itinuturo ng instructor
                );
    
                // I-echo ang JSON response
                echo json_encode($response);
            }
        }
    }
    
    function check_evaluation() {
// Include your database connection file
include 'db_connect.php';

// Check if selectedFacultyId is set in the POST data
if (isset($_POST['selectedFacultyId'])) {
    $selectedFacultyId = $_POST['selectedFacultyId'];
    
    // Ensure that the session variable is set and not empty
    if (isset($_SESSION['login_id']) && !empty($_SESSION['login_id'])) {
        $superiorId = $_SESSION['login_id'];
        
        // Query to check if the faculty member has been evaluated by the superior
        $check_evaluation_query = "SELECT CONCAT(firstname, ' ', lastname) AS name
                                   FROM evaluation_list_superior
                                   INNER JOIN faculty_list ON evaluation_list_superior.faculty_id = faculty_list.id
                                   WHERE faculty_list.id = $selectedFacultyId
                                         AND evaluation_list_superior.superior_id = $superiorId";

        // Execute the query
        $check_evaluation_result = $conn->query($check_evaluation_query);

        // Check if the query was successful
        if ($check_evaluation_result) {
            // Check if there is a result
            if ($check_evaluation_result->num_rows > 0) {
                // Faculty member has been evaluated by the superior
                $evaluatedFacultyName = ucwords($check_evaluation_result->fetch_assoc()['name']);
                echo json_encode(['evaluated' => true, 'facultyName' => $evaluatedFacultyName]);
            } else {
                // Faculty member has not been evaluated by the superior
                echo json_encode(['evaluated' => false]);
            }
        } else {
            // Error in executing the query
            echo json_encode(['error' => 'Query execution failed: ' . $conn->error]); // Add more details for debugging
        }
    } else {
        // Session variable not set or empty
        echo json_encode(['error' => 'Invalid session']);
    }
} else {
    // selectedFacultyId not set in the POST data
    echo json_encode(['error' => 'selectedFacultyId is required']);
}
    }
    function import_section() {
        global $conn;
    
        // Include PhpSpreadsheet library and other necessary files
        require 'vendor/autoload.php';
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
                
                $expectedHeaders = ['Program', 'Level', 'Section'];
                $headerRow = $spreadSheetAry[0]; // Get the first row (header row) from the array
    
                // Check if the header row matches the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Please ensure the column headers are 'Program', 'Level', 'Section'.";
                }
    
                try {
                    // Start iteration from the second row (skipping the first row)
                    $skipFirstRow = true;
                    foreach ($spreadSheetAry as $row) {
                        if ($skipFirstRow) {
                            $skipFirstRow = false;
                            continue; // Skip the first row (header row)
                        }
    
                        $curriculum = $row[0];
                        $level = $row[1];
                        $section = $row[2];
    
                        // Check if entry already exists in database
                        $stmt_check = $conn->prepare("SELECT * FROM class_list WHERE curriculum = ? AND level = ? AND section = ?");
                        $stmt_check->bind_param("sss", $curriculum, $level, $section);
                        $stmt_check->execute();
                        $result = $stmt_check->get_result();
    
                        if ($result->num_rows > 0) {
                            // Entry already exists, skip insertion or update logic
                            continue;
                        }
    
                        // Insert data into database
                        $stmt = $conn->prepare("INSERT INTO class_list (curriculum, level, section) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $curriculum, $level, $section);
                        $stmt->execute();
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    
    function import_subject() {
        global $conn;
    
        // Include PhpSpreadsheet library and other necessary files
        require 'vendor/autoload.php';
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
    
                // Define expected column headers
                $expectedHeaders = ['Subject Code', 'Subject Name', 'Description'];
                $headerRow = $spreadSheetAry[0]; // Get the first row (header row) from the array
    
                // Check if the header row matches the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Please ensure the column headers are 'Subject Code', 'Subject Name', 'Description'.";
                }
    
                try {
                    // Start iteration from the second row (skipping the header row)
                    $skipFirstRow = true;
                    foreach ($spreadSheetAry as $row) {
                        if ($skipFirstRow) {
                            $skipFirstRow = false;
                            continue; // Skip the first row (header row)
                        }
    
                        $subject = $row[0];
                        $level = $row[1];
                        $section = $row[2];
    
                        // Check if entry already exists in database
                        $stmt_check = $conn->prepare("SELECT * FROM subject_list WHERE code = ? AND subject = ? AND description = ?");
                        $stmt_check->bind_param("sss", $subject, $level, $section);
                        $stmt_check->execute();
                        $result = $stmt_check->get_result();
    
                        if ($result->num_rows > 0) {
                            // Entry already exists, skip insertion or update logic
                            continue;
                        }
    
                        // Insert data into database
                        $stmt = $conn->prepare("INSERT INTO subject_list (code, subject, description) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $subject, $level, $section);
                        $stmt->execute();
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    
    function import_criteria_student() {
        global $conn;
    
        // Include PhpSpreadsheet library and other necessary files
        require 'vendor/autoload.php';
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();

                $expectedHeaders = ['Criteria for Evaluation (Students)'];
                $headerRow = $spreadSheetAry[0]; // Get the first row (header row) from the array
    
                // Check if the header row matches the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Please ensure the column headers are 'Criteria for Evaluation (Students)'.";
                }
                try {
                    // Get the maximum order number from the database
                    $stmt_max_order = $conn->prepare("SELECT MAX(`order_by`) AS max_order FROM criteria_list");
                    $stmt_max_order->execute();
                    $result_max_order = $stmt_max_order->get_result();
                    $max_order = ($result_max_order->num_rows > 0) ? $result_max_order->fetch_assoc()['max_order'] : 0;
    
                    // Start iteration from the second row (skipping the first row)
                    $skipFirstRow = true;
                    foreach ($spreadSheetAry as $row) {
                        if ($skipFirstRow) {
                            $skipFirstRow = false;
                            continue; // Skip the first row (header row)
                        }
    
                        $criteria = $row[0];
    
                        // Check if entry already exists in database
                        $stmt_check = $conn->prepare("SELECT * FROM criteria_list WHERE criteria = ?");
                        $stmt_check->bind_param("s", $criteria);
                        $stmt_check->execute();
                        $result = $stmt_check->get_result();
    
                        if ($result->num_rows > 0) {
                            // Entry already exists, skip insertion
                            continue;
                        }
    
                        // Insert data into database with the next available order
                        $new_order = $max_order + 1;
                        $stmt_insert = $conn->prepare("INSERT INTO criteria_list (criteria, `order_by`) VALUES (?, ?)");
                        $stmt_insert->bind_param("si", $criteria, $new_order);
                        $stmt_insert->execute();
    
                        $max_order = $new_order; // Update the maximum order number
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    function import_criteria_superior() {
        global $conn;
    
        // Include PhpSpreadsheet library and other necessary files
        require 'vendor/autoload.php';
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
                
                $expectedHeaders = ['Criteria for Evaluation (Superiors)'];
                $headerRow = $spreadSheetAry[0]; // Get the first row (header row) from the array
    
                // Check if the header row matches the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Please ensure the column headers are 'Criteria for Evaluation (Superiors)'.";
                }
                try {
                    // Get the maximum order number from the database
                    $stmt_max_order = $conn->prepare("SELECT MAX(`order_by`) AS max_order FROM criteria_list_superior");
                    $stmt_max_order->execute();
                    $result_max_order = $stmt_max_order->get_result();
                    $max_order = ($result_max_order->num_rows > 0) ? $result_max_order->fetch_assoc()['max_order'] : 0;
    
                    // Start iteration from the second row (skipping the first row)
                    $skipFirstRow = true;
                    foreach ($spreadSheetAry as $row) {
                        if ($skipFirstRow) {
                            $skipFirstRow = false;
                            continue; // Skip the first row (header row)
                        }
    
                        $criteria = $row[0];
    
                        // Check if entry already exists in database
                        $stmt_check = $conn->prepare("SELECT * FROM criteria_list_superior WHERE criteria = ?");
                        $stmt_check->bind_param("s", $criteria);
                        $stmt_check->execute();
                        $result = $stmt_check->get_result();
    
                        if ($result->num_rows > 0) {
                            // Entry already exists, skip insertion
                            continue;
                        }
    
                        // Insert data into database with the next available order
                        $new_order = $max_order + 1;
                        $stmt_insert = $conn->prepare("INSERT INTO criteria_list_superior (criteria, `order_by`) VALUES (?, ?)");
                        $stmt_insert->bind_param("si", $criteria, $new_order);
                        $stmt_insert->execute();
    
                        $max_order = $new_order; // Update the maximum order number
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    function import_questions() {
        global $conn;
    
        // Include PhpSpreadsheet library and other necessary files
        require 'vendor/autoload.php';
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
                
                $expectedHeaders = ['Questions (Students)'];
                $headerRow = $spreadSheetAry[0]; // Get the first row (header row) from the array
    
                // Check if the header row matches the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Please ensure the column headers are 'Questions (Students)'.";
                }
    
                try {
                    // Start iteration from the second row (skipping the first row)
                    $skipFirstRow = true;
                    foreach ($spreadSheetAry as $row) {
                        if ($skipFirstRow) {
                            $skipFirstRow = false;
                            continue; // Skip the first row (header row)
                        }
    
                        $questions = $row[0];
    
                        // Check if entry already exists in database
                        $stmt_check = $conn->prepare("SELECT * FROM questions_list WHERE questions = ?");
                        $stmt_check->bind_param("s", $questions);
                        $stmt_check->execute();
                        $result = $stmt_check->get_result();
    
                        if ($result->num_rows > 0) {
                            // Entry already exists, skip insertion or update logic
                            continue;
                        }
    
                        // Insert data into database
                        $stmt = $conn->prepare("INSERT INTO questions_list (questions) VALUES (?)");
                        $stmt->bind_param("s", $questions);
                        $stmt->execute();
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    function import_questions_superior() {
        global $conn;
    
        // Include PhpSpreadsheet library and other necessary files
        require 'vendor/autoload.php';
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();

                $expectedHeaders = ['Questions (Superiors)'];
                $headerRow = $spreadSheetAry[0]; // Get the first row (header row) from the array
    
                // Check if the header row matches the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Please ensure the column headers are 'Questions (Superiors)'.";
                }
                try {
                    // Start iteration from the second row (skipping the first row)
                    $skipFirstRow = true;
                    foreach ($spreadSheetAry as $row) {
                        if ($skipFirstRow) {
                            $skipFirstRow = false;
                            continue; // Skip the first row (header row)
                        }
    
                        $questions = $row[0];
    
                        // Check if entry already exists in database
                        $stmt_check = $conn->prepare("SELECT * FROM questions_list_superior WHERE questions = ?");
                        $stmt_check->bind_param("s", $questions);
                        $stmt_check->execute();
                        $result = $stmt_check->get_result();
    
                        if ($result->num_rows > 0) {
                            // Entry already exists, skip insertion or update logic
                            continue;
                        }
    
                        // Insert data into database
                        $stmt = $conn->prepare("INSERT INTO questions_list_superior (questions) VALUES (?)");
                        $stmt->bind_param("s", $questions);
                        $stmt->execute();
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    function import_student() {
        global $conn;
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                require 'vendor/autoload.php';
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
    
                $expectedHeaders = ['School ID', 'Firstname', 'Lastname', 'Email', 'Password', 'Section'];
                $headerRow = array_map('trim', $spreadSheetAry[0]); // Trim header row values for comparison
    
                // Check if the extracted headers match the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Expected headers: '" . implode("', '", $expectedHeaders) . "'.";
                }
    
                try {
                    foreach ($spreadSheetAry as $row) {
                        $school_id = $row[0];
                        $firstname = $row[1];
                        $lastname = $row[2];
                        $email = $row[3];
                        $password = md5($row[4]); // Encrypt password with MD5
                        $class = $row[5]; // Get the class information from Excel
    
                        // Retrieve class_id from class_list based on curriculum, level, section
                        $stmt_class = $conn->prepare("SELECT id FROM class_list WHERE CONCAT(curriculum, ' ', level, ' - ', section) = ?");
                        $stmt_class->bind_param("s", $class);
                        $stmt_class->execute();
                        $result_class = $stmt_class->get_result();
    
                        if ($result_class->num_rows > 0) {
                            $class_data = $result_class->fetch_assoc();
                            $class_id = $class_data['id'];
    
                            // Check if student entry already exists based on email and school_id
                            $stmt_check = $conn->prepare("SELECT * FROM student_list WHERE email = ? AND school_id = ?");
                            $stmt_check->bind_param("ss", $email, $school_id);
                            $stmt_check->execute();
                            $result_check = $stmt_check->get_result();
    
                            if ($result_check->num_rows > 0) {
                                continue; // Skip if student already exists based on email and school_id
                            }
    
                            // Insert student data
                            $stmt_insert = $conn->prepare("INSERT INTO student_list (school_id, firstname, lastname, email, password, class_id) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt_insert->bind_param("sssssi", $school_id, $firstname, $lastname, $email, $password, $class_id);
                            $stmt_insert->execute();
                        }
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    function import_superior() {
        global $conn;
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                require 'vendor/autoload.php';
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $highestColumn = $excelSheet->getHighestColumn(); // Get the highest column (last column) used in the sheet
                $expectedHeaders = ['First Name', 'Last Name', 'Email', 'Password'];
                $headerRow = [];
    
                // Loop through each column to retrieve header values
                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $cellValue = $excelSheet->getCell($col . '1')->getValue();
                    if (!empty($cellValue)) {
                        $headerRow[] = $cellValue;
                    }
                }
    
                // Check if the extracted headers match the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Expected headers: '" . implode("', '", $expectedHeaders) . "'.";
                }
    
                try {
                    foreach ($excelSheet->getRowIterator(2) as $row) { // Start from row 2 (skipping header row)
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(true);
    
                        $rowData = [];
                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getValue();
                        }
    
                        // Extract data from row
                        list($firstname, $lastname, $email, $password) = $rowData;
    
                        $passwordHash = md5($password); // Encrypt password with MD5
    
                        // Check if superior entry already exists based on email
                        $stmt_check = $conn->prepare("SELECT * FROM superior_list WHERE email = ?");
                        $stmt_check->bind_param("s", $email);
                        $stmt_check->execute();
                        $result_check = $stmt_check->get_result();
    
                        if ($result_check->num_rows > 0) {
                            // Update superior information if email already exists
                            $stmt_update = $conn->prepare("UPDATE superior_list SET firstname = ?, lastname = ?, email = ?, password = ? WHERE email = ?");
                            $stmt_update->bind_param("sssss", $firstname, $lastname, $email, $passwordHash, $email);
                            $stmt_update->execute();
                        } else {
                            // Insert new superior data if email does not exist
                            $stmt_insert = $conn->prepare("INSERT INTO superior_list (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
                            $stmt_insert->bind_param("ssss", $firstname, $lastname, $email, $passwordHash);
                            $stmt_insert->execute();
                        }
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    function import_faculty() {
        global $conn;
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                require 'vendor/autoload.php';
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
    
                // Define expected headers and validate against the extracted header row
                $expectedHeaders = ['School ID', 'Firstname', 'Lastname', 'Email', 'Password'];
                $headerRow = array_map('trim', $spreadSheetAry[0]); // Trim header row values for comparison
    
                // Check if the extracted headers match the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Expected headers: '" . implode("', '", $expectedHeaders) . "'.";
                }
    
                try {
                    foreach ($spreadSheetAry as $index => $row) {
                        if ($index === 0) {
                            continue; // Skip the first row (header row)
                        }
                        $school_id = $row[0];
                        $firstname = $row[1];
                        $lastname = $row[2];
                        $email = $row[3];
                        $password = md5($row[4]); // Encrypt password with MD5
    
                        // Check if faculty entry already exists based on email or school ID
                        $stmt_check = $conn->prepare("SELECT * FROM faculty_list WHERE school_id = ? OR email = ?");
                        $stmt_check->bind_param("ss", $school_id, $email);
                        $stmt_check->execute();
                        $result_check = $stmt_check->get_result();
    
                        if ($result_check->num_rows > 0) {
                            // Update faculty information if school ID or email already exists
                            $stmt_update = $conn->prepare("UPDATE faculty_list SET firstname = ?, lastname = ?, email = ?, password = ?
                                WHERE school_id = ? OR email = ?");
                            $stmt_update->bind_param("ssssss", $firstname, $lastname, $email, $password, $school_id, $email);
                            $stmt_update->execute();
                        } else {
                            // Insert new faculty data if school ID and email do not exist
                            $stmt_insert = $conn->prepare("INSERT INTO faculty_list (school_id, firstname, lastname, email, password) 
                                VALUES (?, ?, ?, ?, ?)");
                            $stmt_insert->bind_param("sssss", $school_id, $firstname, $lastname, $email, $password);
                            $stmt_insert->execute();
                        }
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    function import_admin() {
        global $conn;
    
        if (isset($_FILES['exceldata'])) {
            $allowedFileTypes = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
    
            if (in_array($_FILES['exceldata']['type'], $allowedFileTypes)) {
                $filename = $_FILES['exceldata']['name'];
                $tempname = $_FILES['exceldata']['tmp_name'];
                move_uploaded_file($tempname, 'assets/uploads/' . $filename);
    
                require 'vendor/autoload.php';
    
                $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $reader->load('assets/uploads/' . $filename);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
                
                $expectedHeaders = ['Firstname', 'Lastname', 'Email','Password'];
                $headerRow = $spreadSheetAry[0]; // Get the first row (header row) from the array
    
                // Check if the header row matches the expected headers
                if ($headerRow !== $expectedHeaders) {
                    return "Invalid Excel format. Please ensure the column headers are 'Firstname', 'Lastname', 'Email','Password'.";
                }
                try {
                    foreach ($spreadSheetAry as $index => $row) {
                        if ($index === 0) {
                            continue; // Skip the first row (header row)
                        }
                        $firstname = $row[0];
                        $lastname = $row[1];
                        $email = $row[2];
                        $password = md5($row[3]); // Encrypt password with MD5
    
                        // Check if superior entry already exists based on email
                        $stmt_check = $conn->prepare("SELECT * FROM users WHERE email = ?");
                        $stmt_check->bind_param("s", $email);
                        $stmt_check->execute();
                        $result_check = $stmt_check->get_result();
    
                        if ($result_check->num_rows > 0) {
                            // Update superior information if email already exists
                            $stmt_update = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? 
                            WHERE email = ?");
                            $stmt_update->bind_param("sssss", $firstname, $lastname, $email, $password, $email);
                            $stmt_update->execute();
                        } else {
                            // Insert new superior data if email does not exist
                            $stmt_insert = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) 
                            VALUES (?, ?, ?, ?)");
                            $stmt_insert->bind_param("ssss", $firstname, $lastname, $email, $password);
                            $stmt_insert->execute();
                        }
                    }
                    return 1; // Success response
                } catch (PDOException $e) {
                    return "Database Error: " . $e->getMessage();
                }
            } else {
                return "Please upload a valid Excel file.";
            }
        } else {
            return "No file uploaded.";
        }
    }
    public function getPositive() {
        global $conn;
        $sql = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Positive' ORDER BY term";
        $result = $this->$conn->query($sql);
        $output = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $term_id = $row['term_id'];
                $term = $row['term'];
                
                // Append row to output
                $output .= "<tr>";
                $output .= "<td>{$term}</td>";
                // Add action buttons if needed
                $output .= "<td><a href='./toNegative.php?term_id={$term_id}' class='negative'><i class='fas fa-minus'></i><span> Negative</span></a>";
                $output .= "<a href='./toNeutral.php?term_id={$term_id}' class='neutral'><i class='fas fa-genderless'></i><span> Neutral</span></a></td>";
                $output .= "</tr>";
            }
        } else {
            $output .= "<tr><td colspan='2'>No positive terms found.</td></tr>";
        }

        return $output;
    }

    // Function to get negative terms
    public function getNegative() {
        global $conn;
        $sql = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Negative' ORDER BY term";
        $result = $this->$conn->query($sql);
        $output = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $term_id = $row['term_id'];
                $term = $row['term'];
                
                // Append row to output
                $output .= "<tr>";
                $output .= "<td>{$term}</td>";
                // Add action buttons if needed
                $output .= "<td><a href='./toPositive.php?term_id={$term_id}' class='positive'><i class='fas fa-plus'></i><span> Positive</span></a>";
                $output .= "<a href='./toNeutral.php?term_id={$term_id}' class='neutral'><i class='fas fa-genderless'></i><span> Neutral</span></a></td>";
                $output .= "</tr>";
            }
        } else {
            $output .= "<tr><td colspan='2'>No negative terms found.</td></tr>";
        }

        return $output;
    }

    // Function to get neutral terms
    public function getNeutral() {
        global $conn;
        $sql = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Neutral' ORDER BY term";
        $result = $this->$conn->query($sql);
        $output = '';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $term_id = $row['term_id'];
                $term = $row['term'];
                
                // Append row to output
                $output .= "<tr>";
                $output .= "<td>{$term}</td>";
                // Add action buttons if needed
                $output .= "<td><a href='./toPositive.php?term_id={$term_id}' class='positive'><i class='fas fa-plus'></i><span> Positive</span></a>";
                $output .= "<a href='./toNegative.php?term_id={$term_id}' class='negative'><i class='fas fa-minus'></i><span> Negative</span></a></td>";
                $output .= "</tr>";
            }
        } else {
            $output .= "<tr><td colspan='2'>No neutral terms found.</td></tr>";
        }

        return $output;
    }
    function add_term($term, $termType, $value) {
        global $conn;
    
        // Trim and sanitize inputs
        $term = trim(mysqli_real_escape_string($conn, $term));
        $termType = mysqli_real_escape_string($conn, $termType);
        $value = (int)$value;
    
        // Validate term
        if (empty($term)) {
            return "Term should not be blank.";
        }
    
        // Check if the term already exists (case-insensitive comparison)
        $checkQuery = "SELECT COUNT(*) as count FROM sentiment_terms WHERE LOWER(term) = LOWER(?) AND term_type = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "ss", $term, $termType);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    
        if ($count > 0) {
            return "Term already exists!";
        }
    
        // Insert the term into the database
        $insertQuery = "INSERT INTO sentiment_terms (term, term_type, value) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ssi", $term, $termType, $value);
    
        if (mysqli_stmt_execute($stmt)) {
            return "Term successfully added!";
        } else {
            return "Error adding term: " . mysqli_error($conn);
        }
    
        mysqli_stmt_close($stmt);
    }
    
    public function delete_term($term_id) {
        global $conn;
        // Query para idelete ang term base sa term_id
        $query = "DELETE FROM sentiment_terms WHERE term_id = $term_id";
        $stmt = $this->db->prepare($query);

        // I-execute ang query
        if ($stmt->execute()) {
            return 'Term deleted successfully.';
        } else {
            return 'Error deleting term.';
        }
    }
    public function Positive($term_id) {
        global $conn;
        $query = "UPDATE sentiment_terms SET term_type = 'Positive', value = 1 WHERE term_id = $term_id";
        if ($conn->query($query) === TRUE) {
            return "Term successfully moved to Positive.";
        } else {
            return "Error: " . $conn->error;
        }
    }

    // Function para ilipat ang term type sa Negative
    public function Negative($term_id) {
        global $conn;
        $query = "UPDATE sentiment_terms SET term_type = 'Negative', value = -1 WHERE term_id = $term_id";
        if ($conn->query($query) === TRUE) {
            return "Term successfully moved to Negative.";
        } else {
            return "Error: " . $conn->error;
        }
    }

    // Function para ilipat ang term type sa Neutral
    public function Neutral($term_id) {
        global $conn;
        $query = "UPDATE sentiment_terms SET term_type = 'Neutral', value = 0 WHERE term_id = $term_id";
        if ($conn->query($query) === TRUE) {
            return "Term successfully moved to Neutral.";
        } else {
            return "Error: " . $conn->error;
        }
    }

    public function sentiment_terms() {
        global $conn;

        $response = array();

        // Fetch positive terms
        $positive_query = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Positive'";
        $positive_result = $conn->query($positive_query);

        if ($positive_result->num_rows > 0) {
            while ($row = $positive_result->fetch_assoc()) {
                $response['positive'][] = $row;
            }
        }

        // Fetch negative terms
        $negative_query = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Negative'";
        $negative_result = $conn->query($negative_query);

        if ($negative_result->num_rows > 0) {
            while ($row = $negative_result->fetch_assoc()) {
                $response['negative'][] = $row;
            }
        }

        // Fetch neutral terms
        $neutral_query = "SELECT term_id, term FROM sentiment_terms WHERE term_type = 'Neutral'";
        $neutral_result = $conn->query($neutral_query);

        if ($neutral_result->num_rows > 0) {
            while ($row = $neutral_result->fetch_assoc()) {
                $response['neutral'][] = $row;
            }
        }

        return json_encode($response);
    }

}