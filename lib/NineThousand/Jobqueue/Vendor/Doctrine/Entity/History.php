<?php

namespace NineThousand\Jobqueue\Vendor\Doctrine\Entity;

/**
 * Arg Entity for use with DoctrineJobAdapter in Jobqueue.
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

/**
 * History
 *
 * @Table(name="jobqueue_history")
 * @Entity
 */
class History
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
        /*
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }
        
        /*
         * @param int $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }


    /**
     * @ManyToOne(targetEntity="Job")
     * @JoinColumn(name="job", referencedColumnName="id")
     */
    protected $job;
    
        /**
         * @return int
         */
        public function getJob()
        {
            return $this->job;
        }
        
        /**
         * @param int $job
         */
        public function setJob($job)
        {
            $this->job = $job;
        }
        


    /**
     * @Column(nullable="true", type="date")
     */
    protected $timestamp;
        
        /**
         * @return \DateTime
         */
        public function getTimestamp()
        {
            return $this->timestamp;
        }
        
        /**
         * @param \DateTime $timestamp
         */
        public function setTimestamp(\DateTime $timestamp)
        {
            $this->timestamp = $timestamp;
        }

        
    /**
     * @Column(nullable="true", type="string")
     */
    protected $status;
        
        /**
         * @return string
         */         
        public function getStatus()
        {
            return $this->status;
        }
        
        /**
         * @param string $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }
        
    
    /**
     * @Column(nullable="true", type="text", nullable="true")
     */
    protected $message;
        
        /**
         * @return string
         */
        public function getMessage()
        {
            return $this->message;
        }
        
        /**
         * @param string $message
         */
        public function setMessage($message)
        {
            $this->message = $message;
        }
        
    
    /**
     * @Column(nullable="true", type="string")
     */
    protected $severity;
        
        /**
         * @return string
         */
        public function getSeverity()
        {
            return $this->severity;
        }
        
        /**
         * @param string $severity
         */
        public function setSeverity($severity)
        {
            $this->severity = $severity;
        }


    /**
     * @Column(type="integer")
     */
    protected $active;
        
        /**
         * @return int
         */
        public function getActive()
        {
            return $this->active;
        }
        
        /**
         * @param int $active
         */
        public function setActive($active)
        {
            $this->active = $active;
        }


}
