<?php

class Characters extends Base
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

        if(!empty($episode['characters'])){
            $characters_ids = [];

            foreach($episode['characters'] as $character_url){
                $exploded_url = explode("/",$character_url);
                $characters_ids[] = $exploded_url[5];
            }

            if(!empty($characters_ids)){
                $url = 'https://rickandmortyapi.com/api/character/'.implode(",",$characters_ids);
                
                $fetch = $this->fetchUrl($url);
        
                if(!$fetch){
                    return false;
                }

                $characters = json_decode($fetch,true);
                
                if(!empty($characters)){
                    $result = [];

                    foreach($characters as $character){

                        $exploded_location_url = explode("/",$character['location']['url']);

                        $result[] = [
                            'id' => $character['id'],
                            'name' => $character['name'],
                            'gender' => $character['gender'],
                            'image' => $character['image'],
                            'location_id' => isset($exploded_location_url[5]) ? $exploded_location_url[5] : false
                        ];
                    }

                    $response['status'] = 'success';
                    $response['result'] = $result;

                    return $response;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}