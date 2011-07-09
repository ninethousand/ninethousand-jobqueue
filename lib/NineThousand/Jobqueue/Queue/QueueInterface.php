<?php

namespace NineThousand\Jobqueue\Queue;

/**
 * QueueInterface defines the required functions for Queue models in Jobqueue.
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

use NineThousand\Jobqueue\Job\JobInterface;

interface QueueInterface extends \Iterator
{
    public function getAll();
    public static function factory($adapter);
    public function adoptJob(JobInterface $job);
    public function sort($reverse);
    public function sortBy($column, $reverse);
    public function filter($exclude);
    public function filterBy($column, $exclude);
    public function prev();
    public function refresh();
    public function totalJobs();
}
