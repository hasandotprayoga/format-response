# Laravel Format Response For API

## Installation

Add to composer.json
```
    "require": {
        ...
        "hasandotprayoga/format-response": "*"
    }
```

On your terminal, run `composer update`

## Configuration

1. Open `app\Http\Controllers\Controller.php`, add one line like this:
    ```php
    class Controller extends BaseController
    {
        use \hasandotprayoga\FormatResponse; // add this line
    }  
    ```

2. Open `app\Exceptions\Handler.php`, edit method render like this:

    ```php
    public function render($request, Exception $e)
    {
        if($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        $rendered = parent::render($request, $e);
        
        $controller = app('\App\Http\Controllers\Controller');
        $controller->responseCode = $rendered->getStatusCode();
        $controller->messages = $e->getMessage();

        return $controller->response();
    }
    ```

## How to use
Change your return response to **$this->response();**

    $this->response($results, $resCode, $messages, $metaData, $request);
    /*
        @param $results default []
        @param $resCode default 200
        @param $messages default ''
        @param $metaData default []
        @param $request default []
    */
Example

    return $this->response([1,2,4], 200, 'ok', [
        'selectedPage' => 1, 
        'selectedItem' => NULL, 
        'totalPage' => 2, 
        'totalItem' => 5, 
        'totalItemPerPage' => 5 
    ], [
        'get'=>[
            'field1'=>1,
            'field2'=>2
        ],
        'post'=>[]
    ]);
    
    // Or

    $this->results = [1,2,4];
    $this->resCode = 200;
    $this->messages = 'Ok';
    $this->metaData = [
        'selectedPage' => 1, 
        'selectedItem' => NULL, 
        'totalPage' => 2, 
        'totalItem' => 5, 
        'totalItemPerPage' => 5 
    ];
    $this->request = [
        'get'=>[
            'field1'=>1,
            'field2'=>2
        ],
        'post'=>[]
    ];

    return $this->response();

## Example Response
```json
{
    "code": 200,
    "description": "Ok",
    "response": {
        "results": [
            {
                "id": 1,
                "balance": 100000,
                "recStatus": "DELETE",
                "recTimeCreate": "2019-01-16 17:10:01",
                "recTimeUpdate": "2019-01-29 04:39:22"
            },
            {
                "id": 2,
                "balance": null,
                "recStatus": "PUBLISH",
                "recTimeCreate": "2018-04-12 23:36:03",
                "recTimeUpdate": null
            },
            {
                "id": 3,
                "balance": null,
                "recStatus": "PUBLISH",
                "recTimeCreate": "2018-04-12 23:10:03",
                "recTimeUpdate": null
            },
        ],
        "messages": "ok",
        "metaData": {
            "selectedPage": 1,
            "selectedItem": null,
            "totalPage": 1,
            "totalItem": 3,
            "totalItemPerPage": 3
        }
    },
    "request": {
        "get": {
            "id": "1"
        }
    },
}
```