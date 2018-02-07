<?php
namespace Operation\Model;
class Operation {
    public $id;
    public $title;
    public $content;
    public function exchangeArray($data){
        $this->id              = (isset($data['id'])) ? $data['id'] : null;
        $this->artist       = (isset($data['title'])) ? $data['title'] : null;
        $this->title        = (isset($data['content'])) ? $data['content'] : null;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }
}