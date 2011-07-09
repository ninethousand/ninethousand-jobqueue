<?php

namespace NineThousand\Jobqueue\Entity;

/**
 * Tag Entity for use with DoctrineJobAdapter in Jobqueue.
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
 * Tag
 *
 * @Table(name="jobqueue_tag")
 * @Entity
 */
class Tag
{

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
     * @Column(type="integer")
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
     * @Column(type="string")
     */
    protected $value;
    
        /**
         * @return string
         */
        public function getValue()
        {
            return $this->value;
        }
        
        /**
         * @param string $value
         */
        public function setValue($value)
        {
            $this->value = $value;
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
