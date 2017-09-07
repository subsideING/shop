<?php
namespace Admin\Controller;

use Think\Controller;

class CategoryController extends Controller
{
	public function catList()
	{
		$model = D('category');
		$data = $model->getTree();
		$this->assign('data',$data);
		$this->assign(array(
			'page_title' => '商品分类列表',
			'page_link' => U('catAdd'),
			'page_name' => '商品分类添加',
		));
		$this->display();
		//dump($data);
	}
	public function catAdd()
	{
		$model = D('Category');
		if(IS_POST){
			if($model->create(I('post.'),1)){
				if($model->add()){
					$this->success('添加成功',U('catList'));
					exit;
				}
			}
			$error = $model->getError();
			$this->error($error);
		}
		//取出所有的分类
		$data = $model->getTree();
		$this->assign('data',$data);
		$this->assign(array(
			'page_title' => '商品分类添加',
			'page_link' => U('catList'),
			'page_name' => '商品分类列表',
		));
		$this->display();

	}
	//删除
	public function catDelete()
	{
		$id = I('get.id');
		$model = D('Category');
		if($model->delete($id)){
			$this->success('删除成功！');
			exit;
		}else{
			$this->error('删除失败！');
		}
	}
	//修改
	public function catEdit()
	{
		$this->display();
	}
}