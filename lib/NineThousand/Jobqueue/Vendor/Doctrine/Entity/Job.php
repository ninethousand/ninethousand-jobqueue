<?php

namespace NineThousand\Jobqueue\Vendor\Doctrine\Entity;

/**
 * Job Entity for use with DoctrineJobAdapter in Jobqueue.
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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Job
 *
 * @Table(name="jobqueue_job")
 * @Entity
 */
class Job
{

    public function __construct()
    {
        $this->args = new ArrayCollection();
        $this->params = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->history = new ArrayCollection();
    }


    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }
        
        /**
         * @param int $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }
    
    /**
     * @Column(nullable="true", type="string")
     */
    protected $name;
    
        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }
        
        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }


    /**
     * @Column(type="integer")
     */
    protected $retry = 0;
    
        /**
         * @return int
         */
        public function getRetry()
        {
            return $this->retry;
        }
        
        /**
         * @param int $retry
         */
        public function setRetry($retry)
        {
            $this->retry = $retry;
        }
        
    /**
     * @Column(type="integer")
     */
    protected $cooldown = 0;
    
        /**
         * @return int
         */
        public function getCooldown()
        {
            return $this->cooldown;
        }
        
        /**
         * @param int $cooldown
         */
        public function setCooldown($cooldown)
        {
            $this->cooldown = $cooldown;
        }


    /**
     * @Column(name="max_retries", type="integer")
     */
    protected $maxRetries = 0;
    
        /**
         * @return int
         */
        public function getMaxRetries()
        {
            return $this->maxRetries;
        }
        
        /**
         * @param int $maxRetries
         */
        public function setMaxRetries($maxRetries)
        {
            $this->maxRetries = $maxRetries;
        }


    /**
     * @Column(type="integer")
     */
    protected $attempts = 0;

        /**
         * @return int
         */
        public function getAttempts()
        {
            return $this->attempts;
        }
        
        /**
         * @param int $attempts
         */
        public function setAttempts($attempts)
        {
            $this->attempts = $attempts;
        }


    /**
     * @Column(nullable="true", type="text")
     */
    protected $executable;
    
        /**
         * @return string
         */
        public function getExecutable() 
        {
            return $this->executable;
        }
        
        /**
         * @param string $executable
         */
        public function setExecutable($executable)
        {
            $this->executable = $executable;
        }


    /**
     * @Column(nullable="true", type="string")
     */
    protected $type;

        /**
         * @return string
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * @param string $type
         */
        public function setType($type)
        {
            $this->type = $type;
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
     * @Column(name="create_date", type="datetime")
     */
    protected $createDate;

        /**
         * @return \DateTime
         */   
        public function getCreateDate()
        {
            return $this->createDate;
        }

        /**
         * @param \DateTime $date
         */   
        public function setCreateDate(\DateTime $date)
        {
            $this->createDate = $date;
        }


    /**
     * @Column(name="last_run", nullable="true", type="datetime")
     */
    protected $lastrunDate;

        /**
         * @return \DateTime
         */   
        public function getLastrunDate()
        {
            return $this->lastrunDate;
        }

        /**
         * @param NULL | \DateTime $date
         */  
        public function setLastrunDate($date)
        {
            $this->lastrunDate = $date;
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


    /**
     * @Column(nullable="true", type="string")
     */
    protected $schedule;

        /**
         * @return string
         */   
        public function getSchedule()
        {
            return $this->schedule;
        }

        /**
         * @param string $schedule
         */   
        public function setSchedule($schedule)
        {
            $this->schedule = $schedule;
        }
        
    /**
     * @Column(type="integer")
     */
    protected $parent = 0;

        /**
         * @return int
         */   
        public function getParent()
        {
            return $this->parent;
        }

        /**
         * @param int $parent
         */   
        public function setParent($parent)
        {
            $this->parent = $parent;
        }


    /**
     * @ManyToMany(targetEntity="Param")
     * @JoinTable(name="jobqueue_job_param",
     *      joinColumns={@JoinColumn(name="job_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="param_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $params;

        /**
         * @return Doctrine\Common\Collections\ArrayCollection
         */ 
        public function getParams()
        {
            return $this->params;
        }

        /**
         * @param Doctrine\Common\Collections\ArrayCollection $params
         */   
        public function setParams($params)
        {
            $this->params = $params;
        }


    /**
     * @ManyToMany(targetEntity="Arg")
     * @JoinTable(name="jobqueue_job_arg",
     *      joinColumns={@JoinColumn(name="job_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="arg_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $args;
    
        /**
         * @return Doctrine\Common\Collections\ArrayCollection
         */ 
        public function getArgs()
        {
            return $this->args;
        }

        /**
         * @param Doctrine\Common\Collections\ArrayCollection $args
         */   
        public function setArgs($args)
        {
            $this->args = $args;
        }

    /**
     * @ManyToMany(targetEntity="Tag")
     * @JoinTable(name="jobqueue_job_tag",
     *      joinColumns={@JoinColumn(name="job_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="tag_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $tags;

        /**
         * @return Doctrine\Common\Collections\ArrayCollection
         */ 
        public function getTags()
        {
            return $this->tags;
        }

        /**
         * @param Doctrine\Common\Collections\ArrayCollection $tags
         */   
        public function setTags($tags)
        {
            $this->tags = $tags;
        }

    /**
     * @OneToMany(targetEntity="History", mappedBy="job")
     */
    protected $history;

        /**
         * @return Doctrine\Common\Collections\ArrayCollection
         */ 
        public function getHistory()
        {
            return $this->history;
        }

        /**
         * @param Doctrine\Common\Collections\ArrayCollection $history
         */   
        public function setHistory($history)
        {
            $this->history = $history;
        }

}
