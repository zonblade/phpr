<?php

namespace phpr\database\models;

if (dbsetup() != false) {

    class Template
    {
        protected $_file;
        protected $_data = array();

        public function __construct($file = null)
        {
            $this->_file = $file;
        }

        public function set($key, $value)
        {
            $this->_data[$key] = $value;
            return $this;
        }
        public function render()
        {
            extract($this->_data);
            ob_start();
            include($this->_file);
            return ob_get_clean();
        }
    }
    class mysql
    {
        private $instance;

        function __construct($instance)
        {
            $db = mysqli_connect(
                dbsetup()[$instance]->host, 
                dbsetup()[$instance]->user, 
                dbsetup()[$instance]->pass, 
                dbsetup()[$instance]->db);
            if ($db->connect_errno) {
                echo "Failed to connect to MySQL: " . $db->connect_error;
                die();
            }
            $this->db = $db;
        }

        function query($sql)
        {
            $db     = $this->db;
            mysqli_query($db, $sql);
            if (mysqli_affected_rows($db) > 0) {
                return true;
            } else {
                return false;
            }
        }

        function gc($str, $st, $end)
        {
            $a = array();
            $stLength = strlen($st);
            $endLength = strlen($end);
            $sf = $atart = $contentEnd = 0;
            while (false !== ($atart = strpos($str, $st, $sf))) {
                $atart += $stLength;
                $contentEnd = strpos($str, $end, $atart);
                if (false === $contentEnd) {
                    break;
                }
                $a[] = substr($str, $atart, $contentEnd - $atart);
                $sf = $contentEnd + $endLength;
            }

            return $a;
        }

        function insert($table_name, $array, $status = false)
        {
            $db = $this->db;
            /*
    array(
        "table"=>"value"
    )
    */
            $table = '';
            $value = '';
            foreach ($array as $key => $x) {
                $table .= "`" . $key . "`,";
            }
            foreach ($array as $key => $y) {
                if ($y == 'NULL') {
                    $value .= "" . $y . ",";
                } else {
                    $value .= "'" . $y . "',";
                }
            }
            $table = "(" . rtrim(trim($table), ',') . " )";
            $value = "(" . rtrim(trim($value), ',') . " )";
            $sql = "INSERT INTO `$table_name`$table VALUES $value";
            mysqli_query($db, $sql);
            if ($status === false) {
                if (mysqli_affected_rows($db) > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if (mysqli_affected_rows($db) > 0) {
                    return (object)['status' => true, 'id' => mysqli_insert_id($db)];
                } else {
                    return (object)['status' => false];
                }
            }
        }

        function raw($sql, $type)
        {
            $db     = $this->db;
            $query  = mysqli_query($db, $sql);
            if ($type == 'ASSOC') {
                return mysqli_fetch_assoc($query);
            } elseif ($type == 'ALL') {
                return mysqli_fetch_all($query, MYSQLI_ASSOC);
            } elseif ($type == 'NUM') {
                return mysqli_num_rows($query);
            } elseif ($type == 'ARRAY') {
                return mysqli_fetch_array($query);
            } elseif ($type == 'QUERY') {
                if (mysqli_affected_rows($db) > 0) {
                    return (object)['status' => true];
                } else {
                    return (object)['status' => false];
                }
            } else {
                return false;
            }
        }

        function delete($table_name, $array)
        {
            $db = $this->db;
            /*
    $context = [
        'set'     => [
            "nama" => $nama,
            "stock" => $stock,
            "harga" => $harga
        ],
        'where'   => [
            "id" => $id
        ]
    ];
    */
            $set = '';
            $where = '';
            foreach ($array as $key => $x) {
                if ($key == 'where') {
                    foreach ($x as $ykey => $yval) {
                        $where .= "`" . $ykey . "`='" . $yval . "' AND";
                    }
                }
            }
            $set    = rtrim(trim($set), ',');
            $where  = rtrim(trim($where), 'AND');

            $sql = "DELETE FROM `$table_name` WHERE $where";
            mysqli_query($db, $sql);
            if (mysqli_affected_rows($db) > 0) {
                return true;
            } else {
                return false;
            }
        }

        function update($table_name, $array)
        {
            $db = $this->db;
            /*
    $context = [
        'set'     => [
            "nama" => $nama,
            "stock" => $stock,
            "harga" => $harga
        ],
        'where'   => [
            "id" => $id
        ]
    ];
    */
            $set = '';
            $where = '';
            foreach ($array as $key => $x) {
                if ($key == 'set') {
                    foreach ($x as $xkey => $xval) {
                        $set .= "`" . $xkey . "`='" . $xval . "',";
                    }
                }
                if ($key == 'where') {
                    foreach ($x as $ykey => $yval) {
                        $where .= "`" . $ykey . "`='" . $yval . "' AND";
                    }
                }
            }
            $set    = rtrim(trim($set), ',');
            $where  = rtrim(trim($where), 'AND');

            $sql = "UPDATE `$table_name` SET $set WHERE $where";
            mysqli_query($db, $sql);
            if (mysqli_affected_rows($db) > 0) {
                return true;
            } else {
                return false;
            }
        }

        function num($table_name, $array)
        {
            $db = $this->db;
            $set        = '';
            $where      = '';
            $collumn    = '';
            $limit      = '';
            $param      = '';
            $select     = '';
            foreach ($array as $key => $x) {
                if ($key == 'col') {
                    foreach ($x as $xkey => $xval) {
                        $collumn .= "`" . $xkey . "`='" . $xval . "',";
                    }
                }
                if ($key == 'where') {
                    foreach ($x as $ykey => $yval) {
                        $where .= "`" . $ykey . "`='" . $yval . "' AND";
                    }
                }
                if ($key == 'parameter') {
                    $param  = $x;
                }
                if ($key == 'select') {
                    $select = $x;
                }
            }
            $set    = rtrim(trim($set), ',');
            if ($param == 'OR') {
                $where  = rtrim(trim($where), 'AND');
                $where = str_replace('AND', 'OR', $where);
            } elseif ($param == 'AND') {
                $where  = rtrim(trim($where), 'AND');
            }
            if ($where == '') {
                $sql = "SELECT $select FROM `$table_name`";
            } else {
                $sql = "SELECT $select FROM `$table_name` WHERE $where";
            }
            $query = mysqli_query($db, $sql);
            return mysqli_num_rows($query);
        }

        function all($table_name, $array)
        {
            $db = $this->db;
            $set        = '';
            $where      = '';
            $collumn    = '';
            $limit      = '';
            $param      = '';
            $select     = '';
            $custom     = '';
            foreach ($array as $key => $x) {
                if ($key == 'where') {
                    foreach ($x as $ykey => $yval) {
                        $where .= "`" . $ykey . "`='" . $yval . "' AND";
                    }
                }
                if ($key == 'parameter') {
                    $param  = $x;
                }
                if ($key == 'select') {
                    $select = $x;
                }
                if ($key == 'custom') {
                    $custom = $x;
                }
            }
            $set    = rtrim(trim($set), ',');
            if ($param == 'OR') {
                $where  = rtrim(trim($where), 'AND');
                $where = str_replace('AND', 'OR', $where);
            } else {
                $where  = rtrim(trim($where), 'AND');
            }
            if ($where == '') {
                $sql = "SELECT $select FROM `$table_name` $custom";
            } else {
                $sql = "SELECT $select FROM `$table_name` WHERE $where $custom";
            }
            $query = mysqli_query($db, $sql);
            return mysqli_fetch_all($query, MYSQLI_ASSOC);
        }

        function assoc($table_name, $array)
        {
            $db = $this->db;
            $set        = '';
            $where      = '';
            $collumn    = '';
            $limit      = '';
            $param      = '';
            $select     = '';
            $custom     = '';
            foreach ($array as $key => $x) {
                if ($key == 'where') {
                    foreach ($x as $ykey => $yval) {
                        $where .= "`" . $ykey . "`='" . $yval . "' AND";
                    }
                }
                if ($key == 'parameter') {
                    $param  = $x;
                }
                if ($key == 'select') {
                    $select = $x;
                }
                if ($key == 'custom') {
                    $custom = $x;
                }
            }
            $set    = rtrim(trim($set), ',');
            if ($param == 'OR') {
                $where  = rtrim(trim($where), 'AND');
                $where = str_replace('AND', 'OR', $where);
            } else {
                $where  = rtrim(trim($where), 'AND');
            }
            if ($where == '') {
                $sql = "SELECT $select FROM `$table_name` $custom";
            } else {
                $sql = "SELECT $select FROM `$table_name` WHERE $where $custom";
            }
            $query = mysqli_query($db, $sql);
            return mysqli_fetch_assoc($query);
        }

        function array_($table_name, $array)
        {
            $db = $this->db;
            $set        = '';
            $where      = '';
            $collumn    = '';
            $param      = '';
            $select     = '';
            $custom     = '';
            foreach ($array as $key => $x) {
                if ($key == 'col') {
                    foreach ($x as $xkey => $xval) {
                        $collumn .= "`" . $xkey . "`='" . $xval . "',";
                    }
                }
                if ($key == 'where') {
                    foreach ($x as $ykey => $yval) {
                        $where .= "`" . $ykey . "`='" . $yval . "' AND";
                    }
                }
                if ($key == 'parameter') {
                    $param  = $x;
                }
                if ($key == 'select') {
                    $select = $x;
                }
                if ($key == 'custom') {
                    $custom = $x;
                }
            }
            $set    = rtrim(trim($set), ',');
            if ($param == 'OR') {
                $where  = rtrim(trim($where), 'AND');
                $where = str_replace('AND', 'OR', $where);
            } elseif ($param == 'AND') {
                $where  = rtrim(trim($where), 'AND');
            }
            if ($where == '') {
                $sql = "SELECT $select FROM `$table_name` $custom";
            } else {
                $sql = "SELECT $select FROM `$table_name` WHERE $where $custom";
            }
            $query = mysqli_query($db, $sql);
            return mysqli_fetch_array($query);
        }

        function foreach_($table_name, $array, $custom_input, $path)
        {
            $db         = $this->db;
            $set        = '';
            $where      = '';
            $type       = '';
            $param      = '';
            $select     = '';
            $custom     = '';
            foreach ($array as $key => $x) {
                if ($key == 'where') {
                    foreach ($x as $ykey => $yval) {
                        $where .= "`" . $ykey . "`='" . $yval . "' AND";
                    }
                }
                if ($key == 'type') {
                    $type  = $x;
                }
                if ($key == 'param') {
                    $param  = $x;
                }
                if ($key == 'select') {
                    $select = $x;
                }
                if ($key == 'custom') {
                    $custom = $x;
                }
            }
            $set    = rtrim(trim($set), ',');
            if ($type == 'MULTI') {
                $where = str_replace("'", '', $where);
                $where = str_replace("`", '', $where);
                $where = str_replace('=', ' IN ', $where);
                $where  = rtrim(trim($where), 'AND');
            } else {
                $where  = rtrim(trim($where), 'AND');
            }
            if ($param == 'OR') {
                $where  = rtrim(trim($where), 'AND');
                $where = str_replace('AND', 'OR ', $where);
            } else {
                $where  = rtrim(trim($where), 'AND');
            }
            if ($where == '') {
                $sq = "SELECT $select FROM `$table_name` $custom";
            } else {
                $sq = "SELECT $select FROM `$table_name` WHERE $where $custom";
            }
            $fe = mysqli_fetch_all(mysqli_query($db, $sq), MYSQLI_ASSOC);
            $i = 1;
            $fo_return = null;
            $template = new Template($path);
            if (isset($fe)) {
                foreach ($fe as $fo_key => $fo_val) {
                    if ($custom_input != false) {
                        foreach ($custom_input as $key_cu => $cu) {
                            $template->set($key_cu, $cu);
                        }
                    }
                    foreach ($fo_val as $fokey => $foval) {
                        $template->set($fokey, $foval);
                    }
                    $template->set('fo_val', $fo_val);
                    $template->set('count', $i++);
                    $fo_return .= $template->render();
                }
            }
            return $fo_return;
        }
    }
}
