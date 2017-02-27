<?php

namespace app\Libs;

use App\Libs\HttpRequestAPI;
use Google_Service_Classroom;
use Google_Service_Classroom_Course;

class GoogleClassRoom
{
	function __construct($adminEmail)
	{
		$this->rq = new HttpRequestAPI($adminEmail);
		$this->api = new Google_Service_Classroom($this->rq->getGoogleClient());
	}
	
	function get_classes($opts=[])
	{
		return $this->api->courses->listCourses($opts)->toSimpleObject()->courses;
	}
	
	function get_class($id)
	{
		return  $this->api->courses->get($id)->toSimpleObject();
	}
	
	function add_class($info)
	{
		$newClass = new Google_Service_Classroom_Course($info);
		return  $this->api->courses->create($newClass)->toSimpleObject();
	}
	
	function update_class($id, $info)
	{
		$method = 'PATCH';
		$uri = "https://classroom.googleapis.com/v1/courses/$id?updateMask=".implode(',',array_keys($info));;
		return $this->rq->send($method, $uri, $info);
		
	}
	
	function delete_class($id)
	{
		$this->api->courses->delete($id);
	}
	
	function get_assignments($courseId, $opts=[])
	{
		return $this->api->courses_courseWork->listCoursesCourseWork($courseId, $opts)->toSimpleObject()->courseWork;
	}
	
	function get_assignment_grades($courseId, $courseWorkId, $opts = [])
	{
		return $this->api->courses_courseWork_studentSubmissions
		->listCoursesCourseWorkStudentSubmissions($courseId, $courseWorkId, $opts)->toSimpleObject()->studentSubmissions;
	}
	
	function enroll_students($courseId, $studentIds)
	{
		$method = 'POST';
		$uri = "https://classroom.googleapis.com/v1/courses/$courseId/students";
		
		$students = [];
		foreach($studentIds as $email)
		{
			$newStudent = new Google_Service_Classroom_Student();
			$newStudent->setUserId($email);
			$students[] = $newStudent;
		}
		
		return $this->rq->batch($method, $uri, $students);
	
	}
	
	function enroll_teachers($courseId, $teacherIds)
	{
		$method = 'POST';
		$uri = "https://classroom.googleapis.com/v1/courses/$courseId/teachers";
		
		$teachers = [];
		foreach($teacherIds as $email)
		{
			$newTeacher = new Google_Service_Classroom_Teacher();
			$newTeacher->setUserId($email);
			$teachers[] = $newTeacher;
		}
		
		return $this->rq->batch('POST', $uri, $teachers);
	}
	
	function enroll_parents($studentId, $parentEmails)
	{
		$method = 'POST';
		$uri = "https://classroom.googleapis.com/v1/userProfiles/$studentId/guardianInvitations";
		
		foreach($parentEmails as $email)
		{
			$newParent = new Google_Service_Classroom_GuardianInvitation();
			$newParent->setInvitedEmailAddress($email);
			$parents[] = $newParent;
		}
		
		return $this->rq->batch($method, $uri, $perents);
	}
	
	function unenroll_students($courseId, $studentIds)
	{
		foreach($studentIds as $id)
		{
			$uris[] = "https://classroom.googleapis.com/v1/courses/$courseId/students/$id";
		}
		$this->rq->del($uris);
	}
	
	
	function unenroll_teachers($courseId, $teacherIds)
	{
		foreach($teacherIds as $id)
		{
			$uris[] = "https://classroom.googleapis.com/v1/courses/$courseId/teachers/$id";
		}
		$this->rq->del($uris);
	}
	
	function unenroll_parents($studentId, $guardianIds)
	{
		foreach($guardianIds as $id)
		{
			$uris[] = "https://classroom.googleapis.com/v1/userProfiles/$studentId/guardians/$id";
		}
		$this->rq->del($uris);
	}
	
	function get_user_assignment_grades($user_id,$class_id,$assignment_id)
	{
		$method = 'GET';
		$uri = "https://classroom.googleapis.com/v1/courses/$class_id/courseWork/$assignment_id/studentSubmissions";
		$response = $this->rq->send($method, $uri);
		return $respone;
	}
	
	function add_assignment($class_id, $data)
	{
		$method = 'POST';
		$uri = "https://classroom.googleapis.com/v1/courses/$class_id/courseWork";
		$courseWork = new Google_Service_Classroom_CourseWork($data);
		return $this->rq->send($method, $uri, $data);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}


?>