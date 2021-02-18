<?php
include_once("../inc/global.php");
require_once('MkEncrypt.php');
$ms=new Mysqls;
$s_id=isset($_GET['s_id'])?intval($_GET['s_id']):1;

$sql="select * from `sort` where `state`=1 limit 30";
$data=$ms->getRows($sql);//标签分类数据

foreach($data as $k=>$v){
    $sql="select count(*) as t from `links` where `sort_id`={$v['id']} and `state`=1 limit 1";
    $tmp=$ms->getRow($sql);
    $data[$k]['link_num']=$tmp['t'];
}

$sql="select * from `links` where `state`=1 and `sort_id`={$s_id} limit 100";
$data_url=$ms->getRows($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>书签列表</title>
<?php include_once('../inc/styles.php');?>
<link rel="stylesheet" href="../static/css/layui.css">
    <script src="../static/layui/layui.js"></script>
    <style type="text/css">
textarea{ width:290px; height:80px; padding:6px; line-height:1.5em;}
.nav2 .tellist{margin-left:150px;}
.nav2 .tel a{color:#FF6A28;}
.layui-btn{height: 30px; line-height: 30px; margin-left: 10px;}
.text{width: 290px;height: 70px;}
.a_col:visited {color: red;}
tbody tr td div{margin-top: 8px;}
#add_url{margin-left:20px;}
#top_url{margin-left:20px;}
.mon_msg{border-bottom:0px solid black}
a{color:#0000ee;text-decoration:none;}
a:hover{text-decoration:underline;}

</style>
</head>
<body>
<div class="nav2">
    <?php foreach($data as $k=>$v){?>
        <li <?php if($v['id']==$s_id){echo 'class="on"';}?> ><a href="index.php?s_id=<?php echo $v['id'];?>" target="_self"><?php echo $v['name']; echo $v['link_num']>0?'（'.$v['link_num'].'）':'';?></a></li>
    <?php }?>
    <li id='add_url'><input type="button" class='layui-btn' value="分类管理" onclick="window.open('sort_list.php');" /></li>
    <li id='add_url'><input type="button" class='layui-btn' value="新增" onclick="window.open('edit.php');" /></li>
    <li id='top_url'><input type="button" class='layui-btn layui-btn-normal' value="首页" onclick="window.open('../index.php');" /></li>

</div>

<div class="list">
	<table width="100%" border="1" bordercolor="#e9eaec" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td width="30" align="center">序号</td>
				<td width="60">图标</td>
                <td width="100">名称</td>
                <td width="220">介绍</td>
                <td width="200">链接</td>
                <td width="100">密码</td>
				<td width="80">添加时间</td>
				<td >操作</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data_url as $m => $d) {?>
			<tr>
				<td align="center"><?php echo $m+1;?></td>
				<td><i class="layui-icon <?php echo $d['icon']?>"></i></td>
				<td><?php echo $d['name']?></td>
                <td><?php echo $d['text'];?></td>
                <td><?php echo $d['url'];?></td>
                <td><?php echo $d['pwd'];?></td>
                <td><?php echo $d['dated'];?></td>
                <td>
					<div>
						<a href="javascript:void(0);" onclick="goUrl('<?php echo $d['url'];?>')" target="_self">跳转链接</a>
						<a href="javascript:void(0);" onclick="fromLink('<?php echo $d['id'];?>')" target="_self">二维码</a>
						<a href="javascript:void(0);" onclick="urlEdit(<?php echo $d['id'];?>)" target="_self">编辑链接</a>
						<a href="javascript:void(0);" onclick="urlDel(<?php echo $d['id'];?>,this)" target="_self">删除</a>
					</div>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>

</div><!--/list-->
<script type="text/javascript">
var lock=0;
layui.use(['laydate','layer'],function(){
	var laydate = layui.laydate;
	var layer = layui.layer;
	laydate.render({
		elem: '#sdate',
    	range: true,
	});
});
function urlDel(id,obj){
    layer.confirm('确定删除该书签链接吗', {
    btn: ['确定', '取消'],
    btn2:function(){}},
    function () {
        //点击确定
        var url='./sub/del.php?rnd='+Math.random();
        if (lock==1) {
        	return false;
        }
		lock=1;
        $.post(url,{id:id},function(d){
			lock=0;
            if(d.ret==1){
				layer.msg(d.msg);
				$(obj).parents('td').html("<span style='color:red;'>已删除</span>");
            }else{
                layer.msg(d.msg);
            }
        },'json');
    });
}

function goUrl(url){
    window.open(url);
}

function urlEdit(id){
    window.open("./edit.php?id="+id);
}

function remarkEdit(id,v){
	// remark=$('#remark'+id).val();
	var url='./sub/remark_edit2.php?rnd='+Math.random();
	if (lock==1) {return false;}
	lock=1;
	$.post(url,{id:id,remark:v},function(d){
        if(d.ret==1){
            layer.msg(d.msg);
            lock=0;
        }else{
            layer.msg(d.msg);
            lock=0;
            return false;
        }
    },'json');
}

//链接弹窗
function fromLink(id){
    layer.open({
        type: 2,
        title:['微信扫码查看','font-weight:bold;'],
        content: ['./sub/qr_code.php?id='+id, 'no'],
        area: ['290px','390px'],
        success: function(){$(".layui-layer-close").attr("target","_self")}
    });
}
</script>
</body>
</html>