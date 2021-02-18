<?php 

function getPage($total,$page,$limit,$param=null){
        $total_page=ceil($total/$limit);
        $start_page=$page-3;
        $end_page=$page+5;
        if($start_page<1){
                $start_page=1;
        }
        
        if($end_page>$total_page){
                $end_page=$total_page;
        }
        if($total>$limit){
        $html="<div id=\"pager\"><a href=\"?page=1{$param}\" target=\"_self\">首页</a> ";
        
        for($pg=$start_page;$pg<$end_page;$pg++){
                if($page==$pg){
                        $html.="<span>{$pg}</span> ";
                }else{
                        $html.="<a href=\"?page={$pg}{$param}\" target=\"_self\">{$pg}</a> ";
                }
        }
        $npage=$page+1;
        if($npage<=$total_page){
                $html.="<a href=\"?page={$total_page}{$param}\" target=\"_self\">...".$total_page."</a>";
                $html.="<a href=\"?page={$npage}{$param}\" target=\"_self\">下一页</a> ";
        }else{$html.="<span>{$total_page}</span>";}
                
        //$html.="e        ;
        $html.='</div>';
        }else
        {
                $html="";
        }
        return $html;
}
 ?>