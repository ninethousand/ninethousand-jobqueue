<?php

namespace NineThousand\Jobqueue;

/**
 * Job is an abstract class with which to create more specific jobs in Jobqueue.
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

use NineThousand\Jobqueue\Adapter\Job\JobAdapterInterface;

abstract class Job
{
    /**
     * @var placeholder for the JobAdapter
     */
    protected $adapter;

    /**
     * @var array Placeholder for the execution result info
     */
    protected $result = array();
    
    /**
     * @var string Retains the command line execution string
     */
    protected $execLine = null;

    /**
     * Executes the job in whatever fashion is defined by the job type.
     */
    public function run() 
    {
        $this->preRun();
        $this->result = $this->adapter->run($this->execLine);
        $this->postRun();
    }
    
    /**
     * Rewinds the retry count by an arbitrary numeric value.
     *
     * @param int $value
     */
    public function rewindAttempts($value) 
    {
        if ((($attempts = $this->getAttempts()) - $value) > 1) {
            $attempts = ($attempts - $value);
        } else {
            $attempts = 0;
        }
        
        $this->setAttempts($attempts);
    }
    
    /**
     * Rewinds the retry count by an arbitrary numeric value.
     *
     * @param int $value optional increases the retry count by an arbitrary amount default is 1
     */
    public function incrementAttempts($value = 1)
    {
        $attempts = $this->getAttempts();
        $this->adapter->setAttempts(($attempts + $value));
    }
    
    /**
     * Hook to perform operations before run.
     */
    public function preRun()
    {
        $this->execLine = $this->adapter->getExecline(array(
            'executable' => $this->adapter->getExecutable(),
            'params'     => $this->adapter->getParams(),
            'args'       => $this->adapter->getArgs(),
        ));
    }
    
    /**
     * Hook to perform operations after run.
     */
    public function postRun()
    {
        $this->adapter->log('debug', $this->result['message']);
        $this->setStatus($this->result['status']);
        $this->setLastRunDate(new \DateTime);
        $this->addHistory($this->result);
        $this->incrementAttempts(1);
    }
    
    /**
     * @return NineThousand\Jobqueue\Adapter\Job\JobAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param NineThousand\Jobqueue\Adapter\Job\JobAdapterInterface $adapter
     */
    public function setAdapter(JobAdapterInterface $adapter) 
    {
        $this->adapter = $adapter;
    }

    /**
     * @return int
     */
    public function getId()
    {
        $this->adapter->getId();
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->adapter->getName();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->adapter->setName($name);
    }

    /**
     * @return int
     */
    public function getRetry()
    {
        return $this->adapter->getRetry();
    }

    /**
     * @param int $retry
     */
    public function setRetry($retry)
    {
        $this->adapter->setRetry($retry);
    }

    
    /**
     * @return int
     */
    public function getCooldown()
    {
        return $this->adapter->getCooldown();
    }
        
    /**
     * @param int $cooldown
     */
    public function setCooldown($cooldown)
    {
        $this->adapter->setCooldown($cooldown);
    }

    /**
     * @return int
     */
    public function getMaxRetries()
    {
        return $this->adapter->getMaxRetries();
    }

    /**
     * @param int $maxRetries
     */
    public function setMaxRetries($maxRetries)
    {
        $this->adapter->setMaxRetries($maxRetries);
    }

    /**
     * @return int
     */
    public function getAttempts()
    {
        return $this->adapter->getAttempts();
    }

    /**
     * @param int $attempts
     */
    public function setAttempts($attempts)
    {
        $this->adapter->setAttempts($attempts);
    }

    /**
     * @return string
     */
    public function getExecutable()
    {
        return $this->adapter->getExecutable();
    }

    /**
     * @param string $executable
     */
    public function setExecutable($executable)
    {
        $this->adapter->setExecutable($executable);
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->adapter->getParams();
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->adapter->setParams($params);
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->adapter->getArgs();
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args)
    {
        $this->adapter->setArgs($args);
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->adapter->getTags();
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        return $this->adapter->setTags($tags);
    }
    
    /**
     * @return array
     */
    public function getHistory()
    {
        return $this->adapter->getHistory();
    }

    /**
     * @param array $result
     */
    public function addHistory(array $result)
    {
        return $this->adapter->addHistory($result);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->adapter->getType();
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->adapter->setType($type);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->adapter->getStatus();
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->adapter->setStatus($status);
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->adapter->getCreateDate();
    }

    /**
     * @param \DateTime $date
     */
    public function setCreateDate(\DateTime $date)
    {
        $this->adapter->setCreateDate($date);
    }

    /**
     * @return \DateTime
     */
    public function getLastRunDate()
    {
        return $this->adapter->getLastRunDate();
    }
    
    /**
     * @param \DateTime $date
     */
    public function setLastRunDate(\DateTime $date)
    {
        $this->adapter->setLastRunDate($date);
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->adapter->getActive();
    }

    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->adapter->setActive($active);
    }

    /**
     * @return string
     */
    public function getSchedule()
    {
        return $this->adapter->getSchedule();
    }

    /**
     * @param string $schedule
     */
    public function setSchedule($schedule)
    {
        $this->adapter->setSchedule($schedule);
    }
    
    /**
     * @return int
     */
    public function getParent()
    {
        $this->adapter->getParent();
    }
    
    /**
     * @param int $parent
     */
    public function setParent($parent)
    {
        $this->adapter->setParent($parent);
    }
}

