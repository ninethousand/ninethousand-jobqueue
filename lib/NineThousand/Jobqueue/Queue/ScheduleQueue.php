<?php

namespace NineThousand\Jobqueue\Queue;

/**
 * ScheduleQueue is the object model for the queue of jobs flagged to run on a schedule
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

use NineThousand\Jobqueue\Queue;
use NineThousand\Jobqueue\Job\JobInterface;
use NineThousand\Jobqueue\Queue\QueueInterface;
use NineThousand\Jobqueue\Adapter\Queue\QueueAdapterInterface;
 
class ScheduleQueue extends Queue implements QueueInterface
{
 
    /**
     * @var null|string retains the sort order
     */
    protected $sortby = null;
    
    /**
     * @var null|string retains the filter option
     */
    protected $filterby = null;


    /**
     * Constructs the object.
     *
     * @param NineThousand\Jobqueue\Adapter\Queue\QueueAdapterInterface $adapter
     */
    public function __construct(QueueAdapterInterface $adapter)
    {
        $this->setAdapter($adapter);
        parent::__construct();
    }
    
    /**
     * Creates a static instance of ActiveQueue.
     *
     * @param $adapter
     * @return NineThousand\Jobqueue\Queue\ActiveQueue
     */
    public static function factory($adapter)
    {
        return new self($adapter);
    }
    
    /**
     * Fetches all jobs in this queue.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->adapter->getScheduled();
    }
    
    /**
     * Converts a job from a foreign queue to qualify for this queue.
     * 
     * @param NineThousand\Jobqueue\Job\JobInterface $job
     */
    public function adoptJob(JobInterface $job)
    {

    }
    
}
