<?php
include_once("inc/global.php");
$ms=new Mysqls();
$id=isset($_POST['id'])?intval($_POST['id']):0;

if(!$id){
    echo json_encode(array('ret'=>0,'msg'=>'数据有误'));
    die();
}
$sql="select * from `links` where `id`={$id} and `state`=1 limit 1";
$data=$ms->getRow($sql);
if($data){
    $p=array(
        'ret'=>1,
        'msg'=>'ok',
        'url'=>$data['url'],
    );
}else{
    $p=array(
        'ret'=>0,
        'msg'=>'数据有误',
    );
}

echo json_encode($p);

?>