<?php
namespace BOTK\Core;

use PHPUnit_Framework_TestCase;
use BOTK\Core\HttpErrorException,
    BOTK\Core\Models\HttpProblem;

/**
 * @covers BOTK\Core\HttpErrorException
 * @covers BOTK\Core\Models\HttpProblem
 * @covers BOTK\Core\ErrorManager
 */
class ErrorManagerTest extends PHPUnit_Framework_TestCase
{
    
    public function testErrorHandleReset()
    {
        $errorManager = ErrorManager::getInstance()
            ->registerErrorHandler();
        $this->assertNotNull($errorManager->getErrorHandler());
        
        $errorManager->registerErrorHandler();
        $this->assertNull($errorManager->getErrorHandler());
    }

   
    /**
     * @expectedException \ErrorException
     */
    public function testErrorExceptions()
    {
        $errorManager = ErrorManager::getInstance()->registerErrorHandler();
        $x = $y +1 ; // trigger notice         
    }

}
