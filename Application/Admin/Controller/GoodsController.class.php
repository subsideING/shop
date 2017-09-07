<?php
namespace Admin\Controller;

use Think\Controller;

class GoodsController extends Controller
{
	public function goodsAdd()
	{
		//判断表单是否提交
		if(IS_POST){
			$model = D('Goods');
			//dump($_POST);die;
			if($model->create(I('post.'),1)){
				//插入数据库
				if($model->add()){
					//提示成功，并在1秒后跳转lst方法
					$this->success('添加成功',U('goodsList'));
					exit;
				}
			}
			//获取失败的原因
			$error = $model->getError();
			//打印错误信息
			$this->error($error);
		}
		$this->assign(array(
			'page_title' => '商品添加',
			'page_link' => U('goodsList'),
			'page_name' => '商品列表',
		));
		$this->display();//显示模版
	}
	public function goodsList()
	{
		$model = D('Goods');
		$data = $model->search();
		//dump($data);
		$this->assign('data',$data['data']);
		$this->assign('page',$data['page']);
		$this->assign(array(
			'page_title' => '商品列表',
			'page_link' => U('goodsAdd'),
			'page_name' => '添加商品',
		));
		$this->display();
	}
	public function goodsEdit()
	{
		$id = I('get.id');//要修改的id
		$model = D('Goods');
		//判断是否提交了表单
		if(IS_POST){
			//dump($model->create(I('post.'),2));die;
			if($model->create(I('post.'),2)){
			//dump($model->save());die;				
				if($model->save()){
					//提示成功，并在1秒后跳转lst方法
					$this->success('修改成功',U('goodsList'));
					exit;
				}else{
					$this->error('修改失败');
					exit;
				}
			}
			//获取失败的原因
			$error = $model->getError();
			//打印错误信息
			$this->error($error);
		}
		$info = $model->find($id);
		$this->assign('info',$info);
		$this->assign(array(
			'page_title' => '商品修改',
			'page_link' => U('goodsList'),
			'page_name' => '添加列表',
		));
		$this->display();
	}
	//删除商品
	public function goodsDelete()
	{
		//要接收的id
		$id = I('get.id');
		$model = D('Goods');
		if($model->delete($id)){
			$this->success('删除成功');
			exit();
		}else{
			$this->error('删除失败');
		}
	}
}