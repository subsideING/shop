<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - <?php echo $page_title ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="<?php echo $page_link; ?>"><?php echo $page_name; ?></a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $page_title; ?> </span>
    <div style="clear:both"></div>
</h1>


<div class="form-div">
    <form action="<?php echo U('goodsList')?>" name="searchForm">
        <img src="/Public/Admin/Images/icon_search.gif" width="26" height="22" border="0" alt="search" />
        <!-- 分类 -->
        <select name="cat_id">
            <option value="0">所有分类</option>
            <?php if(is_array($cat_list)): foreach($cat_list as $key=>$val): ?><option value=""></option><?php endforeach; endif; ?>
        </select>
        <!-- 品牌 -->
        <select name="brand_id">
            <option value="0">所有品牌</option>
            <?php if(is_array($brand_list)): foreach($brand_list as $key=>$val): ?><option value=""></option><?php endforeach; endif; ?>
        </select>
        <!-- 推荐 -->
        <select name="intro_type">
            <option value="0">全部</option>
            <option value="is_best">精品</option>
            <option value="is_new">新品</option>
            <option value="is_hot">热销</option>
        </select>
        <!-- 上架 -->
        是否上架
        <select name="is_on_sale">
            <option value='0' <?php if(I('get.is_on_sale',0) == 0) echo 'selected="selected"'?>>全部</option>
            <option value="是" <?php if(I('get.is_on_sale') == "是") echo 'selected="selected"'?>>上架</option>
            <option value="否" <?php if(I('get.is_on_sale') == "否") echo 'selected="selected"'?>>下架</option>
        </select>
        <!-- 关键字 -->
        商品名称 <input type="text" name="gn" size="15" value="<?php echo I('get.gn')?>" />
        商品价格 从<input type="text" name="fp" size="15" value="<?php echo I('get.fp')?>" />
                 到<input type="text" name="tp" size="15" value="<?php echo I('get.tp')?>" />
        <input type="submit" value=" 搜索 " class="button" />
    </form>
</div>

<!-- 商品列表 -->
<form method="post" action="" name="listForm" onsubmit="">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>编号</th>
                <th>商品名称</th>
                <th>logo</th>
                <th>商品价格</th>
                <th>市场价格</th>
                <th>是否上架</th>
                <th>操作</th>
            </tr>
            <?php foreach($data as $k=>$v): ?>
            <tr>
                <td align="center"><?php echo $v['id']; ?></td>
                <td align="center"><?php echo $v['goods_name']; ?></td>
                <td align="center">
                    <img src="/Public/Uploads/Goods/<?php echo $v['sm_logo']; ?>">
                    
                </td>
                <td align="center"><?php echo $v['shop_price']; ?></td>
                <td align="center"><?php echo $v['market_price']; ?></td>
                <td align="center"><?php echo $v['is_on_sale']; ?></td>
                <td align="center">
                    <a href="<?php echo U('goodsEdit?id='.$v['id'])?>" title="编辑">编辑</a>
                    <a href="<?php echo U('goodsDelete?id='.$v['id'])?>" title="删除" onclick = "return confirm('确定要删除吗？')">删除</a>
                </td>
                
            </tr>
            <?php endforeach?>
        </table>

    <!-- 分页开始 -->
        <table id="page-table" cellspacing="0">
            <tr>
                <td width="80%">&nbsp;</td>
                <td align="center" nowrap="true">
                    <!-- <<?php echo ($showPage); ?>> -->
                    <div id="turn-page"><?php echo $page ;?></div>
                    
                </td>
            </tr>
        </table>
    <!-- 分页结束 -->
    </div>
</form>



<div id="footer">
共执行 7 个查询，用时 0.028849 秒，Gzip 已禁用，内存占用 3.219 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>