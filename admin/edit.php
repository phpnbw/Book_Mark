<?php
include_once("../inc/global.php");
require_once('MkEncrypt.php');
$ms=new Mysqls;
$id=isset($_GET['id'])?intval($_GET['id']):0;
if ($id) {
    $sql="select * from `links` where `id`={$id} and `state`=1 limit 1";
    $data=$ms->getRow($sql);
}
$sql="select * from `sort` where `state`=1 limit 30";
$data_mark=$ms->getRows($sql);//标签分类数据
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $id?'编辑':'新增';?>书签链接</title>
<?php include_once('../inc/styles.php');?>
<link rel="stylesheet" href="../static/css/layui.css">
    <script src="../static/layui/layui.js"></script>
<script src="module/common.js"></script>
<style type="text/css">
.w100{width:150px;}
.w200{width:250px;}
.w600{width:600px;}
.w400{width:400px;}
.sort .layui-form-select{width: 120px;display: inline-block;}
.layui-btn{margin-left: 20px;height: 35px; line-height: 35px;}
.sel{vertical-align: middle;}
#passwd{display: none;}
.hide {display: none;}
</style>
</head>
<body>
<nav><?php echo $id?'编辑':'新增';?>书签链接</nav>
<div class="formbox">
<form name="myform" class="layui-form" action="" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0" id="table">
        <tr class="sort">
			<td width="80"><div class="l">分类</div></td>
			<td class="sel">
				<select id="links_sort" lay-filter="group">
				<?php foreach ($data_mark as $k => $v) {?>
					<option value="<?php echo $v['id']?>"<?php if($data_mark['sort_id']==$v['id']){echo "selected";}?>><?php echo $v['name'];?></option>
				<?php }?>
                </select>
				<input class="layui-btn layui-btn-normal" type="button" onclick="sortAdd()" value="新增分类" />
                
			</td>
        </tr>
        
		<tr>
			<td><div class="l">名称</div></td>
			<td>
				<input type="text" class="txt w100" name="url_name" id="url_name" autocomplete="off" value="<?php echo $data['name'];?>" />
			</td>
		</tr>
		
		<tr>
			<td><div class="l">图标</div></td>
			<td>
				<input type="text" id="iconPicker" lay-filter="iconPicker" value="<?php echo $data['icon'];?>" class="hide">
			</td>
        </tr>

        <tr>
			<td><div class="l">链接</div></td>
			<td>
				<input type="text" class="txt w400" id="url" value="<?php echo $data['url'];?>" autocomplete="off"/>
			</td>
        </tr>
        
        <tr>
			<td><div class="l">介绍</div></td>
			<td>
				<input type="text" class="txt w600" id="url_text" value="<?php echo $data['text'];?>" autocomplete="off"/>
			</td>
        </tr>

		<tr>
			<td><div class="l">加密</div></td>
			<td>
				<input type="radio" name='pwd' value="0" title="否" checked />
				<input type="radio" name='pwd' value="1" title="是" />
			</td>
        </tr>

		<tr id="passwd">
			<td><div class="l">密码</div></td>
			<td>
				<input type="text" class="txt w100" id="url_pwd" value="<?php echo $data['pwd'];?>" autocomplete="off"/>
			</td>
        </tr>

        <tr>
			<td>&nbsp;</td>
			<td>
				<div id="substr"><input type="button" name="sub" class="sub" value="提 交" onclick="editRebate()" /></div>
			</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td></td>
		</tr>

		<div id="layer-modal" style="display: none;">
			<div style="padding: 18px;">
				<input type="text" id="iconPicker3" lay-filter="iconPicker3" value="layui-icon-face-smile-fine" class="layui-input">  
				<br>
				<input type="text" id="iconPicker3-2" lay-filter="iconPicker3-2" class="layui-input">  
				<button id="btnSubmit3-2" class="layui-btn">获取当前值</button>
			</div>
		</div>
	</table>
</form>
</div>
<script type="text/javascript">
var	gid=<?php echo $id;?>;
var glock=false;
var from;
var url_isPwd=0;
//监控单选值改变
layui.use(['layer', 'form','iconPicker'], function() {
	form = layui.form;
	var layer = layui.layer,
	iconPicker = layui.iconPicker,
	$ = layui.$;
	form.render();
	
	//监控单选值改变
	form.on('radio', function(data) {
		if(data.value==0){
			$('#passwd').hide();
			var url_isPwd=0;
		}else{
			$('#passwd').show();
			var url_isPwd=1;
		}
	});

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


function editRebate(){
	if(glock==true){
		return false;
	}
	var name =$('#url_name').val();
	var sort =$('#links_sort').val();
	var text =$('#url_text').val();
	var link =$('#url').val();
	var sort_icon =$('#iconPicker').val();
	var url_pwd =$('#url_pwd').val();//密码

	if(url_isPwd==1 && url_pwd==''){
		layer.msg('请输入密码');
		return false;
	}
	if(name==''){
		layer.msg('名称不能为空');
		return false;
	}

    if(url==''){
		layer.msg('链接不能为空');
		return false;
	}
	url='sub/add.php?rnd='+Math.random();
	datas={
	    name:name,
	    sort:sort,
	    text:text,
        link:link,
        id:gid,
        sort_icon:sort_icon,
        url_pwd:url_pwd,
        url_isPwd:url_isPwd,
	 };
	glock=true;
	$.post(url,datas,function(d){
        console.log(url);
		glock=false;
	    if(d.ret==1){
	        layer.msg(d.msg)
        	setTimeout(function () {
            	// self.opener.location.reload();
            	// window.close();
        	}, 1500);
	    }else{
	        layer.msg(d.msg);
	    }
	},'json');
}

//直接在页面弹窗增加品牌
function sortAdd(){
	layer.closeAll();
    layer.open({
        type: 2,
        title: '添加品牌',
        shadeClose: true,
        shade: 0.8,
        area: ['610px', '550px'],
        content: ['sort_add.php','no'],  //iframe的url
    });
}

function refreshBrand(id,name){
	$("#links_sort").append('<option value="'+id+'" selected>'+name+'</option>');
	form.render('select');//添加了节点后要重新渲染
}

window.onload=function(){

}

</script>
</body>
</html>