<?php

namespace ScmsCore;

/**
 * 抽取的共用的增删改查
 */
Trait ScmsCore
{
    /**
     * @var string  表名
     */
    private $scms_table = 'scms_content_list';

    /**
     * @var array   搜索关键字的时候需要匹配的字段
     */
    private $search_where_enable = ['title', 'summary'];

    /**
     * @var array   字段名
     */
    private $scms_fields = [
        'id'                     => 'id',
        'category_id'            => 'category_id',
        'title'                  => 'title',
        'summary'                => 'summary',
        'preview_big_image'      => 'preview_big_image',
        'preview_image'          => 'preview_image',
        'original_link'          => 'original_link',
        'published_time'         => 'published_time',
        'deleted_at'             => 'deleted_at',
        'created_at'             => 'created_at'
    ];

    /**
     * @param $data
     * @return Array
     */
    abstract public function insert($sets);

    /**
     * @param $wheres
     * @return Boolean
     */
    abstract public function delete($wheres);

    /**
     * @param $sets
     * @param $wheres
     * @return Boolean
     */
    abstract public function update($sets, $wheres);

    /**
     * @param $wheres
     * @return Array
     */
    abstract public function get($wheres);

    /**
     * 获取列表数据
     * @param $wheres
     * @return Array
     */
    abstract public function gets($offset, $length, $sets, $wheres, $orWheres);

    /**
     * 设置表名
     * @return string
     */
    public function setTable($table)
    {
        $this->scms_table = $table;
    }

    /**
     * 获取表名
     * @return string
     */
    public function getTable()
    {
        return $this->scms_table;
    }

    /*
     * 设置搜索关键字的时候需要匹配的字段
     */
    public function setEnableSearchField($fields){
        unset($this->search_where_enable);
        $this->search_where_enable = $fields;
    }

    public function getEnableSearchField(){
        return $this->search_where_enable;
    }

    /*
     * 设置对应table里面的字段
     * */
    public function setTableFields($fileds){
        $this->scms_fields = $fileds;
    }

    public function getTableField($filed){
        return $this->scms_fields[$filed];
    }

    /*
     * 检查参数是否合法
     * */
    public function _checkFileds($options=[]){
        $_options=[];
        foreach ($options as $key=>$value) {
            if ($key && in_array($key, $this->scms_fields)) {
                $_options[$key] = $value;
            }
        }
        return $_options;
    }

    /*
     * 添加数据
     * */
    public function addItem($options=[]){
        $_options = $this->_checkFileds($options);
        return $this->insert($_options);
    }

    /*
     * 更改数据
     * */
    public function modifyItem($options, $wheres=[]){
        $_options = $this->_checkFileds($options);
        return $this->update($_options, $wheres);
    }

    /*
     * 真实删除一条数据
     * */
    public function deleteItem($wheres=[]){
        return $this->delete($wheres);
    }

    /*
    * 获取某一条数据
    * */
    public function getItem($wheres){
        $this->get($wheres);
    }

    /*
     * 获取列表
     * return array
     * */
    public function getList($offset, $length, $options=[], $wheres=[]){
        //参数检查
        if (!is_numeric($offset) || $offset < 0) {
            $offset = 0;
        }
        if (!is_numeric($length) || $length < 0) {
            $length = 10;
        }

        $keywords = isset($options['keywords'])?$options['keywords']:'';
        $orWheres = [];
        if ($keywords) {
            //需要添加关键字搜索
            foreach ($this->search_where_enable as $key){
                $orWheres[] = [$key, 'like', '%'."$keywords".'%'];
            }
        }
        return $this->gets($offset, $length, $options, $wheres, $orWheres);
    }
}