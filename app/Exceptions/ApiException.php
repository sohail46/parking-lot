<?php

namespace App\Exceptions;

class ApiException extends \Exception
{
    protected $group; //group to which exception belongs. group viz; client, server, database, semantic
    protected $statusCode; //http status code
    protected $statusText; //http status text
    protected $type; //exception group are further classified to types
    protected $details;


    public static $exceptionTypes = [
        //Client
        400 => ['type' => 'BadRequest', 'message' => 'Bad request'],
        401 => ['type' => 'Unauthorized', 'message' => 'Unauthorized'],
        402 => ['type' => 'PaymentRequired', 'message' => 'Payment required'],
        403 => ['type' => 'Forbidden', 'message' => 'Forbidden'],
        404 => ['type' => 'NotFound', 'message' => 'Not found'],
        405 => ['type' => 'MethodNotAllowed', 'message' => 'Invalid request method'],
        406 => ['type' => 'NotAcceptable', 'message' => 'Not acceptable'],
        407 => ['type' => 'ProxyAuthenticationRequired', 'message' => 'Proxy authentication required'],
        408 => ['type' => 'RequestTimeout', 'message' => 'Request timeout'],
        409 => ['type' => 'Conflict', 'message' => 'Conflict'],
        410 => ['type' => 'Gone', 'message' => 'Gone'],
        411 => ['type' => 'LengthRequired', 'message' => 'Length required'],
        412 => ['type' => 'PreconditionFailed', 'message' => 'Precondition failed'],
        413 => ['type' => 'RequestEntityTooLarge', 'message' => 'Request entity too large'],
        414 => ['type' => 'Request-URITooLong', 'message' => 'Request-URI too long'],
        415 => ['type' => 'UnsupportedMediaType', 'message' => 'Unsupported media type'],
        416 => ['type' => 'RequestedRangeNotSatisfiable', 'message' => 'Requested range not satisfiable'],
        417 => ['type' => 'ExpectationFailed', 'message' => 'Expectation failed'],
        418 => ['type' => 'Imateapot', 'message' => 'I\'m a teapot'], // RFC2324
        422 => ['type' => 'UnprocessableEntity', 'message' => 'Unprocessable entity'], // RFC4918
        423 => ['type' => 'Locked', 'message' => 'Locked'], // RFC4918
        424 => ['type' => 'FailedDependency', 'message' => 'Failed dependency'], // RFC4918
        426 => ['type' => 'UpgradeRequired', 'message' => 'Upgrade required'], // RFC2817
        428 => ['type' => 'PreconditionRequired', 'message' => 'Precondition required'], // RFC6585
        429 => ['type' => 'TooManyRequests', 'message' => 'Too many requests'], // RFC6585
        431 => ['type' => 'RequestHeaderFieldsTooLarge', 'message' => 'Request header fields too large'], // RFC6585
        //Server (Database exception comes under Server)
        500 => ['type' => 'InternalServerError', 'message' => 'Internal server error'],
        501 => ['type' => 'NotImplemented', 'message' => 'Not implemented'],
        502 => ['type' => 'BadGateway', 'message' => 'Bad gateway'],
        503 => ['type' => 'ServiceUnavailable', 'message' => 'Service unavailable'],
        504 => ['type' => 'GatewayTimeout', 'message' => 'Gateway timeout'],
        505 => ['type' => 'HTTPVersionNotSupported', 'message' => 'HTTP version not supported'],
        506 => ['type' => 'VariantAlsoNegotiates', 'message' => 'Variant also negotiates'], // RFC2295
        507 => ['type' => 'InsufficientStorage', 'message' => 'Insufficient storage'], // RFC4918
        508 => ['type' => 'LoopDetected', 'message' => 'Loop detected'], // RFC5842
        510 => ['type' => 'NotExtended', 'message' => 'Not extended'], // RFC2774
        511 => ['type' => 'NetworkAuthenticationRequired', 'message' => 'Network authentication required'], // RFC6585
    ];


    /*
     * @author: Sohail
    */
    public function __construct($group, $statusCode, $statusText, $message = '', $successCode = 0, $details = null)
    {
        $this->type = self::$exceptionTypes[$statusCode]['type'];
        $this->group = $group;
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;
        $this->successCode = $successCode;
        $this->message = !empty($message) ? $message : self::$exceptionTypes[$statusCode]['message'];

        if (!empty($details)) {
            $this->details = $details;
        }
    }

    //exception group are further classified to types....get them
    final public function getType()
    {
        return $this->type;
    }

    //get group to which exception belongs. group viz; client, server, database, semantic
    final public function getGroup()
    {
        return $this->group;
    }

    //get http status code
    final public function getStatusCode()
    {
        return $this->statusCode;
    }

    //get http status text
    final public function getStatusText()
    {
        return $this->statusText;
    }

    //get http status text
    final public function getDetails()
    {
        return $this->details;
    }

    //get success code -- 0/1
    final public function getSuccessCode()
    {
        return $this->successCode;
    }
}
