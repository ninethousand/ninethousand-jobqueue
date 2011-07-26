<?php

namespace NineThousand\Jobqueue\History;

/**
 * HistoryInterface defines the required functions for History models in Jobqueue.
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

interface HistoryInterface extends \Iterator
{
    public function getAll();
    public static function factory($adapter);
    public function sort($reverse);
    public function sortBy($column, $reverse);
    public function filter($exclude);
    public function filterBy($column, $exclude);
    public function prev();
    public function refresh();
    public function count();
    public function getTotal();
    public function setTotal($total);
}
