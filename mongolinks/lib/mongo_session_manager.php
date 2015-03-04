<?php

class MongoSessionManager
{
	private $_collection;
	private $_current_session;

	function __construct($database, $collection = 'session')
	{
		$this->_collection = $database->selectCollection($collection);
		session_set_save_handler(
			array(&$this, 'open'),
		    array(&$this, 'close'),
		    array(&$this, 'read'),
		    array(&$this, 'write'),
		    array(&$this, 'destroy'),
		    array(&$this, 'gc')
		);
		session_start();
	}

	public function open($path, $name)
	{
		return true;
	}

	public function close()
	{
		return true;
	}

	public function read($sessionId)
	{
		$rv = '';

		$query = array(
			'session_id' => $sessionId,
			'expired_at' => array('$gte' => time()),
		);

		$result = $this->_collection->findOne($query);

		$this->_current_session = $result;

		if(isset($result['data'])) $rv = $result['data'];

		return $rv;
	}

	public function write($sessionId, $data)
	{
		$expired_at = time() + ini_get('session.gc_maxlifetime');
		$query = array('session_id' => $sessionId);
		$object = array(
			'data' => $data,
			'expired_at' => $expired_at
		);

		$this->_collection->update(
			$query,
			array('$set' => $object),
			array('upsert' => True)
		);

		return True;
	}

	public function destroy($sessionId)
	{
		$this->_collection->remove(array('session_id' => $sessionId));
        
        return True;
	}

	public function gc($lifetime)
	{
		$query = array( 'expired_at' => array('$lt' => time()));
        $this->_collection->remove($query);

        return True;
	}

	function __destruct()
	{
		session_write_close();
	}
}

?>