<?php

namespace phpr\mongodb;

use MongoDB\Driver as Mongod; use MongoDB;

class instance
{
    private $conn;
    private $instance;
    function __construct($instance)
    {
        $this->dbuser   = dbmongo()[$instance]->user; $this->dbpass   = dbmongo()[$instance]->pass; $this->dbhost   = dbmongo()[$instance]->host; $this->dbport   = (int)dbmongo()[$instance]->port; $this->dbsource = dbmongo()[$instance]->source;
        try { 
            if(dbmongo()[$instance]->user===''){ 
                $this->conn = new Mongod\Manager('mongodb://' . $this->dbhost . ':' . $this->dbport . '/?readPreference=primary&appname=MongoDB%20Compass&ssl=false');
            }else{
                $this->conn = new Mongod\Manager('mongodb://' . $this->dbuser . ':' . $this->dbpass . '@' . $this->dbhost . ':' . $this->dbport . '/?authSource=' . $this->dbsource . '&readPreference=primary&appname=MongoDB%20Compass&ssl=false');
            }
        } catch (Mongod\Exception\AuthenticationException $e) {return $e->getMessage();die();}
    }
    function getConnection(){return $this->conn;}
    function database($database_dot_collection)
                                {$this->db_name_col = $database_dot_collection;return $this;}
    function auto_id($bool)     {$this->db_auto_id  = $bool;     return $this;}
    function param($array=[])      {$this->param       = $array;    return $this;}
    function operator($operator){$this->operator    = $operator; return $this;}
    function content($array=[]) {$this->content     = $array;    return $this;}
    function options($array=[]) {$this->options     = $array;    return $this;}
    function go_update()
    {
        $bulk = new Mongod\BulkWrite;
        $bulk->update($this->param,[$this->operator => $this->content],$this->options);
        $this->bulk = $bulk;
        return $this;
    }
    function go_insert()
    {
        $bulk = new Mongod\BulkWrite;
        if(isset($this->db_auto_id)){
            $generate_id = new MongoDB\BSON\ObjectID;
            $bulk->insert(array_merge(['_id' => $generate_id], $this->content));
            $this->bulk = $bulk;
            return $this;
        }else{
            $bulk->insert($this->content);
            $this->bulk = $bulk;
            return $this;
        }
    }
    function go_delete()
    {
        $bulk = new Mongod\BulkWrite;
        $bulk->delete($this->content);
        $this->bulk = $bulk;
        return $this;
    }
    function cmd($rawCMD)
    {
        $bulk = new Mongod\Command($rawCMD);
        $this->bulk = $bulk;
        return $this;
    }
    function execute($type=true,$organic=false)
    {
        $m = $this->conn;
        $options = [];
        if(is_bool($type)){
            $type = 'get';
            $organic = true;
        }
        if(isset($this->options)){
            $options = $this->options;
        }
        $this->fn_type = $type;
        if($type==='bulk'   || 
           $type==='delete' || 
           $type==='update' || 
           $type==='insert' || 
           $type==='single' || 
           $type==='once'){
            $fins = $m->executeBulkWrite($this->db_name_col, $this->bulk);
            $this->final_result = $fins;
        }elseif($type==='cmd'){
            $fins = $m->executeCommand($this->db_name_col, $this->bulk);
            $this->final_result = $fins;
        }elseif($type==='query' || $type==='get' || $type==='data'){
                /* only need param and execute! */
                $query 	= new Mongod\Query($this->param, $options);
                if($organic===false){
                $rows 	= $m->executeQuery($this->db_name_col, $query);
                $result = [];
                foreach ($rows as $document) {$arrayx   = json_encode($document);$result[] = json_decode($arrayx, true);};
                $this->final_result = (object)$result;
            }else{
                $query 	= new Mongod\Query($this->param, $options);
                $rows 	= $m->executeQuery($this->db_name_col, $query);
                $this->final_result = $rows;
            }
        }
        return $this;
    }
    function result(){
        $type = 'none';
        if(isset(func_get_args()[0])){$type = func_get_args()[0];}
        $final      = $this->final_result;
        $returner   = null;
        if($type==='JSON')          {$returner = json_encode($final);
        }elseif($type==='array')    {$returner = (array)$final;
        }elseif($type==='cmd-array')    {
            $returner = $final->toArray();
        }elseif($type==='object')   {$returner = (object)$final;
        }elseif($type==='simplify') {
			$returner = (object)[
				'Inserted'  => $final->getInsertedCount(),
				'Modified'  => $final->getModifiedCount(),
				'Matched'   => $final->getMatchedCount(),
				'Removed'   => $final->getDeletedCount(),
				'Upserted'  => $final->getUpsertedCount(),
				'Error'     => $final->getWriteErrors()
			];
        }else                       {$returner = (object)$final;}
        return $returner;
    }
}