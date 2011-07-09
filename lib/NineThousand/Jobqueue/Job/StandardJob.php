<?php

namespace NineThousand\Jobqueue\Job;

/**
 * Standard Job is a regular implementation of job.
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

use NineThousand\Jobqueue\Job;
use NineThousand\Jobqueue\Job\JobInterface;
use NineThousand\Jobqueue\Adapter\Job\JobAdapterInterface;
 
class StandardJob extends Job implements JobInterface
{   
    /**
     * Constructs the object.
     *
     * @param NineThousand\Jobqueue\Adapter\Job\JobAdapterInterface $adapter
     */
    public function __construct(JobAdapterInterface $adapter) 
    {
        $this->setAdapter($adapter);
    }
    
    /**
     * Creates an active instance from the template of a scheduled job.
     * @return NineThousand\Jobqueue\Job\JobInterface
     */
    public function spawn() 
    {
        $adapter = $this->adapter->spawn();
        return new self($adapter);
    }
    
}
