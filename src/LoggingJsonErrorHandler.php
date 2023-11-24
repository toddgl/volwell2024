<?php
namespace Application;

use Concrete\Core\Error\Handler;
use Concrete\Core\Error\Handler\JsonErrorHandler;
use Concrete\Core\Logging\Logger;
use Concrete\Core\Support\Facade\Database;
use Config;

class LoggingJsonErrorHandler extends JsonErrorHandler
{
    public function handle()
    {
        $e = $this->getException();
	 $ret = parent::handle();

        if (Config::get('concrete.log.errors')) {
            try {
                $e = $this->getInspector()->getException();
                $db = Database::get();
                if ($db->isConnected()) {
                    $l = new Logger(LOG_TYPE_EXCEPTIONS);
                    $l->emergency(
                        sprintf(
                            "Exception Occurred: %s:%d %s (%d)\n",
                            $e->getFile(),
                            $e->getLine(),
                            $e->getMessage(),
                            $e->getCode()
                        ),
                        array($e)
                    );
                }
            } catch (\Exception $e) {
            }
        }

        return $ret;
    }
}
