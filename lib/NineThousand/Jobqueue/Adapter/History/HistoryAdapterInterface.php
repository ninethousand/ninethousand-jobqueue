<?php

namespace NineThousand\Jobqueue\Adapter\History;

/**
 * HistoryAdapterInterface defines the required functions for History Adapter models in Jobqueue.
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

use NineThousand\Jobqueue\Adapter\AdapterInterface;

interface HistoryAdapterInterface extends AdapterInterface
{   
    public function getHistory($limit, $offset);
    public static function factory($controller, $em, $entity);
    public function getId();
    public function getJobId();
    public function getJobName();
    public function getJobExecutable();
    public function getJobType();
    public function getJobCreateDate();
    public function getJobActive();
    public function getjobSchedule();
    public function getJobParent();
    public function getTimestamp();
    public function getStatus();
    public function setStatus($status);
    public function getMessage();
    public function setMessage($message);
    public function getSeverity();
    public function setSeverity($severity);
    public function getActive();
    public function setActive($active);
}
