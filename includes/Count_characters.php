<?php

class Count_characters extends Base
{
    var $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function result()
    {
        $response = [];

        if(!is_numeric(trim($this->arg))){
            $response['status'] = 'error';
            return $response;
        }

        $url = 'https://rickandmortyapi.com/api/location/'.$this->arg;

        $fetch = $this->fetchUrl($url);
        
        if(!$fetch){
            return false;
        }

        $result = json_decode($fetch,true);
        
        if(isset($result['residents'])){
            $response['status'] = 'success';
            $response['result'] = count($result['residents']);
        } else {
            return false;
        }

        return $response;
    }
}