<?php
require_once './permissionUtil.php';
require_once '../config/config.php';
require_once '../model/ExamLevelData.php';
//require_once '../model/UserAdminData.php';
require_once '../model/LogAdminData.php';
require_once '../util/util.php';
header("Content-type: application/json;charset=utf-8");

if(!isset($_SESSION)){
	session_start();
}

//db.students.update( {}, { $rename: { 'create_at': 'created_at'}})
//update db: db.news.update({},{$set:{deleted:false,active:true,create_by:1,created_at:ISODate("2015-10-30T10:10:10.965Z")},{multi:true})
$jResponse = null;
$admin_info = null;

if(!isset($_SESSION[session_user])){
	require_once './LoginUtil.php';
	if(!ReloginAjax()){
		$jResponse = [
			'error' => 5,
			'message' => 'Đã hết phiên làm việc, bạn hãy đăng nhập lại'
		];
		echo json_encode($jResponse);
		die();
	}
}

$admin_info=$_SESSION[session_user];

//$_POST = json_decode(file_get_contents('php://input'),true);
if(isset($_POST['action'])) $action = $_POST['action'];
if(isset($action)) {
	if(CheckPermission($admin_info['_id'], 'exam', $action)) {
		$bok = false;
		switch ($action){
			case 'info':
				$bok = Load_Info();
				break;
			case 'list':
				$bok = Load_List();
				break;
			case 'save':
				$bok = Save($admin_info);
				break;
			case 'delete':
				$bok = Delete();
				break;
			// case 'exists':
			// 	$bok = Exists($admin_info);
			// 	break;
			 case 'copy':
			 	$bok = CopyData($admin_info);
			 	break;
			default:
				$jResponse = [
					'error' => 81,
					'message' => 'Request not correct'
				];
				echo json_encode($jResponse);
				break;
		}
	} else{
		$jResponse = [
			'error' => 81,
			'message' => 'Bạn không có quyền thao tác hành động này'
		];
		echo json_encode($jResponse);
	}
} else{
	$jResponse = [
		'error' => 80,
		'message' => 'Không có thông tin yêu cầu'
	];
	echo json_encode($jResponse);
}

function Load_Info() {
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$jResponse = [];
	$ExamLevelData = ExamLevelData::getInstance();
	$info = $ExamLevelData->GetInfo($id);
	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'info' => $info
	];
	echo json_encode($jResponse);
}

function Load_List() {
	$class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : 0;
	$subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : 3; //3 = tiengviet
	$jResponse = [];
	$ExamLevelData = ExamLevelData::getInstance();
	$data = $ExamLevelData->GetListShow($class_id, $subject_id);
	$jResponse = [
		'error' => 0,
		'message' => 'ok',
		'content' => $data
	];
	echo json_encode($jResponse);
	// return $bok;
}

