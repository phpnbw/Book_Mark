<?php
include_once("../../inc/global.php");
$ms=new Mysqls;
$id=isset($_GET['id'])?intval($_GET['id']):0;
$sql="select * from `links` where `id`={$id} limit 1";
$data=$ms->getRow($sql);//标签分类数据
$url=$data['url'];
$qr_name='二维码';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<base target="_blank" />
<?php include_once('../../inc/styles.php');?>
<style type="text/css">
body{background:#fff;}
.main{margin:25px 0 0 40px;}
#qr{width:210px;font-size:0;border-radius:5px;border:1px solid #ddd;}
#qr img{width:100%;border-radius:5px;}
.cen{margin-left:87px;margin-top:10px;}
.qr_url{width:210px;margin-left: 40px;margin-top:5px;}
</style>

    <script src="../../static/js/wxqrcode.js"></script>
</head>
<body>
<div class="main">
    <div id="qr"></div>
</div>
<div class="qr_url"><textarea cols="28" rows="3" class="txt"><?php echo $url;?></textarea></div>
<div class="cen"><input type="button" class="layui-btn" value="下载二维码" onclick="downQr('<?php echo urldecode($url);?>','<?php echo $qr_name;?>')"></div>
<div id="qrcode" style="display:none;"></div>
<script type="text/javascript">
var link='<?php echo urldecode($url);?>';

function showQR(){
    var url=createQrCodeImg(link, {'size':120,'errorCorrectLevel':'L'});
    $("#qr").html('<img width="200" src="'+url+'" />');
}
window.onload=function(){
    showQR();
}

function downQr(link, name) {
    jQuery('#qrcode').qrcode({
        render: "canvas",
        width: 200,
        height: 200,
        text: link
    });
    setTimeout(function(){
        var canvas = $('#qrcode').find("canvas").get(0);
        var a = document.createElement("a");
        a.href = canvas.toDataURL();
        a.download = name;
        a.click();
        $('#qrcode').html('');
    },100);
}
</script>
</body>
</html>