<?php

namespace NineThousand\Jobqueue\Vendor\Doctrine\Adapter\Queue;

/**
 * DoctrineQueueAdapter is a Queue adapter for using Doctrine Entities as Jobs in Jobqueue.
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

use NineThousand\Jobqueue\Adapter\Queue\QueueAdapterInterface;

use Doctrine\ORM\EntityManager;

class DoctrineQueueAdapter implements QueueAdapterInterface
{   

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $_em;
    
    /**
     * @var string name of the entity class to use for jobs
     */
    private $_entityClass;
    
    /**
     * @var string name of the job adapter class to use for jobs
     */
    private $_adapterClass;
    
    /**
     * @var string name of the job model class to use for jobs
     */
    private $_jobClass;
    
    /**
     * @var array the options as defined by the service params
     */
    private $_options;
    
    /**
     * @var object the logger used in the application
     */
    private $_logger;
    
    /**
     * Constructs the object.
     *
     * @param array $options
     * @param Doctrine\ORM\EntityManager $em
     */
    public function __construct($jobClass, array $options, EntityManager $em, $logger) 
    {
        $this->_em = $em;
        $this->_options = $options;
        $this->_logger = $logger;
        $this->_entityClass = $this->_options['job_entity_class'];
        $this->_adapterClass = $this->_options['job_adapter_class'];
        $this->_jobClass = $jobClass;
    }
    
    
    /**
     * Retrieves all jobs classified as "Active".
     *
     * @return array
     */
    public function getActive()
    {
        $jobs = array();
        
        $active = $this->_em->getRepository($this->_entityClass)->findByActive(1);
        foreach ($active as $record) {
            $job = new $this->_jobClass(call_user_func($this->_adapterClass . '::factory', $this->_options, $record, $this->_em, $this->_logger));
            array_push($jobs, $job);
        }
        
        return $jobs;
    }
    
    /**
     * Retrieves all jobs classified as "Inactive".
     *
     * @return array
     */
    public function getInactive()
    {
        $jobs = array();
                
        $inactive = $this->_em->getRepository($this->_entityClass)->findByActive(0);
        foreach ($inactive as $record) {
            $job = new $this->_jobClass(call_user_func($this->_adapterClass . '::factory', $this->_options, $record, $this->_em, $this->_logger));
            array_push($jobs, $job);
        }
        
        return $jobs;
    
    }
    
    /**
     * Retrieves all jobs flagged for "Retry".
     *
     * @return array
     */
    public function getRetries()
    {
        $jobs = array();
        
        $retries = $this
                        ->_em
                        ->getRepository($this->_entityClass)
                        ->createQueryBuilder('s')
                        ->andWhere('s.active = :active')
                        ->andWhere('s.retry = :retry')
                        ->andWhere('s.attempts < s.maxRetries')
                        ->setParameters(array('active' => 0, 'retry' => 1))
                        ->getQuery()
                        ->getResult(); 
                        
        foreach ($retries as $record) {
            $job = new $this->_jobClass(call_user_func($this->_adapterClass . '::factory', $this->_options, $record, $this->_em, $this->_logger));
            array_push($jobs, $job);
        }
        return $jobs;
    
    }
    
    
    /**
     * Retrieves all "Scheduled" jobs.
     *
     * @return array
     */
    public function getScheduled()
    {
        $jobs = array();
        
        $scheduled = $this
                        ->_em
                        ->getRepository($this->_entityClass)
                        ->createQueryBuilder('s')
                        ->andWhere('s.schedule <> :schedule')
                        ->andWhere('s.active = :active')
                        ->setParameters(array('active' => 0, 'schedule' => 'NULL'))
                        ->getQuery()
                        ->getResult(); 
        
        foreach ($scheduled as $record) {
            $job = new $this->_jobClass(call_user_func($this->_adapterClass . '::factory', $this->_options, $record, $this->_em, $this->_logger));
            array_push($jobs, $job);
        }
        return $jobs;
    
    }   
    
}
