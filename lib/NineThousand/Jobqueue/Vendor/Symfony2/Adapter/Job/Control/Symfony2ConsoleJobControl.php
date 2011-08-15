<?php

namespace NineThousand\Jobqueue\Vendor\Symfony2\Adapter\Job\Control;

/**
 * SymfonyConsoleJobControl runs jobs in the symfony command line in Jobqueue.
 *
 * PHP version 5
 *
 * @category  NineThousand
 * @package   Jobqueue
 * @author    Jesse Greathouse <jesse.greathouse@gmail.com>
 * @copyright 2011 NineThousand (https://github.com/organizations/NineThousand)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @link      https://github.com/NineThousand/ninethousand-jobqueue
 */

use NineThousand\Jobqueue\Adapter\Job\Control\JobControlInterface;
use NineThousand\Jobqueue\Adapter\Job\Control\Exception\BlankExecutableException;
use NineThousand\Jobqueue\Adapter\Job\Control\Exception\EmptyCommandException;
use NineThousand\Jobqueue\Adapter\Job\Control\Exception\NologgerException;

class Symfony2ConsoleJobControl implements JobControlInterface
{   

    /**
     * @var Placeholder for a logging object.
     */
    private $_logger;
    
    /**
     * Takes an array of command line, params and args and tranforms it into something that can be run.
     *
     * @param array $input
     * @return string
     */
    public function getExecLine(array $input) {
     
        $params = null;
        $args = null;
        
        if (!isset($input['executable']) || empty($input['executable'])) {
            $message = 'No excecutable was defined for this job.';
            $this->log('err', $message);
            throw new BlankExecutableException($message);
        }
        
        $pieces = explode(" ", $input['executable']);
        
        $console = $pieces[0];
        
        if (!isset($pieces[1]) || empty($pieces[1])) {
            $message = 'No symfony console command was specified.';
            $this->log('err', $message);
            throw new EmptyCommandException($message);
        } else {
            $command = $pieces[1];
        }
        
        if (!empty($input['params'])) { 
            $params = '';
            foreach ($input['params'] as $key => $value) {
                $params .= $key . '=' . $value . ' ';
            }
            $params = trim($params);
        }
        
        if (!empty($input['args'])) { 
            $args = implode(" ", $input['args']);
        }
        
        return trim($console.' '.$command.' '.$params.' '.$args);
        
    }

    /**
     * Runs an arbitrary command line and returns a variable containing status, message, and severity
     *
     * @param string $execLine
     * @return array
     */
    public function run($execLine) 
    {
        $result = array();
        $this->log('debug', 'Attempting to execute: "'. $execLine .'".');
        exec($execLine, $output, $return);
        $result['message'] = implode("\n", $output);
        if ($return !== 0 ) {
            $result['status'] = 'fail';
            $result['severity'] = 'err';
        } else {
            $result['status'] = 'success';
            $result['severity'] = 'debug';
        }        
        return $result;
        
    }
    
    /**
     * Appends a new log message to the log
     *
     * @param string $severity
     * @param string $message
     */
    public function log($severity, $message) 
    {
        try {
            $this->_logger->{$severity}($message);
        } catch (NoLoggerException $e) {}
    }

    
    /**
     * Sets the logger
     *
     * @param object $logger
     */
    public function setLogger($logger)
    {
        $this->_logger = $logger;
    }
    
    /**
     * Gets the logger
     *
     * @return object
     */
    public function getLogger()
    {
        return $this->_logger;
    }
    
    /**
     * method to run before run is called
     */
    public function preRun()
    {

    }
    
    /**
     * method to run after run is called
     */
    public function postRun()
    {

    }
    
}

