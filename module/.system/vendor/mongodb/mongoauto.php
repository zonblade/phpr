<?php

namespace mongo\auto;

use MongoDB\Driver as Mongod;
use MongoDB;


class connect
{

	//Database configuration
	private $conn;
	private $instance;
	// private $dbpass = '';
	// private $dbsource = '';
	// private $dbhost = 'localhost';
	// private $dbport = '27017';

	function __construct($instance)
	{
		// echo '<pre>';
		// print_r(PHPRMONGOD);
		// die();
		$this->dbuser   = PHPRMONGOD[$instance]['user'];
		$this->dbpass   = PHPRMONGOD[$instance]['pass'];
		$this->dbhost   = PHPRMONGOD[$instance]['host'];
		$this->dbport   = (int)PHPRMONGOD[$instance]['port'];
		$this->dbsource = PHPRMONGOD[$instance]['source'];
		//Connecting to MongoDB
		try {
			//Establish database connection
			$this->conn = new Mongod\Manager('mongodb://' . $this->dbuser . ':' . $this->dbpass . '@' . $this->dbhost . ':' . $this->dbport . '/?authSource=' . $this->dbsource . '&readPreference=primary&appname=MongoDB%20Compass&ssl=false');
		} catch (Mongod\Exception\AuthenticationException $e) {
			echo $e->getMessage();
			echo nl2br("n");
			die();
		}
	}

	function getConnection()
	{
		return $this->conn;
	}

	function counter($db, $col, $filter, $options)
	{
		// $filter=[];
		//          //When you need to display, sort, and ignore fields
		// $options = [
		//     'skip'=>($page - 1) * $pageSize,
		//     'limit'=>$pageSize,
		//     'sort' => ['createTime' => -1],
		//     'projection'=>['_id'=> False],
		// ];
		$m = $this->conn;
		$query = new MongoDB\Driver\Query($filter, $options);
		$command = new MongoDB\Driver\Command(
			array(
				"count" => $col,
				"query" => $query,
			)
		);
		$count = $m->executeCommand($db, $command)->toArray()[0]->n;
		return $count;
	}


	function counterOK($db, $col, $options)
	{
		// $filter=[];
		//          //When you need to display, sort, and ignore fields
		// $options = [
		//     'skip'=>($page - 1) * $pageSize,
		//     'limit'=>$pageSize,
		//     'sort' => ['createTime' => -1],
		//     'projection'=>['_id'=> False],
		// ];
		$m = $this->conn;
		$command = new MongoDB\Driver\Command(
			array(
				"count" => $col,
				"query" => $options,
			)
		);
		try {
			$count = $m->executeCommand($db, $command);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		return ['count' => $count->toArray()[0]->n, 'raw' => $count];
	}

	function aggregate($array)
	{
		$db_col = '';
		$filter = [];
		$aggregate = '';

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'pipline') {
				$filter = $val;
			}
			if ($key == 'aggregate') {
				$aggregate = $val;
			}
		}
		try {
			$m 		= $this->conn;
			/*
			sample usage pipline
			[
    			[ '$match' => ['searchcontent.name' => $regex1] ],
    			[ '$group' => ['_id' => '$searchcontent.name'] ],
    			[ '$limit' => 50 ],
    			[ '$skip' => 10 ],
			]; */
			$pipeline = $filter;
			$aggregate = new \MongoDB\Driver\Command([
				'aggregate' => $aggregate,
				'pipeline' => $pipeline
			]);
			$rows = $m->executeCommand($db_col, $aggregate);
			$result = [];
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		// echo '<pre>';
		// print_r($m);
		// die();
		foreach ($rows as $document) {
			$arrayx   = json_encode($document);
			$result[] = json_decode($arrayx, true);
			//print_r($array['subcontent']);
			//print_r($array);
		};
		$this->type   = 'json_encode';
		$this->result = json_encode($result);
		return $this;
	}

