<?php

class One_gate
{
    var $request = [];

    public function __construct($full_request)
    {
        
        if(!$this->check_request_parse($full_request)){
            $this->response('error',[
                'error_type' => 'request_not_parse'
            ]);
            return false;
        }

        $requests = [];

        foreach($full_request as $test_batch){
            if(is_array($test_batch)){
                $requests = $full_request;
                break;
            }
        }

        if(empty($requests)) $requests[] = $full_request;

        $responce_object = [];

        foreach($requests as $request){
            if($this->check_request_valid($request)){
                $this->request = $request;

                $allowed_methods = ['locations','count_locations','count_characters','characters','character_locations'];

                if(in_array($this->request['method'], $allowed_methods)){
                    $class_name = ucfirst($this->request['method']);
                    $object = new $class_name($this->request['params']);
                    $result = $object->result();
                    
                    if(!$result){
                        $responce_object[] = $this->response('error',[
                            'error_type' => 'internal_error'
                        ]);
                    }

                    if($result['status'] == 'success'){
                        $to_response['result'] = $result['result'];
                        if(isset($this->request['id'])){
                            $to_response['id'] = $this->request['id'];
                        }
                        $responce_object[] = $this->response('success', $to_response);
                    } else {
                        $responce_object[] = $this->response('error',[
                            'error_type' => 'invalid_params'
                        ]);
                     
                    }
                    
                } else {
                    $responce_object[] = $this->response('error',[
                        'error_type' => 'method_not_allowed'
                    ]);
   
                }

            } else {
                $responce_object[] = $this->response('error',[
                    'error_type' => 'request_not_valid'
                ]);
            }
        }

        if(count($responce_object) == 1){
            $responce_object = $responce_object[0];
        }

        echo json_encode($responce_object, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function check_request_parse($request)
    {
        return is_array($request);
    }

    private function check_request_valid($request)
    {
        if(!isset($request['jsonrpc']) or $request['jsonrpc'] != '2.0') return false;
        if(!isset($request['method']) or $request['method'] == '') return false;
        return true;
    }

    private function response($status, $data)
    {
        $responce_object = [
            'jsonrpc' => '2.0',
            'id' => NULL
        ];

        if($status == 'success'){
            $responce_object['result'] = $data['result'];
            $responce_object['id'] = $data['id'];
        }

        if($status == 'error'){
            if($data['error_type'] == 'request_not_parse'){
                $code = '-32700';
                $message = 'Parse error';
            }
            if($data['error_type'] == 'request_not_valid'){
                $code = '-32600';
                $message = 'Invalid request';
            }
            if($data['error_type'] == 'method_not_allowed'){
                $code = '-32601';
                $message = 'Method not found or not allowed';
            }
            if($data['error_type'] == 'invalid_params'){
                $code = '-32602';
                $message = 'Invalid params';
            }
            if($data['error_type'] == 'internal_error'){
                $code = '-32603';
                $message = 'Internal error';
            }
            
            $responce_object['error'] = [
                'code' => $code,
                'message' => $message,
                'data' => 'some additional info', //TODO: create human readable meanings
            ];

            // simple error logging
            
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/json_rpc_test/error-log.txt',date('Y-m-d').' '.json_encode($responce_object['error'],JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\r\n",FILE_APPEND);
        }

        return $responce_object;
    }
}
