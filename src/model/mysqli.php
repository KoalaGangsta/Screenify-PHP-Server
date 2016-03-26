<?php
class mysqliModel implements IModel
{
    public $cfg;
    private $db;

    public function __construct($cfg)
    {
        $this->cfg = $cfg;
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function fetch($result)
    {
        return $result->fetch_assoc();
    }

    public  function rows($result)
    {
        return $result->num_rows();
    }

    public function run()
    {
        @$this->db = new mysqli($this->cfg['server'], $this->cfg['user'], $this->cfg['pw'], $this->cfg['db']);


        if($this->db->connect_errno > 0){
            die('Unable to connect to database [' . $this->db->connect_error . ']');
        }
    }
}