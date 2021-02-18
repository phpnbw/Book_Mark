<?php
include_once('../../inc/global.php');
$ms=new Mysqls;
$id=isset($_POST['id'])?intval($_POST['id']):0;
if(!$id){
    echo json_encode(array('ret'=>0, 'msg'=>'数据有误'));
}
$arr=array('state'=>0);
$ms->update('links', 'id='.$id, $arr);
echo json_encode(array('ret'=>1, 'msg'=>'删除成功'));
?>