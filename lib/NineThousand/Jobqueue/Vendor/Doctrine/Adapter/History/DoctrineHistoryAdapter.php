<?php

namespace NineThousand\Jobqueue\Vendor\Doctrine\Adapter\History;

/**
 * DoctrineHistoryAdapter is a Queue adapter for using Doctrine Entities as Job histories in Jobqueue.
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

use NineThousand\Jobqueue\Adapter\History\HistoryAdapterInterface;

use Doctrine\ORM\EntityManager;

class DoctrineHistoryAdapter implements HistoryAdapterInterface
{   

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $_em;
    
    /**
     * @var string name of the history class
     */
    private $_historyClass;
    
    /**
     * @var string name of the job adapter class to use for jobs
     */
    private $_adapterClass;
    
    /**
     * @var array the options as defined by the service params
     */
    private $_options;
    
    /**
     * @var NineThousand\Jobqueue\Entity\History History ORM
     */
    private $_entry = null;
    
    /**
     * @var int holds the result from Collection::count()
     */
    protected $total = 0;
    
    /**
     * Constructs the object.
     *
     * @param array $options
     * @param Doctrine\ORM\EntityManager $em
     * @param NineThousand\Jobqueue\Entity\History $entry
     */
    public function __construct(array $options, EntityManager $em, $entry = null) 
    {
        $this->_em = $em;
        $this->_options = $options;
        $this->_historyClass = $this->_options['history_entity_class'];
        $this->_adapterClass = $this->_options['job_adapter_class'];
        if (null !== $entry) {
            $this->_entry = $entry;
        }
    }

    /**
     * Retrieves history of jobs ran.
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getHistory($limit = null, $offset = null)
    {
        $entries = array();
        
        $query = $this->_em
                        ->getRepository($this->_historyClass)
                        ->createQueryBuilder('h')
                        ->andWhere('h.active = :active')
                        ->setParameters(array('active' => 1))
                        ->getQuery();
        
        if (null !== $limit) {
            $countQuery = clone $query;
            $countQuery->setParameters($query->getParameters());

            $query->setMaxResults($limit);
            if (null !== $offset) {
                $query->setFirstResult($offset);
            }
            
            $this->setTotal(count($countQuery->getResult())); 
        }
        
        $history = $query->getResult();
        if (!$this->getTotal()) {
            $this->setTotal(count($history));
        }
        
        foreach ($history as $entry) {
            $item = self::factory($this->_options, $this->_em, $entry);
            array_push($entries, $item);
        }
        return $entries;
    }
    
    /**
     * Returns a new instance of HistoryAdapterInterface.
     *
     * @param array $options
     * @param Doctrine\ORM\EntityManager $em
     * @param NineThousand\Jobqueue\Entity\History $entry
     * @return NineThousand\Jobqueue\Adapter\History\DoctrineHistoryAdapter
     */
    public static function factory($options, $em, $entry)
    {
        return new self($options, $em, $entry);
    }
    
    /*
     * @return int
     */
    public function getId() 
    {
        if (null !== $this->_entry) {
            return $this->_entry->getId();
        }
    }
    
    /**
     * @return int
     */   
    public function getJobId()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getId();
        }
    }

    /**
     * @return string
     */
    public function getJobName()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getName();
        }
    }

    /**
     * @return string
     */
    public function getJobExecutable()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getExecutable();
        }
    }

    /**
     * @return string
     */
    public function getJobType()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getType();
        }
    }

    /**
     * @return \DateTime
     */ 
    public function getJobCreateDate()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getDate();
        }
    }

    /*
     * @return int
     */
    public function getJobActive()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getActive();
        }
    }

    /**
     * @return string
     */
    public function getjobSchedule()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getSchedule();
        }
    }

    /*
     * @return int
     */
    public function getJobParent()
    {
        if (null !== $this->_entry) {
            return $this->_entry->getJob()->getParent();
        }
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp() 
    {
        if (null !== $this->_entry) {
            return $this->_entry->getTimestamp();
        }
    }

    /**
     * @return string
     */
    public function getStatus() 
    {
        if (null !== $this->_entry) {
            return $this->_entry->getStatus();
        }
    }

    /**
     * @param string $status
     */ 
    public function setStatus($status) 
    {
        if (null !== $this->_entry) {
            $this->_entry->setStatus($status);
            $this->_em->persist($this->_entry);
            $this->_em->flush();
        }
    }

    /**
     * @return string
     */
    public function getMessage() 
    {
        if (null !== $this->_entry) {
            return $this->_entry->getMessage();
        }
    }

    /**
     * @param string $message
     */ 
    public function setMessage($message) 
    {
        if (null !== $this->_entry) {
            $this->_entry->setMessage($message);
            $this->_em->persist($this->_entry);
            $this->_em->flush();
        }
    }

    /**
     * @return string
     */
    public function getSeverity() 
    {
        if (null !== $this->_entry) {
            return $this->_entry->getSeverity();
        }
    }

    /**
     * @param string $severity
     */ 
    public function setSeverity($severity) 
    {
        if (null !== $this->_entry) {
            $this->_entry->setSeverity($severity);
            $this->_em->persist($this->_entry);
            $this->_em->flush();
        }
    }

    /**
     * @return int
     */
    public function getActive() 
    {
        if (null !== $this->_entry) {
            return $this->_entry->getActive();
        }
    }

    /**
     * @param int $active
     */ 
    public function setActive($active) 
    {
        if (null !== $this->_entry) {
            $this->_entry->setActive($active);
            $this->_em->persist($this->_entry);
            $this->_em->flush();
        }
    }
    
    /**
     * @return int
     */
    public function getTotal() 
    {
        return $this->total;
    }

    /**
     * @param int $total
     */ 
    public function setTotal($total) 
    {
        $this->total = $total;
    }
    
}
