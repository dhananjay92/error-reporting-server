<?php
App::uses('NotificationsController', 'Controller');
App::uses('Notification', 'Model');

/**
 * NotificationsController Test Case
 *
 */
class NotificationsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.notification',
		'app.developer',
		'app.report'
	);

	public function setUp() {
		$this->Notification = $this->generate('Notifications');
		$Session = new SessionComponent(new ComponentCollection());
		$Session->write("Developer.id", 1);
	}

	public function testMassAction() {
		$this->testAction('/notifications/mass_action',
			array(
				'data' => array('notifs' => array('1','3')),
				'method' => 'post'
			)
		);

		$notification = new Notification();
		$actual = $notification->find('all', array('fields' => array('Notification.id')));
		$expected = array(
			array(
				'Notification'=> array(
					'id' => "2"
				),
				'Developer' => array(),
				'Report' => array()
			),
		);

		$this->assertEquals($actual, $expected);
	}

	public function testCleanOldNotifs() {
		// Mark one Notification as "not older". Setting 'created' to current time.
		$notification = new Notification();
		$notification->read(null, 3);
		$notification->set('created', date('Y-m-d H:i:s', time()));
		$notification->save();

		// define constant for Cron Jobs
		if (!defined('CRON_DISPATCHER')) {
			define('CRON_DISPATCHER', true);
		}
		$this->testAction('/notifications/clean_old_notifs');
		$actual = $notification->find('all', array('fields' => array('Notification.id')));
		$expected = array(
			array(
				'Notification'=> array(
					'id' => "3"
				),
				'Developer' => array(),
				'Report' => array()
			),
		);

		$this->assertEquals($actual, $expected);
	}

public function testDataTables() {
		$this->testAction('/notification/data_tables', array(
			'data' => array(
				'sEcho' => 1,
			),
			'method' => 'get',
			'return' => 'view'
		));
		$result = json_decode($this->contents, true);
		
		$expected = array(
			'iTotalRecords' => 3,
			'iTotalDisplayRecords' => 3,
			'sEcho' => 1,
			'aaData' => array(
				array("<input type='checkbox' name='notifs[]' value='1'/>", "<a href='/reports/view/1'>1</a>", "error2", "Lorem ipsum dolor sit amet", "4.0", "New", "js", "2013-08-28 21:47:17"),
				array("<input type='checkbox' name='notifs[]' value='2'/>", "<a href='/reports/view/2'>2</a>", 'error2', 'Lorem ipsum dolor sit amet', '4.0', 'New', 'js', "2013-08-28 21:47:17"),
				array("<input type='checkbox' name='notifs[]' value='3'/>", "<a href='/reports/view/4'>4</a>", 'error1', 'Lorem ipsum dolor sit amet', '3.8', 'New', 'js', "2013-08-28 21:47:17")
			)
		);
		
		echo "\n###############################\n";
		echo "\nResult:";
		var_dump($result);
		echo "\n###############################\n";

		$this->assertEquals($expected, $result);
	}


}
