<?php
include_once("../inc/global.php");
require_once('MkEncrypt.php');
$ms=new Mysqls;
$page=isset($_GET['page'])?intval($_GET['page']):1;

$limit=200;
$start=($page-1)*$limit;
$sql="select * from `sort` where `state`=1 order by `id` desc limit {$start},{$limit}";
$data=$ms->getRows($sql);

$sql="select count(*) as t from `sort` where `state`=1 limit 1";
$num=$ms->getRow($sql);
$total=$num['t'];
$pager=getPage($total, $page, $limit,'');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>分类列表</title>
<base target="_self" />
<?php include_once('../inc/styles.php');?>
<link rel="stylesheet" href="../static/css/layui.css">
    <script src="../static/layui/layui.js"></script>
    <style type="text/css">
textarea{ width:220px; height:80px; padding:6px; line-height:1.5em;}
.op{line-height:2em;}
.dated{ padding-bottom:1em;}
.preview{width:100px;height:100px;}
.position{margin:10px 20px;;}
.position div{margin-bottom:10px;}
.txt{width:80px;height:26px;line-height:26px;}
label{margin-left:10px;margin-right:5px;}
.btn{padding:5px 20px;margin-left:10px;}
.position a{font-size:12px;margin-left:10px;}
.order div{padding-bottom:5px;}
.export{position:absolute;top:85px;left:900px;}
.top{display:block;position:fixed;right:10px;bottom:140px; width:50px;height:50px; background:url(http://baby.ci123.com/qq/images/gotop.png) no-repeat;cursor:pointer}
.list .order table{box-shadow:none;}
.list .order table td{padding:5px 0;}
.nav2 .tellist{margin-left:150px;}
.nav2 .tel a{color:#FF6A28;}
.layui-btn{height: 30px; line-height: 30px;margin-left: 10px;}
.sel{vertical-align: middle;}
</style>
</head>
<body>
<div class="nav2">
    <li id='add_url'><input type="button" class='layui-btn' value="新增" onclick="window.open('sort_add.php');" /></li>
    <li id='top_url'><input type="button" class='layui-btn layui-btn-normal' value="首页" onclick="window.open('../index.php');" /></li>
</div>
<div class="list">
	<table width="100%" border="1" bordercolor="#e9eaec" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td width="30" align="center">序号</td>
				<td width="150">分类名</td>
				<td width="150">图标</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $k => $v) {?>
			<tr>
				<td><?php echo $k+1; ?></td>
				<td><?php echo $v['name'];?></td>
				<td align="right"><?php echo $v['icon'];?></td>
				<td>
					<a href="sort_add.php?id=<?php echo $v['id'];?>" target="_blank">编辑</a> &nbsp; 
					<a href="javascript:void(0);" onclick="sortDel(<?php echo $v['id'];?>,this)">删除</a>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php echo $pager;?>
</div><!--/list-->
<script type="text/javascript">
var lock=0;
layui.use('layer', function(){
    var layer = layui.layer;
});
//删除品牌
function sortDel(brand_id,obj){
    layer.confirm('确定删除该分类吗',{             
    btn: ['确定', '取消'],
    btn2:function(){}}, 
    function () {
        //点击确定
        var url='./sub/sort_del.php?rnd='+Math.random();
        if (lock==1) {
        	return false;
        }
		lock=1;
        $(obj).parents('td').html("<span style='color:red;'>已删除</span>");
        $.post(url,{id:brand_id},function(d){
            if(d.ret==1){
                layer.msg(d.msg);
                lock=0;
            }else{
                layer.msg(d.msg);
                lock=0;
                parent.location.reload();
                return false;
            }
        },'json');
    });
}
</script>
</body>
</html>
