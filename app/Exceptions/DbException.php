<?php

namespace App\Exceptions;

class DbException
{
    private $code;
    private $sqlState;
    private $message;
    private $status;
    private $group;
    
    public static $exceptionMessages = [
        '1045' => 'Access denied for user',
        '1049' => 'Unknown Database',
        '1146' => 'Table or View not found',
        '2005' => 'Unable to connect to the server',
        '1062' => 'Duplicate entry for the unique key'
    ];
    
    public static $exceptionStatus = [
        '1045' => 'access_denied_error',
        '1049' => 'bad_db_error',
        '1146' => 'no_such_table',
        '2005' => 'unknown_host',
        '1062' => 'dup_entry'
    ];
    
    public function __construct(\Exception $e)
    {
        $this->group = 'database_'.strtolower(str_replace('Exception', '', basename(str_replace('\\', '/', get_class($e)))));
      
        $this->code = $e->errorInfo[1];
        $this->sqlState = $e->getCode();

        $this->status = isset(self::$exceptionStatus[$this->code]) ? self::$exceptionStatus[$this->code] : 'unknown_db_error';
        $this->message = 'Internal Server Error';

        $details['driver_message'] = $e->getMessage();
        throw new ApiException($this->group, 500, $this->status, $this->message, 0, $details);
    }
}
