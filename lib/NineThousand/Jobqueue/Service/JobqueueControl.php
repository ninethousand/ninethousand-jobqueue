<?php

namespace NineThousand\Jobqueue\Service;

use NineThousand\Jobqueue\Queue\ActiveQueue;
use NineThousand\Jobqueue\Queue\InactiveQueue;
use NineThousand\Jobqueue\Queue\RetryQueue;
use NineThousand\Jobqueue\Queue\ScheduleQueue;

use Symfony\Component\Config\FileLocator;


class JobqueueControl
{  

    protected $queues = array();

    protected $db;
   
    protected $adapter;
   
    protected $logger;
    
    protected $cronClass;
    
    public function __construct($jobClass, $adapterClass, $adapterOptions, $db, $logger)
    {
       $this->logger = $logger;
       $this->db = $db;
       $this->adapter = new $adapterClass($jobClass, $adapterOptions, $this->db, $logger);
       $this->cronClass = $adapterOptions['cron_class'];
       $this->loadQueues();
    }

    public function run()
    {

       $this->logger->debug('Entered new cycle at: ' . get_class($this). '::run()');
       
       $this->runRetryQueue();
       
       $this->runScheduleQueue();
       
       $this->runActiveQueue();
    }
   
    private function loadQueues()
    {
        $this->setActiveQueue(ActiveQueue::factory($this->adapter));
        $this->setInactiveQueue(InactiveQueue::factory($this->adapter));
        $this->setRetryQueue(RetryQueue::factory($this->adapter));
        $this->setScheduleQueue(ScheduleQueue::factory($this->adapter));
    }
   
    protected function runActiveQueue()
    {
        $this->getActiveQueue()->refresh();
        $this->logger->debug($this->getActiveQueue()->totalJobs() . ' jobs found the Active in queue.');
        foreach ($this->getActiveQueue() as $job)
        {
            $job->run();
            if ($job->getStatus() == 'failure' 
                && $job->getRetry() 
                && $job->getAttempts() < $job->getMaxRetries()) 
            {
                $this->getRetryQueue()->adoptJob($job);
            } else {
                $this->getInactiveQueue()->adoptJob($job);
            }
            
        }
    }
    
    protected function runRetryQueue()
    {
        $this->getRetryQueue()->refresh();
        $this->logger->debug($this->getRetryQueue()->totalJobs() . ' jobs found in the Retry queue.');
        foreach ($this->getRetryQueue() as $job)
        {
            $now = new \DateTime("now");
            if (($now->getTimestamp() - $job->getLastrunDate()->getTimestamp()) > $job->getCooldown()) 
            {
                $this->getActiveQueue()->adoptJob($job);
            }
            
        }
    }
    
    protected function runScheduleQueue()
    {
        $class = $this->cronClass;
        $this->getScheduleQueue()->refresh();
        $this->logger->debug($this->getScheduleQueue()->totalJobs() . ' jobs found in the Schedule queue.');
        foreach ($this->getScheduleQueue() as $job)
        {
            $now = new \DateTime("now");
            $last = (NULL === $job->getLastrunDate()) ? $job->getCreateDate() : $job->getLastrunDate();
            $cron = $class::factory($job->getSchedule());
            $nextTimestamp = $cron->getNextRunDate($last, 0, true)->getTimestamp();
            $nowTimestamp = $now->getTimestamp();
            //add 60 seconds to make sure that it falls outside the end of the minute
            $gap = ($nowTimestamp - ($nextTimestamp+60));
            if ($gap > 0) 
            {
                $job->setLastRunDate($now);
                $this->getActiveQueue()->adoptJob($job->spawn());
            }
        }
    }
   
   protected function announce($method = null)
    {
        $this->logger->debug('EXECUTING METHOD: ' . end(explode('\\', get_class($this))) . '::'. $method);
    }
   
    protected function getOptions() 
    {
        return $this->_options;
    }
    
    public function getActiveQueue() 
    {
        return $this->queues['active'];
    }
   
    public function setActiveQueue(ActiveQueue $queue) 
    {
        $this->queues['active'] = $queue;
    }
   
    public function getInactiveQueue() 
    {
        return $this->queues['inactive'];
    }
    
    public function setInactiveQueue(InactiveQueue $queue) 
    {
        $this->queues['inactive'] = $queue;
    }
    
    public function getRetryQueue() 
    {
        return $this->queues['retry'];
    }
   
    public function setRetryQueue(RetryQueue $queue) 
    {
        $this->queues['retry'] = $queue;
    }
    
    public function getScheduleQueue() 
    {
        return $this->queues['scheduled'];
    }
   
    public function setScheduleQueue(ScheduleQueue $queue) 
    {
        $this->queues['scheduled'] = $queue;
    }
}
