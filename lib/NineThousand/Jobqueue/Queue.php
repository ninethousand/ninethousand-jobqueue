<?php

namespace NineThousand\Jobqueue;

/**
 * Queue is an abstract class with which to create more specific queues in Jobqueue.
 *
 * PHP version 5
 *
 * @category  NineThousand
 * @package   Jobqueue
 * @author    Jesse Greathouse <jesse.greathouse@gmail.com>
 * @copyright 2011 NineThousand (https://github.com/organizations/NineThousand)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @link      https://github.com/NineThousand/Jobqueue
 */

use NineThousand\Jobqueue\Adapter\Queue\QueueAdapterInterface;
use NineThousand\Jobqueue\Job\JobInterface;
 
abstract class Queue
{

    /**
     * @var array list of jobs in this queue
     */
    protected $jobs;
    
    /**
     * @var placeholder for the adapter
     */
    protected $adapter;
    
    /**
     * @var string retains the sort order
     */
    protected $sortby;
    
    /**
     * @var string retains the filter option
     */
    protected $filterby;


    /**
     * Constructs the object
     */
    public function __construct()
    {
        $this->jobs = $this->getAll();
    }

    /**
     * @return NineThousand\Jobqueue\Adapter\Queue\QueueAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
    
    /**
     * @param NineThousand\Jobqueue\Adapter\Queue\QueueAdapterInterface $adapter
     */
    public function setAdapter(QueueAdapterInterface $adapter) 
    {
        $this->adapter = $adapter;
    }
    
    /**
     * Compares the first array element to a second array element.
     * Returns positive if the first has a less value than the second else returns negative.
     *
     * @param JobInterface $a
     * @param JobInterface $b
     * @return bool
     */
    protected function cmp(JobInterface $a, JobInterface $b)
    {
        if ($this->sortby === null) return 0;
        $method = 'get'.ucwords(strtolower($this->sortby));
        if ($a->{$method}() == $b->{$method}()) return 0;
        return ((method_exists($a, $method) && method_exists($b, $method)) && ($a->{$method}() < $b->{$method}())) ? 1 : -1;
    }
    
    /**
     * Sorts the job list according to the standing sort order.
     *
     * @param bool $reverse optional flags the sort for reverse order default is false
     */
    public function sort($reverse = false)
    {
        usort($this->jobs, "self::cmp");
    }
    
    /**
     * Sorts the job list by an arbitrary column.
     *
     * @param string $column the column of the queue for which to sort by
     * @param bool $reverse optional flags the sort for reverse order default is false
     */
    public function sortBy($column, $reverse = false) {
        $this->sortby = $column;
        $this->sort($reverse);
        $this->rewind();
    }
    
    /**
     * Checks whether a job property meets the filter criteria -- 
     * retrns true if it does else false.
     *
     * @param JobInterface $job
     * @param mixed $value
     * @return bool
     */
    protected function chk(JobInterface $job, $value)
    {
        if ($this->filterby === null) return true;
        $method = 'get'.ucwords(strtolower($this->filterby));
        return (method_exists($job, $method) && $job->{$method}() == $value) ? true : false;
    }
    
    /**
     * Filter the job list.
     *
     * @param bool $exclude optional filter jobs that match standing criteria defaults is false
     */
    public function filter($exclude = false)
    {
        $list = array_filter($this->jobs, "self::chk");
        if ($exclude) {
            $list = array_diff($this->jobs, $list);
        }
        $this->jobs = $list;
    }
    
    /**
     * Filters the job list by an arbitrary column.
     *
     * @param string $column the column of the queue for which to filter by
     * @param bool $exclude optional filter jobs that match standing criteria defaults is false
     */
    public function filterBy($column, $exclude = false) {
        $this->filterby = $column;
        $this->filter($exclude);
        $this->rewind();
    }
    
    /**
     * rewinds the queue to its original state with pointer at the beginning of the queue.
     */
    public function rewind()
    {
        reset($this->jobs);
    }

    /**
     * Advances the queue to the next job and returns it.
     * 
     * @return NineThousand\Jobqueue\Job\JobInterface
     */
    public function next()
    {
        return next($this->jobs);
    }
    
    /**
     * Returns the array key of the current job.
     * 
     * @return mixed
     */
    function key() 
    {
        return key($this->jobs);
    }
    
    /**
     * Returns an indication of if the current item is valid
     * 
     * @return bool
     */
    function valid() {
        return key($this->jobs) !== null;
    }
    
    /**
     * Reverts the queue to the previous job and returns it.
     * 
     * @return NineThousand\Jobqueue\Job\JobInterface
     */
    public function prev()
    {
        return prev($this->jobs);
    }
    
    /**
     * Returns the current job.
     * 
     * @return NineThousand\Jobqueue\Job\JobInterface
     */
    public function current()
    {
        return current($this->jobs);
    }
    
    /**
     * Re-initializes the queue with a rereshed list of jobs.
     * Reverts the pointer to the beginning of the job list.
     */
    public function refresh()
    {
        $this->rewind();
        unset($this->jobs);
        $this->jobs = $this->getAll();
        $this->sort();
    }
    
    /**
     * Provides a count of total jobs.
     *
     * @return int
     */
    public function totalJobs()
    {
        return count($this->jobs);
    }
    
}
