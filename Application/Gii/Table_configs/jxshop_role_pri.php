<?php
return array(
	'tableName' => 'jxshop_role_pri',    // 表名
	'tableCnName' => '角色拥有的权限',  // 表的中文名
	'moduleName' => 'Admin',  // 代码生成到的模块
	'digui' => 0,             // 是否无限级（递归）
	'diguiName' => '',        // 递归时用来显示的字段的名字，如cat_name（分类名称）
	'pk' => 'id',    // 表中主键字段名称
	/********************* 要生成的模型文件中的代码 ******************************/
	'insertFields' => "array('role_id','pri_id')",
	'updateFields' => "array('id','role_id','pri_id')",
	'validate' => "
		array('role_id', 'require', '角色Id不能为空！', 1, 'regex', 3),
		array('role_id', 'number', '角色Id必须是一个整数！', 1, 'regex', 3),
		array('pri_id', 'require', '权限Id不能为空！', 1, 'regex', 3),
		array('pri_id', 'number', '权限Id必须是一个整数！', 1, 'regex', 3),
	",
	/********************** 表中每个字段信息的配置 ****************************/
	'fields' => array(
		'role_id' => array(
			'text' => '角色Id',
			'type' => 'text',
			'default' => '',
		),
		'pri_id' => array(
			'text' => '权限Id',
			'type' => 'text',
			'default' => '',
		),
	),
	/**************** 搜索字段的配置 **********************/
	'search' => array(
		array('role_id', 'normal', '', 'eq', '角色Id'),

	),
);