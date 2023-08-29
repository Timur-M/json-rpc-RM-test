<?php

class Character_locations extends Base
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

        $url = 'https://rickandmortyapi.com/api/location';

        $fetch = $this->fetchUrl($url);
        
        if(!$fetch){
            return false;
        }

        $location_first_page = json_decode($fetch,true);

        $total_pages = $location_first_page['info']['pages'];

        $result = [];

        for($i=1;$i<=$total_pages;$i++){
            $url = 'https://rickandmortyapi.com/api/location?page='.$i;

            $fetch = $this->fetchUrl($url);
        
            if(!$fetch){
                return false;
            }

            $location = json_decode($fetch,true);

            foreach($location['results'] as $location){
                foreach($location['residents'] as $resident){
                    $exploded_character_url = explode("/",$resident);
                    if(isset($exploded_character_url[5]) and $exploded_character_url[5] == $this->arg){
                        $result[] = [
                            'id' => $location['id'],
                            'name' => $location['name']
                        ];
                    }
                }
            }
        }

        if(!empty($result)){
            $response['status'] = 'success';
            $response['result'] = array_unique($result);
            return $response;
        } else {
            return false;
        }
    }
}