	function delete($array)
	{
		$db_col = '';
		$set_content = [];
		$param = '';
		$multicontent = [];

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'param') {
				$set_content = $val;
			}
			if ($key == 'option') {
				$param = $val;
			}
			if ($key == 'multiparam') {
				$multicontent = $val;
			}
		}

		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;

		if ($set_content == 'multiple') {
			foreach ($multicontent as $keymulti) {
				$bulk->delete($keymulti['param'], $param);
			}
		} else {
			$bulk->delete($set_content, $param);
		}

		try {
			$result = $m->executeBulkWrite($db_col, $bulk);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}

	function obelisk($type, $array)
	{
		$multicontent 	= [];
		$generate_id 	= '';
		$set_content 	= [];
		$generate 		= false;
		$options 		= [];
		$db_col 		= '';
		$param 			= '';

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'auto.id') {
				$generate = $val;
			}
			if ($key == 'content') {
				$set_content = $val;
			}
			if ($key == 'param') {
				$param = $val;
			}
			if ($key == 'option') {
				$options = $val;
			}
			if ($key == 'options') {
				$options = $val;
			}
			if ($key == 'multicontent') {
				$multicontent = $val;
			}
		}

		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;

		if ($set_content == 'multiple') {
			foreach ($multicontent as $keymulti) {
				if ($generate == true) {
					$generate_id = new MongoDB\BSON\ObjectID;
					$doc = array_merge(['_id' => $generate_id], $keymulti['content']);
				} else {
					$doc = $keymulti['content'];
				}
				$bulk->update(
					$keymulti['param'],
					['$' . $type => $doc],
					$options
				);
			}
		} else {
			if ($generate == true) {
				$generate_id = new MongoDB\BSON\ObjectID;
				$doc = array_merge(['_id' => $generate_id], $set_content);
			} else {
				$doc = $set_content;
			}
			$bulk->update(
				$param,
				['$' . $type => $set_content],
				$options
			);
		}

		try {
			$result = $m->executeBulkWrite($db_col, $bulk);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}

	function insert($array)
	{
		$db_col = '';
		$set_content = '';
		$generate = true;
		$multicontent = [];

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'auto.id') {
				$generate = $val;
			}
			if ($key == 'content') {
				$set_content = $val;
			}
			if ($key == 'multicontent') {
				$multicontent = $val;
			}
		}
		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;
		if ($set_content == 'multiple') {
			foreach ($multicontent as $keymulti) {
				if ($generate == true) {
					$generate_id = new MongoDB\BSON\ObjectID;
					$doc = array_merge(['_id' => $generate_id], $keymulti);
				} else {
					$doc = $keymulti;
				}
				$bulk->insert($doc);
			}
		} else {
			if ($generate == true) {
				$generate_id = new MongoDB\BSON\ObjectID;
				$doc = array_merge(['_id' => $generate_id], $set_content);
			} else {
				$doc = $set_content;
			}
			$bulk->insert($doc);
		}

		try {
			$result = $m->executeBulkWrite($db_col, $bulk);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}

	function set($array)
	{
		$db_col = '';
		$set_content = [];
		$param = '';
		$param_val = '';
		$multi = false;
		$upsert = false;
		$multicontent = [];

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'content') {
				$set_content = $val;
			}
			if ($key == 'param') {
				$param = $val;
			}
			if ($key == 'option') {
				$multi = $val['multi'];
				$upsert = $val['upsert'];
			}
			if ($key == 'multicontent') {
				$multicontent = $val;
			}
		}

		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;

		if ($set_content == 'multiple') {
			foreach ($multicontent as $keymulti) {
				$bulk->update(
					$keymulti['param'],
					['$set' => $keymulti['content']],
					['multi' => $multi, 'upsert' => $upsert]
				);
			}
		} else {
			$bulk->update(
				$param,
				['$set' => $set_content],
				['multi' => $multi, 'upsert' => $upsert]
			);
		}

		try {
			$result = $m->executeBulkWrite($db_col, $bulk);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}


	function push($array)
	{
		$db_col = '';
		$set_content = [];
		$param = '';
		$param_val = '';
		$multi = false;
		$upsert = false;
		$multicontent = [];

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'content') {
				$set_content = $val;
			}
			if ($key == 'param') {
				$param = $val;
			}
			if ($key == 'option') {
				$multi = $val['multi'];
				$upsert = $val['upsert'];
			}
			if ($key == 'multicontent') {
				$multicontent = $val;
			}
		}

		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;

		if ($set_content == 'multiple') {
			foreach ($multicontent as $keymulti) {
				$bulk->update(
					$keymulti['param'],
					['$push' => $keymulti['content']],
					['multi' => $multi, 'upsert' => $upsert]
				);
			}
		} else {
			$bulk->update(
				$param,
				['$push' => $set_content],
				['multi' => $multi, 'upsert' => $upsert]
			);
		}

		try {
			$result = $m->executeBulkWrite($db_col, $bulk);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}

	function unset($array)
	{
		$db_col = '';
		$set_content = [];
		$param = '';
		$param_val = '';
		$multi = false;
		$upsert = false;
		$multicontent = [];

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'content') {
				$set_content = $val;
			}
			if ($key == 'param') {
				$param = $val;
			}
			if ($key == 'option') {
				$multi = $val['multi'];
				$upsert = $val['upsert'];
			}
			if ($key == 'multicontent') {
				$multicontent = $val;
			}
		}

		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;

		if ($set_content == 'multiple') {
			foreach ($multicontent as $keymulti) {
				$bulk->update(
					$keymulti['param'],
					['$unset' => $keymulti['content']],
					['multi' => $multi, 'upsert' => $upsert]
				);
			}
		} else {
			$bulk->update(
				$param,
				['$unset' => $set_content],
				['multi' => $multi, 'upsert' => $upsert]
			);
		}

		try {
			$result = $m->executeBulkWrite($db_col, $bulk);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}

	function pull($array)
	{
		$db_col = '';
		$set_content = [];
		$param = '';
		$param_val = '';
		$multi = false;
		$upsert = false;

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'content') {
				$set_content = $val;
			}
			if ($key == 'param') {
				$param = $val;
			}
			if ($key == 'option') {
				$multi = $val['multi'];
				$upsert = $val['upsert'];
			}
		}

		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;
		$bulk->update(
			$param,
			['$pull' => $set_content],
			['multi' => $multi, 'upsert' => $upsert]
		);
		try {
			$result = $m->executeBulkWrite($db_col, $bulk);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}


	function dropdb($dbname)
	{
		$m 		= $this->conn;
		$command = new \MongoDB\Driver\Command(['dropDatabase' => 1]);
		try {
			$result = $m->executeCommand($dbname, $command);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		
		$this->type   = 'object';
		$this->result = $result;
		return $this;
	}

	function get($array)
	{

		$db_col = '';
		$filter = [];
		$options = [];

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'filter') {
				$filter = $val;
			}
			if ($key == 'options') {
				$options = $val;
			}
		}
		try {
			$m 		= $this->conn;
			$query 	= new Mongod\Query($filter, $options);
			$rows 	= $m->executeQuery($db_col, $query);
			$result = [];
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
		// echo '<pre>';
		// print_r($m);
		// die();
		foreach ($rows as $document) {
			$arrayx   = json_encode($document);
			$result[] = json_decode($arrayx, true);
			//print_r($array['subcontent']);
			//print_r($array);
		};
		
		$this->type   = 'json_encode';
		$this->result = json_encode($result);
		return $this;
	}


	function getOrganic($array)
	{

		$db_col = '';
		$filter = [];
		$options = [];

		foreach ($array as $key => $val) {
			if ($key == 'db.col') {
				$db_col = $val;
			}
			if ($key == 'filter') {
				$filter = $val;
			}
			if ($key == 'options') {
				$options = $val;
			}
		}

		$m 		= $this->conn;
		$query 	= new Mongod\Query($filter, $options);
		try {
			return $m->executeQuery($db_col, $query);
		} catch (Mongod\Exception\AuthenticationException $e) {
			include __DIR__ . '/error.html';
			die();
		} catch (MongoDB\Driver\Exception\ConnectionTimeoutException $e) {
			include __DIR__ . '/error.html';
			die();
		}
	}
	
	function resultJson(){
		$regs = $this->result;
		$type = $this->type;
		if($type==='object'){
			$regs = [
				'Inserted'  => $regs->getInsertedCount(),
				'Modified'  => $regs->getModifiedCount(),
				'Matched'   => $regs->getMatchedCount(),
				'Removed'   => $regs->getDeletedCount(),
				'Upserted'  => $regs->getUpsertedCount(),
				'Error'     => $regs->getWriteErrors()
			];
			return json_encode($regs);
		}elseif($type==='json_encode'){
			return $regs;
		}
	}
	function resultArray(){
		$regs = $this->result;
		$type = $this->type;
		if($type==='object'){
			return (array)[
				'Inserted'  => $regs->getInsertedCount(),
				'Modified'  => $regs->getModifiedCount(),
				'Matched'   => $regs->getMatchedCount(),
				'Removed'   => $regs->getDeletedCount(),
				'Upserted'  => $regs->getUpsertedCount(),
				'Error'     => $regs->getWriteErrors()
			];
		}elseif($type==='json_encode'){
			$decoded = json_decode($regs,true);
			return (array)$decoded;
		}
	}
	function resultObject(){
		$regs = $this->result;
		$type = $this->type;
		if($type==='object'){
			return (object)[
				'Inserted'  => $regs->getInsertedCount(),
				'Modified'  => $regs->getModifiedCount(),
				'Matched'   => $regs->getMatchedCount(),
				'Removed'   => $regs->getDeletedCount(),
				'Upserted'  => $regs->getUpsertedCount(),
				'Error'     => $regs->getWriteErrors()
			];
		}elseif($type==='json_encode'){
			$decoded = json_decode($regs);
			return (object)$decoded;
		}
	}
}
