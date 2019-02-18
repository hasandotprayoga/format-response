# Laravel Format Response For API

## Installation

Add to composer.json
```
    "require": {
        ...
        "hasandotprayoga/format-response": "*"
    },
    "repositories": [
        ...
        {
            "type": "vcs",
            "url": "https://github.com/hasandotprayoga/format-response.git"
        }
    ]
```

On your terminal, run `composer update`

## Configuration

1. Open `app\Http\Controllers\Controller.php`, add one line like this:
    ```php
    class Controller extends BaseController
    {
        use \FormatResponse\FormatResponse; // add this line
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