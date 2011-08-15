<?php

namespace NineThousand\Jobqueue\History;

/**
 * StandardHistory is the object model for the History of processed jobs
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

use NineThousand\Jobqueue\History;
use NineThousand\Jobqueue\History\HistoryInterface;
use NineThousand\Jobqueue\Adapter\History\HistoryAdapterInterface;
 
class StandardHistory extends History implements HistoryInterface
{
    /**
     * @var null|integer retains the query limit
     */
    private $_limit = null;
     
    /**
     * @var null|integer retains the query offset
     */
    private $_offset = null;
    
    /**
     * @var false|bool retains direction of the sort
     */
    private $_reverse = false;
    
    /**
     * @var null|int retains the job Id option
     */
    private $_jobId = null;
    
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
     * @param NineThousand\Jobqueue\Adapter\History\HistoryAdapterInterface $adapter
     */
    public function __construct(HistoryAdapterInterface $adapter, $limit = null, $offset = null, $reverse = false, $jobId = null)
    {
        $this->_limit = $limit;
        $this->_offset = $offset;
        $this->_reverse = $reverse;
        $this->_jobId = $jobId;
        $this->setAdapter($adapter);
        parent::__construct();
    }
    
    /**
     * Creates a static instance of StandardHistory.
     *
     * @param $adapter
     * @return NineThousand\Jobqueue\History\StandardHistory
     */
    public static function factory($adapter, $limit = null, $offset = null, $reverse = false, $jobId = null)
    {
        return new self($adapter, $limit, $offset, $reverse, $jobId);
    }
    
    /**
     * Fetches all jobs in this history.
     *
     * @return array
     */
    public function getAll() 
    {
        return $this->adapter->getHistory($this->_limit, $this->_offset, $this->_reverse, $this->_jobId);
    }
    
}
