<?php

namespace NineThousand\Jobqueue;

/**
 * History is an abstract class with which to create more specific history logs in Jobqueue.
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
 
abstract class History
{

    /**
     * @var array list of entries in this history
     */
    protected $entries;
    
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
        $this->entries = $this->getAll();
    }

    /**
     * @return NineThousand\Jobqueue\Adapter\History\HistoryAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
    
    /**
     * @param NineThousand\Jobqueue\Adapter\History\HistoryAdapterInterface $adapter
     */
    public function setAdapter(HistoryAdapterInterface $adapter) 
    {
        $this->adapter = $adapter;
    }
    
    /**
     * Compares the first array element to a second array element.
     * Returns positive if the first has a less value than the second else returns negative.
     *
     * @param $a
     * @param $b
     * @return bool
     */
    protected function cmp($a, $b)
    {
        if ($this->sortby === null) return 0;
        $method = 'get'.ucwords(strtolower($this->sortby));
        if ($a->{$method}() == $b->{$method}()) return 0;
        return ((method_exists($a, $method) && method_exists($b, $method)) && ($a->{$method}() < $b->{$method}())) ? 1 : -1;
    }
    
    /**
     * Sorts the history list according to the standing sort order.
     *
     * @param bool $reverse optional flags the sort for reverse order default is false
     */
    public function sort($reverse = false)
    {
        usort($this->entries, "self::cmp");
    }
    
    /**
     * Sorts the history list by an arbitrary column.
     *
     * @param string $column the column of the history for which to sort by
     * @param bool $reverse optional flags the sort for reverse order default is false
     */
    public function sortBy($column, $reverse = false) {
        $this->sortby = $column;
        $this->sort($reverse);
        $this->rewind();
    }
    
    /**
     * Checks whether a entry property meets the filter criteria -- 
     * retrns true if it does else false.
     *
     * @param $entry
     * @param mixed $value
     * @return bool
     */
    protected function chk($entry, $value)
    {
        if ($this->filterby === null) return true;
        $method = 'get'.ucwords(strtolower($this->filterby));
        return (method_exists($entry, $method) && $entry->{$method}() == $value) ? true : false;
    }
    
    /**
     * Filter the history list.
     *
     * @param bool $exclude optional filter entries that match standing criteria defaults is false
     */
    public function filter($exclude = false)
    {
        $list = array_filter($this->entries, "self::chk");
        if ($exclude) {
            $list = array_diff($this->entries, $list);
        }
        $this->entries = $list;
    }
    
    /**
     * Filters the history list by an arbitrary column.
     *
     * @param string $column the column of the queue for which to filter by
     * @param bool $exclude optional filter entries that match standing criteria defaults is false
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
        reset($this->entries);
    }

    /**
     * Advances the queue to the next entry and returns it.
     * 
     * @return array
     */
    public function next()
    {
        return next($this->entries);
    }
    
    /**
     * Returns the array key of the current entry.
     * 
     * @return mixed
     */
    function key() 
    {
        return key($this->entries);
    }
    
    /**
     * Returns an indication of if the current item is valid
     * 
     * @return bool
     */
    function valid() {
        return key($this->entries) !== null;
    }
    
    /**
     * Reverts the queue to the previous entry and returns it.
     * 
     * @return array
     */
    public function prev()
    {
        return prev($this->entries);
    }
    
    /**
     * Returns the current entry.
     * 
     * @return array
     */
    public function current()
    {
        return current($this->entries);
    }
    
    /**
     * Re-initializes the queue with a rereshed list of entries.
     * Reverts the pointer to the beginning of the entry list.
     */
    public function refresh()
    {
        $this->rewind();
        unset($this->entries);
        $this->entries = $this->getAll();
        $this->sort();
    }
    
    /**
     * Provides a count of entries in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->entries);
    }
    
    /**
     * @Provides a count of every history item available notwithstanding how many in the collection
     *
     * @return int
     */
    public function getTotal() 
    {
        return $this->adapter->getTotal();
    }

    /**
     * @param int $total
     */ 
    public function setTotal($total) 
    {
        $this->adapter->setTotal($total);
    }
    
}
