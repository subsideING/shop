<?php
namespace Admin\Model;

use Think\Model;

class GoodsModel extends Model
{
	//设置允许接受的字段
	protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,goods_desc';
	//设置表单数据的验证规则
	protected $_validate = array(
		array('goods_name','require','商品名称不能为空',1),
		array('market_price','require','市场价格不能为空',1),
		array('market_price','currency','市场价格必须是货币',1),
		array('shop_price','currency','本店价格必须是货币',1),
		array('shop_price','require','本店价格不能为空',1),
		//array();
	);
	//在数据添加到数据库之前自动被调用
	protected function _before_insert(&$data,$option)
	{
		//商品描述有选择性的过滤 
		$data['goods_desc'] = clearXSS($_POST['goods_desc']);

		//把时间补到表单中
		$data['addtime'] = time();
		//处理上传的图片
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] === 0){
			//上传图片
			$config = array(
			    'maxSize'    =>    2*1024*1024,    
			    'rootPath'   =>    './Public/Uploads/',    
			    'savePath'   =>    'Goods/',    
			    'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),    
			    'autoSub'    =>    false,    
			    //'subName'    =>    array('date','Ymd'),
		    );
		    $upload = new \Think\Upload($config);// 实例化上传类
		    $info = $upload->upload();
		    if($info){
		    	//生成缩略图
		    	//先取出刚刚上传成功的图片的路径和名称
		    	$logo = $info['logo']['savapath'] . $info['logo']['savename'];
		    	//拼出缩略图的名字
		    	$sm_logo = $info['logo']['savapath'] .'sm_' . $info['logo']['savename'].$data['addtime'];
		    	$mid_logo = $info['logo']['savapath'] .'mid_' . $info['logo']['savename'].$data['addtime'];
		    	//生成缩略图
		    	$image = new \Think\Image();
		    	$image->open('./Public/Uploads/Goods/'.$logo);
		    	$image->thumb(450,450)->save('./Public/Uploads/Goods/'.$mid_logo);
		    	$image->thumb(100,100)->save('./Public/Uploads/Goods/'.$sm_logo);
		    	//把生成好的图片的路径保存到表单中
		    	$data['logo'] = $logo;
		    	$data['sm_logo'] = $sm_logo;
		    	$data['mid_logo'] = $mid_logo;
		    }else{
		    	$this->error = $Uploads->getError();
		    	return false;
		    }
		}
	}
	//在修改到数据库之前自动被调用
	protected function _before_update(&$data,$option)
	{
		//商品描述有选择性的过滤 
		$data['goods_desc'] = clearXSS($_POST['goods_desc']);

		//处理上传的图片
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] === 0){
			//上传图片
			$config = array(
			    'maxSize'    =>    2*1024*1024,    
			    'rootPath'   =>    './Public/Uploads/',    
			    'savePath'   =>    'Goods/',    
			    'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),    
			    'autoSub'    =>    false,    
			    //'subName'    =>    array('date','Ymd'),
		    );
		    $upload = new \Think\Upload($config);// 实例化上传类
		    $info = $upload->upload();
		    if($info){
		    	//生成缩略图
		    	//先取出刚刚上传成功的图片的路径和名称
		    	$logo = $info['logo']['savapath'] . $info['logo']['savename'];
		    	//拼出缩略图的名字
		    	$sm_logo = $info['logo']['savapath'] .'sm_' . $info['logo']['savename'].time();
		    	$mid_logo = $info['logo']['savapath'] .'mid_' . $info['logo']['savename'].time();
		    	//生成缩略图
		    	$image = new \Think\Image();
		    	$image->open('./Public/Uploads/Goods/'.$logo);
		    	$image->thumb(650,650)->save('./Public/Uploads/Goods/'.$mid_logo);
		    	$image->thumb(130,130)->save('./Public/Uploads/Goods/'.$sm_logo);
		    	//把生成好的图片的路径保存到表单中
		    	$data['logo'] = $logo;
		    	$data['sm_logo'] = $sm_logo;
		    	$data['mid_logo'] = $mid_logo;
		    	//取出原图
		    	$oldLogo = $this->field('sm_logo,mid_logo,logo')->find($option['where']['id']);
		    	//删除原图
		    	@unlink('./Public/Uploads/Goods/'.$oldLogo['sm_logo']);
		    	@unlink('./Public/Uploads/Goods/'.$oldLogo['mid_logo']);
		    	@unlink('./Public/Uploads/Goods/'.$oldLogo['logo']);
		    }else{
		    	$this->error = $Uploads->getError();
		    	return false;
		    }
		}
	}
	protected function _before_delete(&$data,$option)
	{
		//取出原图
    	$oldLogo = $this->field('sm_logo,mid_logo,logo')->find($data['where']['id']);
    	//删除原图
    	@unlink('./Public/Uploads/Goods/'.$oldLogo['sm_logo']);
    	@unlink('./Public/Uploads/Goods/'.$oldLogo['mid_logo']);
    	@unlink('./Public/Uploads/Goods/'.$oldLogo['logo']);
	}
		
	//搜索条件
	public function search()
	{
		//搜索
		$where = array();
		if($gn = I('get.gn')){
			$where['goods_name'] = array('like',"%$gn%");
		}
		$fp = I('get.fp');
		$tp = I('get.tp');
		if($fp && $tp){
			$where['shop_price'] = array('between',array($fp,$tp));
		}elseif($fp){
			$where['shop_price'] = array('egt',$fp);
		}elseif($tp){
			$where['shop_price'] = array('elt',$tp);
		}
		$is_on_sale = I('get.is_on_sale');
		if($is_on_sale == '是' || $is_on_sale == '否'){
			$where['is_on_sale'] =  array('eq',$is_on_sale);
		}
		//分页
		//取出总的记录数
		$count = $this->where($where)->count();
		//生成分页对象
		$page = new \Think\Page($count,15);
		//生成翻页字符串
		$pageshow       = $page->show();// 分页显示输出
		$data = $this->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		return array(
			'data' => $data,
			'page' => $pageshow	
		);
	}
}