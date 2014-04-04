<?php
/**
 * 简述： 注册模块。
 * 
 * <code>RegisterController<code/> 继承自 <code>Phalcon\Mvc\Controller</code> 类
 * 
 * @author hongker
 * @version 1.0
 * @copyright Powerd by hongker
 * @name RegisterController
 *
 */

class RegisterController extends \Phalcon\Mvc\Controller {
	/**
	 * 显示注册页面
	 */
	public function indexAction() {
		
	}
	
	/**
	 * 向数据库中插入一条新数据
	 */
	public function addAction() {
		/**
		 * 对数据进行过滤并保存至数组中
		 * @param Array $user 
		 */
		$input['email'] = $this->request->getPost('email','email');
		$input['username'] = $this->request->getPost('username','string');
		$input['password'] = $this->request->getPost('password','string');
		$input['repeatpassword'] = $this->request->getPost('repeatpassword','string');
		
		// 如果未通过输入检测，则跳转至注册页面
		if(!$this->checkInput($input)) {
			$this->dispatcher->forward(array(
					'action' => 'index',
			));
		}
		
		$this->addUser($input);
		
	}
	
	/**
	 * 用事务机制同时将记录插入 users表和userinfo表
	 * 保证userid同步
	 * @param array $input 
	 */
	private function addUser(Array $input) {
		
	}
	
	/**
	 * 对用户输入数据进行检查
	 * 分别包括：
	 * 		1.填写内容是否为空
	 * 		2.填写内容是否合法
	 * 		3.用户名是否已经存在
	 * 		4.两次输入密码是否一致
	 * @param array $array
	 * @return boolean
	 */
	private function checkInput(Array $array) {
		
		//检查输入是否为空
		if(empty($array['email'])) {
			$this->flashSession->error("邮件不能为空!");
			return false;
		}else if(empty($array['username'])) {
			$this->flashSession->error("昵称不能为空!");
			return false;
		}else if(empty($array['password'])) {
			$this->flashSession->error("密码不能为空!");
			return false;
		}else if(empty($array['repeatpassword'])) {
			$this->flashSession->error("密码输入不一致!");
			return false;
		}
		
		//检查输入内容是否合法
		
		//检查用户名是否存在
		$user = Users::findFirst(array("username='".$array['username']."'"));
		
		if(!empty($user->userid)) {
			$this->flashSession->error("此用户已存在，请重新填写!");
			return false;
		}
		
		//检查密码输入是否一致
		if($array['password'] != $array['repeatpassword']) {
			$this->flashSession->error("密码输入不一致!");
			return false;
		}
		
		return true;
	}
}