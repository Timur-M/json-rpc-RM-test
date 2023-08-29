<?php

class Locations extends Base
{
    var $arg = '';

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function result()
    {
        $response = [];

        if(!is_array(explode(",",trim($this->arg)))){
            $response['status'] = 'error';
            return $response;
        }

        $url = 'https://rickandmortyapi.com/api/location/'.$this->arg;

        $fetch = $this->fetchUrl($url);
        
        if(!$fetch){
            return false;
        }

        $result = json_decode($fetch,true);

        if($result){
            $response['status'] = 'success';
            
            foreach($result as $r){
                $response['result'][] = [
                    'id' => $r['id'],
                    'name' => $r['name'],
                ];
            }
            
            return $response;
        } else {
            return false;
        }
    }
}