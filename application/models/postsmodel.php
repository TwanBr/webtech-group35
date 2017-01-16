<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Postsmodel extends CI_Model
{
    function __construct()
    {
        $this->tableName = 'user';
        $this->primaryKey = 'id';
    }

}
?>