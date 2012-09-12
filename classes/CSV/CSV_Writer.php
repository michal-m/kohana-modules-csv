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
                // Escape enclosure
                if (strpos($cell, $enclosure) !== FALSE)
                {
                    $cell = str_replace($enclosure, $enclosure . $enclosure, $cell);
                    $enclose_cell = TRUE;
                }

                // Enclose cell
                if ($enclose_cell OR strpos($cell, $delimiter) !== FALSE OR preg_match("/(^\s|\s$|\n|\r)/", $cell))
                {
                    $cell = $enclosure . $cell . $enclosure;
                }

                $output .= $cell . $delimiter;
            }

            $output .= "\n";
        }

        return $output;
    }
}
