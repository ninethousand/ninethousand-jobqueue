<?php

namespace NineThousand\Jobqueue\Util;

/**
 * Cron schedule parser.
 *
 * https://github.com/havvg/CronParser
 *
 * This CronParser is based on the work of "Michael" mentioned below.
 *
 * @link http://stackoverflow.com/questions/321494/calculate-when-a-cron-job-will-be-executed-then-next-time/3453872#3453872
 * @link http://en.wikipedia.org/wiki/Cron
 */

use \DateTime;
use \DateInterval;

class CronParser
{
  /**
   * @var array Cron parts
   */
  protected $cronParts;

  /**
   * Constructor.
   *
   * @uses CronParser::setSchedule()
   */
  public function __construct($schedule)
  {
    $this->setSchedule($schedule);
  }

  /**
   * Set the schedule of the cron to parse.
   *
   * @throws InvalidArgumentException If the given $schedule is not a valid cron schedule.
   *
   * @param string $schedule A valid crontab entry.
   *
   * @return CronParser $this
   */
  public function setSchedule($schedule)
  {
    $this->cronParts = explode(' ', $schedule);

    if (count($this->cronParts) != 5)
    {
      throw new \InvalidArgumentException(sprintf('The given schedule "%s" is invalid.', $schedule));
    }

    return $this;
  }

  /**
   * Check if a date/time unit value satisfies a crontab unit.
   *
   * @param DateTime $date The date to check against.
   * @param string $part The PHP date() part of a date formatted string.
   *
   * @return bool
   */
  public function unitSatisfiesCron(DateTime $date, $part)
  {
    $schedule = $this->getSchedule($part);

    if ($schedule == '*')
    {
      return true;
    }

    $unitValue = (int) $date->format($part);

    if (strpos($schedule, '-'))
    {
      list($first, $last) = explode('-', $schedule);

      return (($unitValue >= $first) and ($unitValue <= $last));
    }
    else if (strpos($schedule, '*/') !== false)
    {
      list($delimiter, $interval) = explode('*/', $schedule);

      return ($unitValue % (int) $interval == 0);
    }
    else
    {
      return ($unitValue == (int) $schedule);
    }
  }

  /**
   * Returns the date the cron is scheduled for the next run.
   *
   * @param DateTime $date The date since when the next run date shall be calculated.
   * @param DateTime $currentTime A modified DateTime to refer to as "current" time.
   *
   * @return DateTime
   */
  public function getNextScheduledDate(DateTime $date, DateTime $currentTime)
  {
    $date = clone $date;
    while (($date = $this->getNextRunDate($date->add(new DateInterval('PT1M')))) < $currentTime);

    return $date;
  }

  /**
   * Returns first date after the given start date the cron is scheduled.
   *
   * @param DateTime $lastRun The date since when the next run date shall be calculated.
   *
   * @return DateTime
   */
  protected function getNextRunDate(DateTime $lastRun)
  {
    $nextRun = clone $lastRun;
    $nextRun->setTime($nextRun->format('H'), $nextRun->format('i'), 0);

    $i = 0;
    // Set a hard limit to bail on an impossible date
    while (++$i < 100000)
    {
      // Adjust the month until it matches.  Reset day to 1 and reset time.
      if (!$this->unitSatisfiesCron($nextRun, 'm'))
      {
        $nextRun->add(new DateInterval('P1M'));
        $nextRun->setDate($nextRun->format('Y'), $nextRun->format('m'), 1);
        $nextRun->setTime(0, 0, 0);

        continue;
      }

      // Adjust the day of the month by incrementing the day until it matches. Reset time.
      if (!$this->unitSatisfiesCron($nextRun, 'd'))
      {
        $nextRun->add(new DateInterval('P1D'));
        $nextRun->setTime(0, 0, 0);

        continue;
      }

      // Adjust the day of week by incrementing the day until it matches.  Resest time.
      if (!$this->unitSatisfiesCron($nextRun, 'N'))
      {
        $nextRun->add(new DateInterval('P1D'));
        $nextRun->setTime(0, 0, 0);

        continue;
      }

      // Adjust the hour until it matches the set hour.  Set seconds and minutes to 0
      if (!$this->unitSatisfiesCron($nextRun, 'H'))
      {
        $nextRun->add(new DateInterval('PT1H'));
        $nextRun->setTime($nextRun->format('H'), 0, 0);

        continue;
      }

      // Adjust the minutes until it matches a set minute
      if (!$this->unitSatisfiesCron($nextRun, 'i'))
      {
        $nextRun->add(new DateInterval('PT1M'));

        continue;
      }

      break;
    }

    return $nextRun;
  }

  /**
   * Returns the cron schedule or a specified part of it.
   *
   * @link http://docs.php.net/date
   *
   * @param string $part The PHP date() part of a date formatted string. If null the complete schedule string will be returned.
   *
   * @return string
   */
  public function getSchedule($part = null)
  {
    switch ($part)
    {
      case 'i':
        return $this->cronParts[0];

      case 'H':
        return $this->cronParts[1];

      case 'd':
        return $this->cronParts[2];

      case 'm':
        return $this->cronParts[3];

      case 'N':
        return $this->cronParts[4];

      default:
        return implode(' ', $this->cronParts);
    }
  }

  /**
   * Deterime if the cron is due to run based on the current time and last run time.
   *
   * @param DateTime $lastRun The DateTime the cron was last run. Defaults to current DateTime.
   * @param DateTime $currentTime The DateTime to use as the current time.
   *
   * @return bool
   */
  public function isDue(DateTime $lastRun = null, DateTime $currentTime = null)
  {
    if (is_null($lastRun))
    {
      $lastRun = new DateTime();
    }

    if (is_null($currentTime))
    {
      $currentTime = new DateTime();
    }

    // At the same time, a cron never runs twice.
    if ($lastRun == $currentTime)
    {
      return false;
    }

    $nextRun = $this->getNextRunDate($lastRun);
    $tRun = clone $lastRun;
    // We did not actually get the next run.
    if ($nextRun == $tRun->setTime($tRun->format('H'), $tRun->format('i'), 0))
    {
      $nextRun = $this->getNextRunDate($tRun->add(new DateInterval('PT1M')));
    }

    $scheduledRun = $this->getNextScheduledDate($lastRun, $currentTime);

    if ($nextRun < $scheduledRun)
    {
      if ($nextRun == $lastRun->setTime($lastRun->format('H'), $lastRun->format('i'), 0))
      {
        $checkDate = &$scheduledRun;
      }
      else
      {
        $checkDate = &$nextRun;
      }
    }
    else
    {
      $checkDate = &$scheduledRun;
    }

    return ($checkDate <= $currentTime);
  }
}
