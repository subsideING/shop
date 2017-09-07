<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
use \Think\Controller;
class AdminController extends Controller 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin/Admin');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
        $roleModel = M('Role');
        $roleData = $roleModel->select();
		$this->assign(array(
            'roleData' => $roleData,
            'page_name' =>'添加管理员', 
            'page_title' =>'管理员列表', 
            'page_link' =>U('lst?p='.I('get.p'))
            ));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Admin/Admin');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
        //根据id查询出单条数据
    	$model = M('Admin');
    	$data = $model->find($id);
    	$this->assign('data', $data);
        //查询该管理员拥有的所有的角色
        $rid = M('AdminRole')->field('GROUP_CONCAT(role_id) rid')->where(array('admin_id'=>array('eq',$id)))->find();
        $rid = explode(',', $rid['rid']);
        //查询出所有的角色
        $roleModel = M('Role');
        $roleData = $roleModel->select();
		$this->assign(array(
            'rid' => $rid,
            'roleData' => $roleData,
            'page_title' =>'添加管理员', 
            'page_name' =>'管理员列表', 
            'page_link' =>U('lst?p='.I('get.p'))));
		$this->display();
    }
    public function delete()
    {
    	$model = D('Admin/Admin');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin/Admin');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		$this->assign(array('page_title' =>'管理员列表', 'page_name' =>'添加管理员', 'page_link' =>U('add')));
    	$this->display();
    }
}