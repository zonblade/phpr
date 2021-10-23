<?php

namespace phpr\mongodb;

use MongoDB\Driver as Mongod;
use MongoDB;


class connect
{

	//Database configuration
	private $conn;
	private $dbuser = '';
	private $dbpass = '';
	private $dbsource = '';
	private $dbhost = 'localhost';
	private $dbport = '27017';

	function __construct($arr)
	{
		foreach ($arr as $key => $val) {
			if ($key == 'user') {
				$this->dbuser = $val;
			}
			if ($key == 'pass') {
				$this->dbpass = $val;
			}
			if ($key == 'host') {
				$this->dbhost = $val;
			}
			if ($key == 'port') {
				$this->dbport = $val;
			}
			if ($key == 'source') {
				$this->dbsource = $val;
			}
		}
		//Connecting to MongoDB
		try {
			//Establish database connection
			$this->conn = new Mongod\Manager('mongodb://' . $this->dbuser . ':' . $this->dbpass . '@' . $this->dbhost . ':' . $this->dbport . '/?authSource=' . $this->dbsource . '&readPreference=primary&appname=MongoDB%20Compass&ssl=false');
		} catch (Mongod\Exception\AuthenticationException $e) {
			echo $e->getMessage();
			echo nl2br("n");
		}
	}

	function getConnection()
	{
		return $this->conn;
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
		
		$result = $m->executeBulkWrite($db_col, $bulk);
		return $result;
	}

	function obelisk($type,$array)
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
			if ($key == 'multicontent') {
				$multicontent = $val;
			}
		}

		$m = $this->conn;
		$bulk = new Mongod\BulkWrite;

		if ($set_content == 'multiple') {
			foreach ($multicontent as $keymulti) {
				if($generate == true){
					$generate_id = new MongoDB\BSON\ObjectID;
					$doc = array_merge(['_id' => $generate_id], $keymulti['content']);
				}else{
					$doc = $keymulti['content'];
				}
				$bulk->update(
					$keymulti['param'],
					['$'.$type => $doc],
					$options
				);
			}
		} else {
			if($generate == true){
				$generate_id = new MongoDB\BSON\ObjectID;
				$doc = array_merge(['_id' => $generate_id], $set_content);
			}else{
				$doc = $set_content;
			}
			$bulk->update(
				$param,
				['$'.$type => $set_content],
				$options
			);
		}
		
		$result = $m->executeBulkWrite($db_col, $bulk);
		return $result;
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

		$result = $m->executeBulkWrite($db_col, $bulk);

		return $result;
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
		
		$result = $m->executeBulkWrite($db_col, $bulk);
		return $result;
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
		
		$result = $m->executeBulkWrite($db_col, $bulk);
		return $result;
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

		$result = $m->executeBulkWrite($db_col, $bulk);
		return $result;
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
		$result = $m->executeBulkWrite($db_col, $bulk);
		return $result;
	}

	
	function dropdb($dbname){
		$m 		= $this->conn;
		$command= new \MongoDB\Driver\Command(['dropDatabase' => 1]);
		$result = $m->executeCommand($dbname, $command);
		return $result;
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

		$m 		= $this->conn;
		$query 	= new Mongod\Query($filter, $options);
		$rows 	= $m->executeQuery($db_col, $query);
		$result = [];
		foreach ($rows as $document) {
			$arrayx   = json_encode($document);
			$result[] = json_decode($arrayx, true);
			//print_r($array['subcontent']);
			//print_r($array);
		};
		return json_encode($result);
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
		return $m->executeQuery($db_col, $query);
	}
}
