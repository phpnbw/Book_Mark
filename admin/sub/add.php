<?php

include_once('../../inc/global.php');
$ms=new Mysqls;

$id=isset($_POST['id'])?intval($_POST['id']):0;
$sort=isset($_POST['sort'])?intval($_POST['sort']):1;
$name=isset($_POST['name'])?trim($_POST['name']):'';
$text=isset($_POST['text'])?trim($_POST['text']):'';
$url=isset($_POST['link'])?trim($_POST['link']):'';
$sort_icon=isset($_POST['sort_icon'])?trim($_POST['sort_icon']):'';
$url_pwd=isset($_POST['url_pwd'])?trim($_POST['url_pwd']):'';
$url_isPwd=isset($_POST['url_isPwd'])?intval($_POST['url_isPwd']):1;
$dated=date("Y-m-d H:i:s");

if(!$sort || $url=='' || $name==''){
    echo json_encode(array('ret'=>0, 'msg'=>'数据有误'));
    die();
}

$arr=array(
	'name'=>$name,
	'sort_id'=>$sort,
	'text'=>$text,
	'url'=>$url,
    'icon'=>$sort_icon,
    'is_pwd'=>$url_isPwd,
    'pwd'=>$url_pwd,
    'dated'=>$dated,
);

if($id){
    $ms->update('links', 'id='.$id, $arr);
    echo json_encode(array('ret'=>1, 'msg'=>'修改成功'));
}else{
    $arr['state']=1;
    $arr['type']=1;
    $id=$ms->insert('links',$arr,true);
    echo json_encode(array('ret'=>1, 'msg'=>'添加成功'));

}


?>