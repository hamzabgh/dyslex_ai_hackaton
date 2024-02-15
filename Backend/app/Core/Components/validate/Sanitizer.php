<?php

namespace App\Components\Validate;

/**
 * Sanitizer
 * 
 * Helper class for sanitizing user input.
 */
class Sanitizer
{
    private $input, $output;

    /**
     * Create a new instance of Sanitizer object and capture input data from the request.
     */
    public function __construct($method = 'post')
    {
        switch (strtolower($method)) {
            case 'post':
                $this->input = request()->post();
                break;
            case 'get':
                $this->input = request()->query();
                break;
            case 'cookie':
                $this->input = request()->cookie();
                break;
        }
    }

    /**
     * Return the sanitized data as output
     */
    public function get()
    {
        if (isset($this->output)) {
            return $this->output;
        }
        return false;
    }

    /**
     * Return the sanitized data by key as output
     */
    public function getItem($key)
    {
        if (isset($this->output[$key][0])) {
            return $this->output[$key][0];
        }
        return false;
    }

    /**
     * Sanitize input to int
     */
    public function sanitize_int($integer, $min = '', $max = '')
    {
        $int = intval($integer);
        if ((($min != '') && ($int < $min)) || (($max != '') && ($int > $max))) {
            return false;
        }
        return $int;
    }

    /**
     * Sanitize input for float
     */
    public function sanitize_float($float)
    {
        return floatval($float);
    }

    /**
     * Strip string of all non-alphanumeric characters
     */
    public function sanitize_strict_string($string)
    {
        return preg_replace("/[^a-zA-Z0-9]/", "", $string);
    }

    /**
     * Sanitize a string for SQL input
     */
    public function sanitize_sql_string($string)
    {
        $pattern = ['/\\\\/', "/\"/", "/'/"];
        $replacement = ['\\\\\\', '\"', "\\'"];

        return preg_replace($pattern, $replacement, $string);
    }

    /**
     * Sanitize a string for HTML
     */
    public function sanitize_html_string($string)
    {
        $pattern = ['/&/', '/</', "/>/", '/\n/', '/"/', "/'/", "/%/", '/\(/', '/\)/', '/\+/', '/-/'];
        $replacement = ['&amp;', '&lt;', '&gt;', '<br>', '&quot;', '&#39;', '&#37;', '&#40;', '&#41;', '&#43;', '&#45;'];

        return preg_replace($pattern, $replacement, $string);
    }

    /**
     * Filter max value or max length of data
     */
    public function max_filter($data, $type, $max = '')
    {
        if ($type == 'int' || $type == 'float') {
            if ($max != '' && $max < $data) {
                return false;
            }
            return $data;
        } else {
            $len = strlen($data);
            if ($max != '' && $max < $len) {
                return false;
            }
            return $data;
        }
    }

    /**
     * Filter min value or min length of data
     */
    public function min_filter($data, $type, $min = '')
    {
        if ($type == 'int' || $type == 'float') {
            if ($min != '' && $min > $data) {
                return false;
            }
            return $data;
        } else {
            $len = strlen($data);
            if ($min != '' && $min > $len) {
                return false;
            }
            return $data;
        }
    }
}
