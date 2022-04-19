<?php
// helper class for logger
namespace Admin\Components;

use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

class MyLogger
{
    /**
     * log($message,$opr)
     *function to log message in log file
     * 
     * @param [type] $message
     * @param [type] $opr
     * @return void
     */
    public function log($opr, $message)
    {
        $adapter = new Stream('../apps/admin/logs/authentication.log');
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        $logger->$opr($message);
    }
}
