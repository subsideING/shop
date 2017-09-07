<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password');
	protected $updateFields = array('id','username','password');
	protected $_validate = array(
		array('username', 'require', '账号不能为空！', 1, 'regex', 3),
		array('username', '1,30', '账号的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 3),
		array('password', '1,32', '密码的值最长不能超过 32 个字符！', 1, 'length', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	protected function _after_insert($data, $option)
	{
		//添加管理员之后把角色和管理员的对应关系插入到数据库
		$rid = I('post.rid');
		if($rid){
			$arModel = M('AdminRole');
			foreach ($rid as $k => $v) {
				$arModel->add(array(
					'admin_id' => $data['id'],
					'role_id' => $v));
			}
		}

	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
		//插入数据库之前先MD5加密
		$data['password'] = md5($data['password']);
			
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
		
		$data['password'] = md5($data['password']);
		//修改前把原有角色和管理员的对应关系先删除，然后把修改的数据再次插入到数据库
		$arModel = M('AdminRole');
		$arModel->where(array('admin_id' => array('eq',$option['where']['id'])))->delete();
		$rid = I('post.rid');
		if($rid){
			foreach ($rid as $k => $v) {
				$arModel->add(array(
					'admin_id' =>$option['where']['id'],
					'role_id' => $v));
			}
		}

	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
		$arModel = M('AdminRole');
		$arModel->where(array('admin_id' => array('eq',$option['where']['id'])))->delete();
	}
	/************************************ 其他方法 ********************************************/
}