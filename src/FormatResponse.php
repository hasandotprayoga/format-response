<?php

namespace FormatResponse;

use Illuminate\Http\Request;

/**
 * Trait FormatResponse
 * @package FormatResponse
 */
trait FormatResponse
{
    /**
     * @var array
     */
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

    /**
     * @var array
     */
    public $results         =[];
    /**
     * @var string
     */
    public $responseCode    ='';
    /**
     * @var array
     */
    public $messages = [];
    /**
     * @var array
     */
    public $metaData        =[];
    /**
     * @var array
     */
    public $request         =[];

    /**
     * @return mixed
     */
    public function getResDescription()
    {
        return $this->responseCodes[$this->responseCode];
    }

    /**
     * @return array
     */
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

    /**
     * @param array $results
     * @param int $resCode
     * @param array $messages
     * @param array $metaData
     * @param array $request
     * @return mixed
     */
    public function response($results=[], $resCode = 200, $messages=[], $metaData=[], $request=[])
    {
        if(empty($this->results)){$this->results = $results;}
        if(empty($this->responseCode)){$this->responseCode = $resCode;} 
        if(empty($this->messages)){$this->messages = $messages;}
        if(empty($this->metaData)){$this->metaData = $metaData;}
        if(empty($this->request)){$this->request = $request;}

        return response()
            ->json(
                $this->data(),
                $this->responseCode
            );
    }

    /**
     * @param Request $request
     * @param array $errors
     * @return mixed
     */
    public function buildFailedValidationResponse(Request $request, array $errors)
    {
        $this->responseCode = 422;
        $this->results = [];
        $this->messages = $errors;
        return $this->response();
    }
}