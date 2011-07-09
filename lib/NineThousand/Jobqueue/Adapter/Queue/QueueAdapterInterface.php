<?php

namespace NineThousand\Jobqueue\Adapter\Queue;

/**
 * QueueAdapterInterface defines the required functions for Queue Adapter models in Jobqueue.
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

interface QueueAdapterInterface extends AdapterInterface
{   
    public function getActive();
    public function getInactive();
    public function getRetries();
    public function getScheduled();
    
}
