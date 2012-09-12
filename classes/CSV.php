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
    protected $_changed = TRUE;

    /**
     * @var bool
     */
    protected $_loaded = FALSE;

    /**
     * @var string
     */
    protected $_output_cache = '';

    /**
     * @var string
     */
    protected $_output_delimiter;

    /**
     * @var string
     */
    protected $_output_enclosure;

    /**
     * @var string
     */
    protected $_output_escape;

    /**
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        if (count(array_unique(func_get_args())) !== 3)
        {
            // @TODO: error
        }

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

        $this->_changed = TRUE;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
        $this->_changed = TRUE;
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
            $this->_changed = TRUE;
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

        $this->_changed = TRUE;
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

        $format_args = array($delimiter, $enclosure, $escape);

        if (count(array_unique($format_args)) !== 3)
        {
            // @TODO: error
        }

        if ($this->_changed OR $delimiter !== $this->_output_delimiter OR $enclosure !== $this->_output_enclosure OR $escape !== $this->_output_escape)
        {
            $this->_output_delimiter = $delimiter;
            $this->_output_enclosure = $enclosure;
            $this->_output_escape = $escape;

            $output = CSV_Writer::write($this->_data, $delimiter, $enclosure, $escape);

            // @TODO: error reporting when writing

            $this->_output_cache = $output;
            $this->_changed = FALSE;
        }

        return $this->_output_cache;
    }

    /**
     * Saves (and generates if necessary) output to a file.
     *
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @param bool $bom Whether to include UTF-8 Byte Order Mark in the file
     */
    public function to_file($filename, $delimiter = NULL, $enclosure = NULL, $escape = NULL, $bom = TRUE)
    {
        $bom = ($bom) ? "\xEF\xBB\xBF" : '';

        if (is_writable($filename))
        {
            $success = file_put_contents($filename, $bom . $this->output($delimiter, $enclosure, $escape));

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