function Save($admin_info) {
	// var_dump($admin_info);
	global $redis;
	$jResponse = [];

	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$name = isset($_POST['name']) ? $_POST['name'] : '';
	$rewrite = isset($_POST['rewrite']) ? $_POST['rewrite'] : '';
	$answers = isset($_POST['answers']) ? $_POST['answers'] : [];
	$content = isset($_POST['content']) ? $_POST['content'] : [];
	$time = isset($_POST['time']) ? intval($_POST['time']) : 0;
	$play = isset($_POST['play']) ? intval($_POST['play']) : 100;
	$level = isset($_POST['level']) ? intval($_POST['level']) : 1;
	$description = isset($_POST['description']) ? $_POST['description'] : '';
	$thumb = isset($_POST['thumb']) ? $_POST['thumb'] : '';
	$class_id = isset($_POST['class_id']) ? intval($_POST['class_id']) : 0;
	$subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : 1;
	$game_id = isset($_POST['game_id']) ? intval($_POST['game_id']) : 1;
	$spq = isset($_POST['spq']) ? intval($_POST['spq']) : 10;

	$ExamLevelData = ExamLevelData::getInstance();
	if($id > 0) {
		$data_update = $ExamLevelData->Update($id, $name, $thumb, $rewrite, $class_id, $subject_id, $level, $game_id, $play, $time, $spq, $description, $answers, $content);
		if($data_update['updatedExisting'] && $data_update['n']>0){
			$redis->delete(sprintf('exam_level_info_%d', $id));//update cache
			$redis->delete(sprintf('exam_level_list_%d_%d', $class_id, $subject_id));//update cache list
			$redis->delete(sprintf('exam_level_list_%d_%d', $class_id, 0));//update cache list
			$jResponse = [
				'error' => 0,
				'message' =>'ok',
				'id' => $id
			];

			$LogAdminData = LogAdminData::getInstance();
			$LogAdminData->Insert([
				'action' => 'update-exam-answers',
				'id' => $id,
				'ip' => util::GetIpClient(),
				'username' => $admin_info['username']
			]);
		} else{
			$jResponse = [
				'error' => 81,
				'message' =>'Dữ liệu chưa thay đổi'
			];
		}
	} else{
		$data_insert = $ExamLevelData->Insert($name, $thumb, $rewrite, $class_id, $subject_id, $level, $game_id, $play, $time, $spq, $description, $answers, $content);
		if($data_insert['_id']>0){
			$redis->delete(sprintf('exam_answer_list_%d_%d', $class_id, $subject_id));//update cache list
			$redis->delete(sprintf('exam_answer_list_%d_%d', $class_id, 0));//update cache list
			$jResponse = [
				'error' => 0,
				'message' =>'ok',
				'id' => $data_insert['_id']
			];
		} else{
			$jResponse = [
				'error' => 82,
				'message' =>'Insert không thành công'
			];
		}
	}

	echo json_encode($jResponse);
	// return $bok;
}

function Delete() {
	global $redis;

	// $bOk = false;
	$jResponse = [];
	$id=isset($_POST['id'])?intval($_POST['id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$subject_id = isset($_POST['subject_id'])?intval($_POST['subject_id']):0;
	if($id>0) {
		$ExamAnswersData = ExamAnswersData::getInstance();
		$data_delete = $ExamAnswersData->Delete($id);
		if($data_delete['n'] > 0) {
			$redis->delete(sprintf('exam_answer_info_%d', $id));//update cache info
			$redis->delete(sprintf('exam_answer_list_%d_%d', $class_id, $subject_id));//update cache list
			$redis->delete(sprintf('exam_answer_list_%d_%d', $class_id, 0));//update cache list
			$jResponse = [
				'error' => 0,
				'message' => 'ok'
			];
		} else{
			$jResponse = [
				'error' => 83,
				'message' => 'Dữ liệu chưa thay đổi'
			];
		}
	} else{
		$jResponse = [
			'error' => 80,
			'message' => 'Chưa có tham số id'
		];
	}
	echo json_encode($jResponse);
	// return $bOk;
}

function CopyData($admin_info) {
	//var_dump($admin_info);
	global $redis;
	$jResponse = [];

	$id = isset($_POST['id'])?intval($_POST['id']):0;
	$class_id = isset($_POST['class_id'])?intval($_POST['class_id']):0;
	$subject_id = isset($_POST['subject_id'])?intval($_POST['subject_id']):0;

	if($id>0 && $class_id>=0 && $class_id<=6 && $subject_id>0){
		$ExamAnswersData = ExamAnswersData::getInstance();
		$data_copy = $ExamAnswersData->CopyData($id, $subject_id, $class_id);
		if($data_copy){
			$redis->delete(sprintf('exam_answer_list_%d_%d', $class_id, $subject_id));//update cache list
			$redis->delete(sprintf('exam_answer_list_%d_%d', $class_id, 0));//update cache list
			$jResponse = [
				'error' => 0,
				'message' =>'ok'
			];
		} else{
			$jResponse = [
				'error' => 0,
				'message' =>'copy không thành công'
			];
		}
	} else{
		$jResponse = ['error' => 1, 'message' =>'chưa nhập đủ thông tin'];
	}

	echo json_encode($jResponse);
	// return $bok;
}