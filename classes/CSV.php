<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The CSV class
 *
 * @package     CSV
 * @author      Michał Musiał
 * @copyright   (c) 2012 Michał Musiał
 */
class CSV implements ArrayAccess
{
    /**
     * @var string
     */
    protected $_delimiter;

    /**
     * @var string
     */
    protected $_enclosure;

    /**
     * @var string
     */
    protected $_escape;

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @var bool
     */
    protected $_loaded = FALSE;

    /**
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        $this->_delimiter = $delimiter;
        $this->_enclosure = $enclosure;
        $this->_escape = $escape;

        // @TODO: anything else?
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->output();
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return (isset($this->_data[$offset])) ? $this->_data[$offset] : NULL;
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === NULL)
        {
            $this->_data = $value;
        }
        else
        {
            $this->_data[$offset] = (is_scalar($value)) ? (string) $value : $value;
        }
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    /**
     * Returns data in original format or sets it from parameter. If an array is
     * more than 2-dimentional 3rd dimention is converted to a string
     *
     * @param array $data
     * @return type
     */
    public function data($data = NULL)
    {
        if ($data === NULL)
        {
            return $this->_data;
        }

        if ($this->_loaded)
        {
            // @TODO: error
        }

        if (is_array($data))
        {
            foreach ($data as $row_id => $row)
            {
                if (is_array($row))
                {
                    foreach ($row as $col_id => $value)
                    {
                        if ( ! is_scalar($value))
                        {
                            $data[$row_id][$col_id] = (string) $value;
                        }
                    }
                }
                else
                {
                    // @TODO: error
                }
            }

            $this->_data = $data;
            $this->_loaded = TRUE;
        }
        else
        {
            // @TODO: error
        }
    }

    /**
     * Loads data from a string
     *
     * @param string $data
     * @return bool @TODO
     */
    public function load($data)
    {
        // @TODO: data parser

        $this->_loaded = TRUE;
        return TRUE;
    }

    /**
     * Loads data from a file
     *
     * @param string $filename
     * @return bool @TODO
     */
    public function load_file($filename)
    {
        if (is_file($filename) AND is_readable($filename))
        {
            return $this->load(file_get_contents($filename));
        }
        else
        {
            // @TODO: error
        }
    }

    /**
     * Outputs data in a CSV format.
     *
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @return string Returns a CSV formatted string.
     */
    public function output($delimiter = NULL, $enclosure = NULL, $escape = NULL)
    {
        $delimiter = ($delimiter === NULL) ? $this->_delimiter : $delimiter;
        $enclosure = ($enclosure === NULL) ? $this->_enclosure : $enclosure;
        $escape = ($escape === NULL) ? $this->_escape : $escape;

        $output = '';

        // @TODO: prepare data for output

        return $output;
    }

    /**
     * Saves (and generates if necessary) output to a file.
     *
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function to_file($filename, $delimiter = NULL, $enclosure = NULL, $escape = NULL)
    {
        if (is_writable($filename))
        {
            $success = file_put_contents($filename, $this->output($delimiter, $enclosure, $escape));

            if ( ! $success)
            {
                // @TODO: error
            }
        }
        else
        {
            // @TODO: error
        }
    }
}
