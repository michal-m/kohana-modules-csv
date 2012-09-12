<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The CSV_Writer class
 *
 * @package     CSV
 * @author      Michał Musiał
 * @copyright   (c) 2012 Michał Musiał
 */
class CSV_Writer
{
    /**
     * Converts an array to a formatted CSV string
     *
     * @param array $data
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @return string Returns a formatted CSV string
     */
    public static function write(array $data, $delimiter = ",", $enclosure ="\"", $escape = "\\")
    {
        $output = '';

        foreach ($data as $row)
        {
            foreach ($row as $column => $cell)
            {
                
            }

            $output .= "\n";
        }

        return $output;
    }
}
