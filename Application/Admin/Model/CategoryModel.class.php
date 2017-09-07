<?php
namespace Admin\Model;

use Think\Model;

class CategoryModel extends Model
{
	//设置允许接受的字段
	protected $insertFields = 'cat_name,parent_id';
	//设置表单数据验证规则
	protected $_validate = array(
		array('cat_name','require','分类名称不能为空',1),
	);
	//获取树形数据结构
	public function getTree()
	{
		$data = $this->select();
		return $this->_getTree($data);
	}
	//递归排序成树状
	private function _getTree($data,$parent_id = 0 , $level = 0)
	{
		static $ret = array();
		foreach ($data as $key => $value) {
			if($value['parent_id'] == $parent_id){
				$value['level'] = $level;
				$ret[] = $value;
				$this->_getTree($data,$value['id'],$level+1);
			}
		}
		return $ret;
	}
	protected function getChildren($catId)
	{
		$data = $this->select();
		return $this->_getChildren($data,$catId);
	}
	private function _getChildren($data,$parent_id)
	{
		static $ret = array();
		foreach ($data as $key => $value) {
			if($value['parent_id'] == $parent_id){
				$ret[] = $value;
				$this->_getChildren($data,$value['id']);
			}
		}
		return $ret;
	}
	protected function _before_delete($option)
	{
		$children = $this->getChildren($option['where']['id']);
		if($children){
			$idArrr = array_column($children,'id');
			$ids = implode(',', $idArrr);
			$sql = "DELETE FROM jxshop_category WHERE id IN($children)";
			$this->execute($sql);
		}
	}
}