<?php

namespace FormatResponse;

use Illuminate\Http\Request;

trait FormatResponse
{
    public $responseCodes = [
        200 => 'Ok',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout. Web server menunggu respon terlalu lama'
    ];

    public $results         =[];
    public $responseCode    =200;
    public $messages = [];
    public $metaData        =[];
    public $request         =[];

    public function getResDescription()
    {
        return $this->responseCodes[$this->responseCode];
    }

    public function data()
    {
        $data = [
            'code'          => $this->responseCode,
            'description'   => $this->getResDescription($this->responseCode),
            'response'      => [],
        ];

        $data['response']['results']=$this->results;
        
        (!empty($this->messages))   ?$data['response']['messages']  =$this->messages:'';
        (!empty($this->metaData))   ?$data['response']['metaData']  =$this->metaData:'';
        (!empty($this->request) &&env('APP_DEBUG')==TRUE )    ?$data['request']               =$this->request:'';
        
        (env('APP_DEBUG')==TRUE )   ?$data['debug']=env('APP_DEBUG'):'';

        return $data;
    }

    public function response($results=[], $resCode = 200, $messages=[], $metaData=[], $request=[])
    {
        $this->results = $results;
        $this->responseCode = $resCode;
        $this->messages = $messages;
        $this->metaData = $metaData;
        $this->request = $request;
        
        return response()
            ->json(
                $this->data(),
                $this->responseCode
            );
    }

    public function buildFailedValidationResponse(Request $request, array $errors) 
    {
        $this->responseCode = 422;
        $this->resulst = [];
        $this->messages = $errors;
        return $this->response();
    }
}