<?php

/*
 * This file is part of the DataGridBundle.
 *
 * (c) Abhoryo <abhoryo@free.fr>
 * (c) Stanislav Turza
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace APY\DataGridBundle\Grid\Column;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;

class DateTimeColumn extends Column
{
    protected $dateFormat = \IntlDateFormatter::MEDIUM;

    protected $timeFormat = \IntlDateFormatter::MEDIUM;

    protected $format;

    protected $fallbackFormat = 'Y-m-d H:i:s';

    protected $inputFormat;

    protected $fallbackInputFormat = 'Y-m-d H:i:s';

    protected $timezone;

    public function __initialize(array $params): void
    {
        parent::__initialize($params);

        $this->setFormat($this->getParam('format'));
        $this->setInputFormat($this->getParam('inputFormat', $this->fallbackInputFormat));
        $this->setOperators($this->getParam('operators', [
            self::OPERATOR_EQ,
            self::OPERATOR_NEQ,
            self::OPERATOR_LT,
            self::OPERATOR_LTE,
            self::OPERATOR_GT,
            self::OPERATOR_GTE,
            self::OPERATOR_BTW,
            self::OPERATOR_BTWE,
            self::OPERATOR_ISNULL,
            self::OPERATOR_ISNOTNULL,
        ]));
        $this->setDefaultOperator($this->getParam('defaultOperator', self::OPERATOR_EQ));
        $this->setTimezone($this->getParam('timezone', date_default_timezone_get()));
    }

    public function isQueryValid($query)
    {
        $result = array_filter((array) $query, [$this, 'isDateTime']);

        return !empty($result);
    }

    protected function isDateTime($query)
    {
        return false !== \DateTime::createFromFormat($this->inputFormat, $query);
    }

    public function getFilters($source)
    {
        $parentFilters = parent::getFilters($source);

        $filters = [];
        foreach ($parentFilters as $filter) {
            $filters[] = ($filter->getValue() === null) ? $filter : $filter->setValue(\DateTime::createFromFormat($this->inputFormat, $filter->getValue()));
        }

        return $filters;
    }

    public function renderCell($value, $row, $router)
    {
        $value = $this->getDisplayedValue($value);

        if (is_callable($this->callback)) {
            $value = call_user_func($this->callback, $value, $row, $router);
        }

        return $value;
    }

    public function getDisplayedValue($value)
    {
        if (!empty($value)) {
            $dateTime = $this->getDatetime($value, new \DateTimeZone($this->getTimezone()));

            if (isset($this->format)) {
                $value = $dateTime->format($this->format);
            } else {
                try {
                    $transformer = new DateTimeToLocalizedStringTransformer(null, $this->getTimezone(), $this->dateFormat, $this->timeFormat);
                    $value = $transformer->transform($dateTime);
                } catch (\Exception $e) {
                    $value = $dateTime->format($this->fallbackFormat);
                }
            }

            if (array_key_exists((string) $value, $this->values)) {
                $value = $this->values[$value];
            }

            return $value;
        }

        return '';
    }

    /**
     * DateTimeHelper::getDatetime() from SonataIntlBundle.
     *
     * @param \Datetime|\DateTimeImmutable|string|int $data
     * @param \DateTimeZone timezone
     *
     * @return \Datetime
     */
    protected function getDatetime($data, \DateTimeZone $timezone)
    {
        if ($data instanceof \DateTime || $data instanceof \DateTimeImmutable) {
            return $data->setTimezone($timezone);
        }

        // the format method accept array or integer
        if (is_numeric($data)) {
            $data = (int) $data;
        }

        if (is_string($data)) {
            $data = strtotime($data);
        }

        // MongoDB Date and Timestamp
        if ($data instanceof \MongoDate || $data instanceof \MongoTimestamp) {
            $data = $data->sec;
        }

        // Mongodb bug ? timestamp value is on the key 'i' instead of the key 't'
        if (is_array($data) && array_keys($data) == ['t', 'i']) {
            $data = $data['i'];
        }

        $date = new \DateTime();
        $date->setTimestamp($data);
        $date->setTimezone($timezone);

        return $date;
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setInputFormat($inputFormat)
    {
        $this->inputFormat = $inputFormat;

        return $this;
    }

    public function getInputFormat()
    {
        return $this->inputFormat;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getType()
    {
        return 'datetime';
    }
}
