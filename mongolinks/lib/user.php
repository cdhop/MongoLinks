<?php

	class User
	{
		private $_collection;
		private $_user;

		public function __construct($database, $collection = "users")
		{
			$this->_collection = $database->selectCollection($collection);
			if($this->isLoggedIn()) $this->_loadData();
		}

		public function isLoggedIn()
		{
			return isset($_SESSION['user_id']);
		}

		public function authenticate($username, $password)
		{
			$rv = false;

			$query = array(
				'username' => $username,
				'password' => md5($password)
			);

			$this->_user = $this->_collection->findOne($query);

			if(!empty($this->_user))
			{
			 	$rv = true;
			 	$_SESSION['user_id'] = (string) $this->_user['_id'];
			}

			return $rv;
		}

		public function logout()
		{
			unset($_SESSION['user_id']);
		}

		public function __get($attr)
		{
			$rv = Null;

			if(!empty($this->_user))
			{
				switch($attr)
				{
					case 'password':
					  $rv = Null;
					  break;
					default:
					  $rv = (isset($this->_user[$attr])) ? $this->_user[$attr] : Null;
				}

			}

			return $rv;
		}

		public function set_password($password)
		{
			$query = array('username' => $this->_user['username']);
			$change = array('$set' => array('password' => md5($password)));

			$this->_collection->update($query,$change);
		}

		private function _loadData()
		{
			$id = new MongoId($_SESSION['user_id']);
			$this->_user = $this->_collection->findOne(array('_id' => $id));
		}

		public static function user_exists($username, $database, $collection = 'users')
		{
			$rv = false;
			$users = $database->selectCollection($collection);
			$query = array('username' => $username);

			if($users->findOne($query)) $rv = true;

			return $rv;
		}

		public static function create_user($username, $password, $database, $collection = 'users')
		{
			$rv = null;
			$users = $database->selectCollection($collection);
			$user_to_create = array(
 				'username' => $username,
				'password' => md5($password),
			);

			try
			{
				$rv = $users->insert($user_to_create);
			}
			catch (MongoCursorException $e)
			{
			  die($e->getMessage());
			}

			return $rv;
		}
	}

?>