<?php


include 'db_connect.php';
ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

switch ($action) {
    case 'login':
    case 'login2':
    case 'logout':
    case 'signup':
    case 'save_user':
    case 'save_term':
    case 'update_user':
    case 'delete_user':
    case 'save_subject':
    case 'delete_subject':
    case 'save_class':
    case 'delete_class':
    case 'save_academic':
    $result = $crud->{$action}();
    break;
    case 'fetch_instructor':
    $result = $crud->{$action}();
    break;
    case 'faculty_feedbacks':
    $result = $crud->{$action}();
    break;
    case 'faculty_feedbacks_superior':
    $result = $crud->{$action}();
    break;
    case 'get_feedbacks':
    $result = $crud->{$action}();
    break;
    case 'get_faculty':
    $result = $crud->{$action}();
    break;
    case 'get_feedbacks_superior':
    $result = $crud->{$action}();
    break;
    
    case 'get_sentiment_terms_faculty_students':
        $facultyId = $_GET['faculty_id'] ?? null;
        if ($facultyId !== null) {
            $result = $crud->{$action}($facultyId);
        } else {
            $result = json_encode(array('error' => 'Invalid faculty_id'));
        }
        break;
        case 'get_sentiment_terms_faculty_superiors':
            $result = $crud->{$action}();
            break;
    case 'get_sentiment_terms':
        $facultyId = $_GET['faculty_id'] ?? null;
        if ($facultyId !== null) {
            $result = $crud->{$action}($facultyId);
        } else {
            $result = json_encode(array('error' => 'Invalid faculty_id'));
        }
        break;
        case 'get_sentiment_terms_superior':
            $facultyId = $_GET['faculty_id'] ?? null;
            if ($facultyId !== null) {
                $result = $crud->{$action}($facultyId);
            } else {
                $result = json_encode(array('error' => 'Invalid faculty_id'));
            }
            break;
    case 'delete_academic':
    case 'make_default':
    case 'save_criteria':
    case 'save_criteria_superior':
    $result = $crud->{$action}();
    break;
    case 'close_evaluation':
    case 'start_evaluation':
    case 'delete_criteria':
    case 'restrict':
    case 'unrestrict':
    case 'get_evaluation_status':
    case 'delete_criteria_superior':
    $result = $crud->{$action}();
    break;
    case 'save_question':
    $result = $crud->{$action}();
    break;
    case 'save_question_superior':
    $result = $crud->{$action}();
    break;
    case 'save_questions':
    $result = $crud->{$action}();
    break;
    case 'save_questions_superior':
    $result = $crud->{$action}();
    break;
    case 'delete_question':
    $result = $crud->{$action}();
    break;
    case 'delete_question_superior':
    $result = $crud->{$action}();
    break;
    case 'delete_questions':
    case 'delete_questions_superior':
    case 'save_criteria_question':
    case 'save_criteria_question_superior':
    case 'save_criteria_order':
    case 'save_criteria_order_superior':
    case 'save_question_order':
    case 'save_question_order_superior':
    case 'save_superior';
    $result = $crud->{$action}();
    break;
    case 'fetch_instructor_subject':
        $result = $crud->{$action}();
        break;
    
    case 'delete_superior';
    case 'save_faculty':
    case 'delete_faculty':
    case 'save_student':
    case 'signup_account':
    case 'add_student':
    case 'delete_student':
    case 'save_restriction':
    case 'save_evaluation':
    $result = $crud->{$action}();
    break;
    case 'update_pending': 
    case 'forgot_password':
    case 'get_class':
    $result = $crud->{$action}();
    break;
    case 'get_class_superior':
    $result = $crud->{$action}();
    break;
    case 'sentiment_terms':
    $result = $crud->{$action}();
    break;
    case 'add_term':
        $term = $_POST['term'] ?? ''; // Kunin ang term mula sa POST data, default value ay empty string
        $termType = $_POST['term_type'] ?? ''; // Kunin ang term type mula sa POST data, default value ay empty string
        $value = $_POST['value'] ?? 0; // Kunin ang value mula sa POST data, default value ay 0
    
        // Tawagin ang add_term function at ipasa ang mga kinakailangang parameters
        $result = $crud->{$action}($term, $termType, $value);
        break;
        case 'delete_term':
            $result = $crud->{$action}($_GET['term_id']);
            break;
    case 'Positive':
        case 'Negative':
        case 'Neutral':
            $result = $crud->{$action}($_GET['term_id']);
            break;
    case 'check_evaluation':
    $result = $crud->{$action}();
    break;
    case 'fetch_evaluation_information':
        $result = $crud->{$action}();
        break;
    case 'import_section':
        $result = $crud->{$action}();
        break;
    case 'certificate':
    case 'fetch_feedbacks':
    case 'toggle_evaluation_status':
    case 'save_draft':
    $result = $crud->{$action}();
    break;
    case 'get_question': // Add this case
    $result = $crud->{$action}();
    break;
    case 'save_evaluation_superior':
        $result = $crud->{$action}();
        break;
    case 'get_question_superior': // Add this case
    $result = $crud->{$action}();
    break;
    case 'get_report_superior':
        $result = $crud->{$action}();
        break;
    case 'get_report': // Add this case
    $result = $crud->{$action}();
    break;
    case 'import_subject':
        $result = $crud->{$action}();
        break;
    case 'import_student':
        $result = $crud->{$action}();
        break;
    case 'import_faculty':
        $result = $crud->{$action}();
        break;
    case 'import_superior':
        $result = $crud->{$action}();
        break;
    case 'import_admin':
        $result = $crud->{$action}();
        break;
    case 'import_criteria_student':
        $result = $crud->{$action}();
        break;
    case 'import_criteria_superior':
        $result = $crud->{$action}();
        break;
    case 'import_questions':
        $result = $crud->{$action}();
        break;
    case 'import_questions_superior':
        $result = $crud->{$action}();
        break;
    case 'fetch_ratings':
        $result = $crud->{$action}();
        break;
    default:
    $result = 'Invalid action';
    break;
    
}

if ($result) {
    echo $result;
}

ob_end_flush();
?>