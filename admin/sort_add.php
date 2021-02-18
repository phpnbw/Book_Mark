<?php
include_once("../inc/global.php");
require_once('MkEncrypt.php');
$ms=new Mysqls;
$id=isset($_GET['id'])?intval($_GET['id']):0;
$sql="select * from `sort` where id={$id} and `state`=1 limit 1";
$data=$ms->getRow($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加分类</title>
<?php include_once('../inc/styles.php');?>
<link rel="stylesheet" href="../static/css/layui.css">
    <script src="../static/layui/layui.js"></script>
    <script src="module/common.js"></script>
<style type="text/css">
.preview{width:150px;margin-right:10px;}
.w100{width:100px;}
.order{ padding:12px 0;color:#999;display:none;}
.order span{color:#000;}
.grep{color:#999;margin-left:10px;font-size:12px;}
.grep a{color:#0000ee;}
#imglist div{float:left;width:150px;margin-right:15px;}
#imglist div span{display:block;text-align:right;}
.toptip{background:#FF6A28;color:#fff;padding:10px 0;text-align:center;}
.tip{margin-left:20px;}
.tip a{color:#0033FF}
.formbox table tr td{text-align: left;}
.hide {display: none;}

</style>
</head>
<body>
<div class="formbox">
	<form name="myform" class="layui-form" action="" method="post" >
		<table width="100%" border="1" bordercolor="#e6e6e6" cellspacing="0" cellpadding="0">

			<tbody>
			<tr>
				<td><div class="l">分类名</div></td>
				<td><input type="text" name="title" id="sort_name" placeholder="" autocomplete="off" id="sort_name" value="<?php echo $data['name'];?>" class="txt" maxlength="50" style="width:300px;" />
				</td>
            </tr>
            <tr>
				<td><div class="l">图标</div></td>
				<td>
				    <input type="text" id="iconPicker" lay-filter="iconPicker" value="<?php echo $data['icon'];?>" class="hide">
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="button" name="Submit" value="提交" class="subbt" onclick="sortSave()" /></td>
			</tr>
			</tbody>
		</table>
        <input type="hidden" id="sort_id" name="id" value="<?php echo $id;?>" />
    </form>
    <div id="layer-modal" style="display: none;">
        <div style="padding: 18px;">
            <input type="text" id="iconPicker3" lay-filter="iconPicker3" value="layui-icon-face-smile-fine" class="layui-input">  
            <br>
            <input type="text" id="iconPicker3-2" lay-filter="iconPicker3-2" class="layui-input">  
            <button id="btnSubmit3-2" class="layui-btn">获取当前值</button>
        </div>
    </div>
</div>
<script type="text/javascript">
var layer,form;
var lock=0;
layui.use(['layer','form','iconPicker'], function() {
	layer= layui.layer;
	form = layui.form;
    form.render();
    var iconPicker = layui.iconPicker,
    $ = layui.$;
    
    iconPicker.render({
		// 选择器，推荐使用input
		elem: '#iconPicker',
		// 数据类型：fontClass/unicode，推荐使用fontClass
		type: 'fontClass',
		// 是否开启搜索：true/false，默认true
		search: true,
		// 是否开启分页：true/false，默认true
		page: true,
		// 每页显示数量，默认12
		limit: 24,
		// 点击回调
		click: function (data) {
			console.log(data);
		},
		// 渲染成功后的回调
		success: function(d) {
			console.log(d);
		}
	});
});
function sortSave(){
	sort_name=$('#sort_name').val();
	sort_icon=$('#iconPicker').val();
	sort_id=$('#sort_id').val();
	if (sort_name=="") {
		alert('分类名不能为空');
		return false;
    }
    if (sort_icon=="") {
		alert('图标不能为空');
		return false;
	}
	url='./sub/sort_add.php?rnd='+Math.random();
	if (lock==1) {
		return false;
	}
	lock=1;
	$.post(url,{sort_id:sort_id,sort_name:sort_name,sort_icon:sort_icon},function(d){
	    if(d.ret==1){
	        layer.msg(d.msg);
	        lock=0;
		    id=d.id;
			parent.refreshBrand(id,sort_name);
			parent.layer.close(parent.layer.getFrameIndex(window.name));
	    }else{
	        layer.msg(d.msg);
	        lock=0;
	        return false;
	    }
	},'json');
}
</script>
</body>
</html>
