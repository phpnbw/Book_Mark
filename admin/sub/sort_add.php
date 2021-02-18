<?php
include_once('../../inc/global.php');
$ms=new Mysqls;

$id=isset($_POST['sort_id'])?intval($_POST['sort_id']):0;
$sort_name=isset($_POST['sort_name'])?trim($_POST['sort_name']):'';
$sort_icon=isset($_POST['sort_icon'])?trim($_POST['sort_icon']):'';
$dated=date("Y-m-d H:i:s");

if(!$sort_name || $sort_icon==''){
    echo json_encode(array('ret'=>0, 'msg'=>'数据有误'));
    die();
}

$arr=array(
	'type'=>1,
	'icon'=>$sort_icon,
	'name'=>$sort_name,
	'state'=>1,
    'dated'=>$dated,
);

if($id){
    $ms->update('sort', 'id='.$id, $arr);
    echo json_encode(array('ret'=>1, 'msg'=>'修改成功'));
}else{
    $id=$ms->insert('sort',$arr,true);
    echo json_encode(array('ret'=>1, 'msg'=>'添加成功','id'=>$id));
}


?>