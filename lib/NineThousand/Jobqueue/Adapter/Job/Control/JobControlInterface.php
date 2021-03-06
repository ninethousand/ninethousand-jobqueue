<?php

namespace NineThousand\Jobqueue\Adapter\Job\Control;

/**
 * JobAdapterInterface defines the required functions for Job  Control Adapter models in Jobqueue.
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

use NineThousand\Jobqueue\Adapter\AdapterInterface;

interface JobControlInterface extends AdapterInterface
{   
    public function getExecLine(array $input);
    public function run($execLine);
    public function log($severity, $message);
    public function preRun();
    public function postRun();
    
    public function setLogger($logger);
    public function getLogger();
    
}
