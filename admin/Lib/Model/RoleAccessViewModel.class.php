<?php
/*
* 权限视图模式
* @author zuihuanxiang@gmail.com
* @create 2013-07-18
*/
class RoleAccessViewModel extends ViewModel{
	public $viewFields = array(
		'Role'=>array('id'=>'r_id','name'=>'r_name'),
		'Access'=>array('role_id','node_id','level','pid','_on'=>'Role.id=Access.role_id'),
		'Node'=>array('id'=>'n_id','name'=>'n_name','title'=>'n_title','status'=>'n_status','pid'=>'n_pid','level'=>'n_level','_on'=>'Access.node_id=Node.id'),

		);
}
?>