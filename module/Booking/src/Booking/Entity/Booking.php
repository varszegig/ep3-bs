<?php

namespace Booking\Entity;

use Base\Entity\AbstractEntity;

class Booking extends AbstractEntity
{

    protected $bid;
    protected $uid;
    protected $sid;
    protected $status;
    protected $status_billing;
    protected $visibility;
    protected $quantity;
    protected $created;

    /**
     * The possible status options.
     *
     * @var array
     */
    public static $statusOptions = array(
        'single' => 'Single',
        'subscription' => 'Subscription',
        'cancelled' => 'Cancelled',
    );

    /**
     * Returns the status string.
     *
     * @return string
     */
    public function getStatus()
    {
        $status = $this->need('status');

        if (isset(self::$statusOptions[$status])) {
            return self::$statusOptions[$status];
        } else {
            return 'Unknown';
        }
    }

    /**
     * Returns the billing status string.
     *
     * @return string
     */
    public function getBillingStatus()
    {
        return $this->need('status_billing');
    }

    /**
     * The possible visibility options.
     *
     * @var array
     */
    public static $visibilityOptions = array(
        'public' => 'Public',
        'private' => 'Private',
    );

    /**
     * Returns the visibility string.
     *
     * @return string
     */
    public function getVisibility()
    {
        $visibility = $this->need('visibility');

        if (isset(self::$visibilityOptions[$visibility])) {
            return self::$visibilityOptions[$visibility];
        } else {
            return 'Unknown';
        }
    }

    /**
     * The possible repeat options.
     */
    public static $repeatOptions = array(
        '0' => 'Only once',
        '1' => 'Daily',
        '2' => 'Every 2 days',
        '3' => 'Every 3 days',
        '4' => 'Every 4 days',
        '5' => 'Every 5 days',
        '6' => 'Every 6 days',
        '7' => 'Weekly',
        '14' => 'Every 2 weeks',
        '28' => 'Monthly',
    );

    public static $paymentOptions = array(
        '0' => 'Once',
        '1' => 'Regularly',
    );

    /**
     * Returns the repeat string.
     *
     * @return string
     */
    public function getRepeat()
    {
        $repeat = $this->getMeta('repeat');

        if (! $repeat) {
            return null;
        }

        if (isset(self::$repeatOptions[$repeat])) {
            return self::$repeatOptions[$repeat];
        } else {
            return sprintf('Every %s days', $repeat);
        }
    }

}
