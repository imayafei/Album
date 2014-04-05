<?php
/**
 * 简述： 首页模块。
 * 
 * <code>IndexController<code/> 继承自 <code>Phalcon\Mvc\Controller</code> 类
 * 
 * @author hongker
 * @version 1.0
 * @copyright Powerd by hongker
 * @package IndexController
 *
 */
class IndexController extends \Phalcon\Mvc\Controller {
	/**
	 * 显示主页
	 */
	public function indexAction() {
		if($this->session->has('username')) {
			$username = $this->session->get('username');
			$this->view->setVar('username',$username);
		}
	}
	
	
}