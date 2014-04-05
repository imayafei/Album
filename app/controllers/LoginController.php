<?php
/**
 * 简述： 登录模块。
 * 
 * <code>LoginController<code/> 继承自 <code>Phalcon\Mvc\Controller</code> 类
 * 
 * @author hongker
 * @version 1.0
 * @copyright Powerd by hongker
 * @package LoginController
 *
 */
class LoginController extends \Phalcon\Mvc\Controller {
	/**
	 * 显示登陆页面
	 * 
	 * @name index
	 * 
	 */
	public function indexAction() {
		
	}
	
	/**
	 * 获取用户输入数据，并进行登录验证
	 * 使用参数绑定的方式从数据库获取数据，防止SQL注入
	 * 
	 * @name auth
	 * 
	 */
	public function authAction() {
		/**
		 * 保存用户名和密码的数组
		 */
		$params = array(
			1 => $this->request->getPost('username','string'),
			2 => md5($this->request->getPost('password','string')),
		);
		
		
		
		/**
		 * 验证条件：匹配用户名和密码
		 */
		$conditions = "username = ?1 AND password = ?2";
		
		/**
		 * 返回一条记录
		 * @param $user 包含用户数据的类 
		 */
		$user = Users::findFirst(array(
			$conditions,
			"bind" => $params,
		));
		
		

		/**
		 * 根据是否成功获取到数据来判断用户是否合法
		 */
		if(!is_null($user->userid)) {
			//todo 显示主页
			
			//将用户Id存入session
			$this->session->set("username",$user->username);
			
			//跳转到已登录验证过的主页
			$this->dispatcher->forward(array(
				'controller' => 'index',
				'action' => 'index',
			));
			
		}else {
			//登录失败，跳转至登录页面，重新登录。
			
			$this->flashSession->error("用户名或密码输入不正确,登陆失败,请重新输入.");
			
			$this->dispatcher->forward(array(
				'action' => 'index',
			));
			
		}
		
	}
	
	/**
	 * @name filter
	 * 过滤用户输入的数据
	 */
	private function filter() {
		
	}
}