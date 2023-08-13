<?php
require('model/database.php');
require('model/assignment_db.php');
require('model/course_db.php');

//The code is using filter_input to sanitize and validate user input. It's retrieving values from POST and GET requests and filtering them to ensure they are of the expected data types and are safe to use.
$assignment_id = filter_input(INPUT_POST, 'assignment_id', FILTER_VALIDATE_INT);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);

$course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
if (!$course_id) {
    $course_id = filter_input(INPUT_GET, 'course_id', FILTER_VALIDATE_INT);
    // an assignment of NULL or FALSE is ok here
}

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if (!$action) {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if (!$action) {
        $action = 'list_assignments'; // assigning default value if NULL or FALSE
    }
}

//The code uses a switch statement based on the $action variable. The possible actions include listing courses, adding a course, adding an assignment, deleting a course, deleting an assignment, and a default case that retrieves data for displaying assignments.
switch ($action) {
        //Retrieves a list of courses, includes the appropriate view template (course_list.php), and displays the list of courses.
    case "list_courses":
        $courses = get_courses();
        include('view/course_list.php');
        break;
        //Adds a new course using the add_course function, then redirects to list the courses.
    case "add_course":
        add_course($course_name);
        header("Location: .?action=list_courses");
        break;
        //Adds a new assignment to a course, then redirects to display the assignments for that course. If the data is invalid, an error is displayed.
    case "add_assignment":
        if ($course_id && $description) {
            add_assignment($course_id, $description);
            header("Location: .?course_id=$course_id");
        } else {
            $error = "Invalid assignment data. Check all fields and try again.";
            include('view/error.php');
            exit();
        }
        break;
        //Deletes a course using the delete_course function, handling a potential error if assignments exist for the course, and redirects to list the courses.
    case "delete_course":
        if ($course_id) {
            try {
                delete_course($course_id);
            } catch (PDOException $e) {
                $error = "You cannot delete a course if assignments exist for it.";
                include('view/error.php');
                exit();
            }
            header("Location: .?action=list_courses");
        }
        break;
        //Deletes an assignment using the delete_assignment function and redirects to display the assignments for the corresponding course.
    case "delete_assignment":
        if ($assignment_id) {
            delete_assignment($assignment_id);
            header("Location: .?course_id=$course_id");
        } else {
            $error = "Missing or incorrect assignment id.";
            include('view/error.php');
        }
        break;
        //retrieves data for displaying assignments associated with a specific course
    default:
        $course_name = get_course_name($course_id);
        $courses = get_courses();
        $assignments = get_assignments_by_course($course_id);
        include('view/assignment_list.php');
}
