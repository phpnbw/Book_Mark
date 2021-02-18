<?php
include_once("inc/global.php");
$ms=new Mysqls();

$sql="select * from `sort` where `state`=1 limit 20";
$data=$ms->getRows($sql);//标签分类数据

foreach($data as $k=>$v){
    $sql="select * from `links` where `state`=1 and `sort_id`={$v['id']} limit 50";
    $tmp=$ms->getRows($sql);
    $data[$k]['data_url']=$tmp;
}

?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <title>网页书签工具</title>
    <meta name="keywords" content="书签工具箱">
    <meta name="description" content="书签工具箱">
    <link href="./static/css/bootstrap.min.css" rel="stylesheet" />
    <script src="./static/js/jquery.min.js"></script>
    <script src="./static/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="static/css/layui.css" media="all">
    <style>
        .layui-card-header{text-align: center;}
        .layui-col-md2{ text-align: center; }
        .layui-quote-nm{border-width: 4px 4px;border-color: #01aaed;}
    </style>
</head>

<body>
    <div style="padding: 4%; background-color: #F2F2F2;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12" style="text-align: center;">
                <div class="layui-card">
                    <div class="layui-card-header"><h3>网页书签工具</h3></div>
                        <div class="panel-body" style="text-align: center;">
                            <blockquote class="layui-elem-quote layui-quote-nm">仅供娱乐，请勿用于违法途径</blockquote>
                            <input type="text" id="uin" value="0" class="hide">
                            <input type="text" id="skey" value="0" class="hide">
                            <input type="text" id="pskey" value="0" class="hide">
                            <input type="text" id="superkey" value="0" class="hide">
                        </div>
                    </div>
                </div>

                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                            <ul class="layui-tab-title">
                                <?php foreach($data as $k=>$v){?>
                                    <li class="<?php if($k==0){echo 'layui-this';}?> layui-icon <?php echo $v['icon'];?>"><?php echo $v['name'];?></li>
                                <?php }?>
                            </ul>

                            <div class="layui-tab-content">
                                    <?php foreach($data as $k=>$v){?>
                                    <div class="layui-tab-item <?php if($k==0){echo 'layui-show';}?>">
                                        <div style="padding: 20px; background-color: #F2F2F2;">
                                            <div class="layui-row layui-col-space15">

                                            <?php foreach($v['data_url'] as $m=>$d){?>
                                                <div class="layui-col-md3">
                                                    <div class="layui-card">
                                                        <div class="layui-card-header"><i class="layui-icon <?php echo $d['icon'];?>"></i> <?php echo $d['name'];?></div>
                                                        <div class="layui-card-body">
                                                            <div class="layui-row">
                                                                <div class="layui-col-md6"><?php echo $d['text'];?></div>
                                                                <div class="layui-col-md2"><button type="button" class="layui-btn" onclick="getUrl(<?php echo $d['id'];?>,<?php echo $d['is_pwd'];?>);">点击访问</button></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }?>

                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-col-md12" style="text-align: center;">
                <div class="layui-card">
                    <div class="layui-card-header">技术支持:
                        <a href="javascript:;" target="_blank"><img id="ico" src="static/picture/get-httpswww.cfhezi.com.jpg" width="16px"> PHP-NBW</a>
                    </div>
                </div>
            </div>

        </div>
    </div> 
<script src="static/js/layui/layui.js" ></script>
<script>
layui.use(['layer', 'element'], function(){
    var $ = layui.jquery
    ,element = layui.element
    ,layer = layui.layer;
});

function getUrl(id,is_pwd) {
    if(is_pwd == 0){
        layer.msg('跳转中...', {icon: 16});
        var url='./api.php?rand='+Math.random();
        $.post(url,{id:id},function(d){
            openUrl(d.url);
        },'json')
    }else{
        //输入密码
        enterPwd();
    }
}

function enterPwd(){

    //弹窗输入密码

    layer.msg('处理中...', {icon: 16});

}

function openUrl(url) {
    window.open(url)
}
</script>    
</body>
</html>