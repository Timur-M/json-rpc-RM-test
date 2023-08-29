<?php

class Count_locations extends Base
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

        $url = 'https://rickandmortyapi.com/api/episode/'.$this->arg;
        
        $fetch = $this->fetchUrl($url);
        
        if(!$fetch){
            return false;
        }

        $episode = json_decode($fetch,true);

        //return $episode;
        if(!isset($episode['error'])){
            if(!empty($episode['characters'])){
                foreach($episode['characters'] as $character_url){
                    $exploded_character_url = explode("/",$character_url);
                    if(isset($exploded_character_url[5])){
                        $character_ids[] = $exploded_character_url[5];
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        //return implode(",",$character_ids);

        if(!empty($character_ids)){
            $url = 'https://rickandmortyapi.com/api/character/'.implode(",",$character_ids);

            $fetch = $this->fetchUrl($url);
        
            if(!$fetch){
                return false;
            }

            $characters = json_decode($fetch,true);

            foreach($characters as $character){
                if(isset($character['location']['url']) and $character['location']['url'] != ''){
                    $exploded_location_url = explode("/",$character['location']['url']);
                    if(isset($exploded_location_url[5])){
                        $result[] = $exploded_location_url[5];
                    }
                }
            }
            
        } else {
            return false;
        }

        if(!empty($result)){
            $result = array_unique($result);
            $response['status'] = 'success';
            $response['result'] = count($result);
        } else {
            return false;
        }

        return $response;
    }
}