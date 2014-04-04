<?php
/**
 * 简述：项目入口文件
 * 
 * @author hongker
 * @name index.php
 */
header ( "content-type:text/html;charset=utf8" );

try {
	/**
	 * 注册加载器
	 */ 
	$loader = new \Phalcon\Loader ();
	$loader->registerDirs ( array (
			'../app/controllers/',
			'../app/models/' 
	) )->register ();
	
	// 创建DI
	$di = new \Phalcon\DI\FactoryDefault ();
	
	// 设置数据库连接
	$di->set ( 'db', function () {
		return new \Phalcon\Db\Adapter\Pdo\Mysql ( array (
				'host' => 'localhost',
				'username' => 'root',
				'password' => 'hongker',
				'dbname' => 'album', 
				'charset' => 'utf8'
		) );
	} );
	
	// 设置视图组件
	$di->set ( 'view', function () {
		$view = new \Phalcon\Mvc\View ();
		$view->setViewsDir ( '../app/views/' );
		return $view;
	} );
	
	//开启session
	$di->setShared('session', function () {
		$session = new \Phalcon\Session\Adapter\Files();
		$session->start();
		return $session;
	});
	
	//注册事务
	$di->setShared('transactions', function(){
		return new Phalcon\Mvc\Model\Transaction\Manager();
	});
	
	//Set the models cache service
	$di->set('modelsCache', function(){
		
		//Cache data for one day by default
		$frontCache = new Phalcon\Cache\Frontend\Data(array(
				"lifetime" => 86400
		));
		
		//Memcached connection settings
		$cache = new Phalcon\Cache\Backend\Memcached($frontCache, array(
				"host" => "localhost",
				"port" => "11211"
		));
		
		return $cache;
	});
	
		$di->set('dispatcher', function() {
		
			//Create an EventsManager
			$eventsManager = new Phalcon\Events\Manager();
		
			//Attach a listener
			$eventsManager->attach("dispatch:beforeDispatchLoop", function($event, $dispatcher) {
		
				$keyParams = array();
				$params = $dispatcher->getParams();
		
				//Use odd parameters as keys and even as values
				foreach ($params as $number => $value) {
					if ($number & 1) {
						$keyParams[$params[$number - 1]] = $value;
					}
				}
		
				//Override parameters
				$dispatcher->setParams($keyParams);
			});
		
				$dispatcher = new Phalcon\Mvc\Dispatcher();
				$dispatcher->setEventsManager($eventsManager);
		
				return $dispatcher;
		});
	
	$application = new \Phalcon\Mvc\Application ();
	$application->setDI ( $di );
	
	echo $application->handle ()->getContent ();
} catch ( \Phalcon\Exception $e ) {
	echo 'PhalconException:', $e->getMessage ();
	echo " File=", $e->getFile(), "\n";
	echo " Line=", $e->getLine(), "\n";
	echo $e->getTraceAsString();
}