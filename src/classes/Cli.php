<?php

namespace Spider;

class Cli
{
    /**
     * Clears the console
     */
    public function clear()
    {
        echo "\033[2J\033[0;0H";
    }

    /**
     * Prints a horizontal divider
     */
    public function hr()
    {
        echo str_repeat("-", 80) . "\n";
    }

    /**
     * Prints a new line in the console
     * @param string $str string to be printed
     */
    public function line($str)
    {
        echo "$str\n";
    }

    /**
     * Prints success message
     * @param string $str the message
     */
    public function success($str)
    {
        $this->line($this->green($str));
    }

    /**
     * Prints error message
     * @param string $s the message
     */
    public function error($s)
    {
        $this->line($this->red($s));
    }

    /**
     * Adds red text color to the string
     * @param string $str the string to be colored
     * @return string the colored string
     */
    public function red($str)
    {
        return "\033[0;31m$str\033[0m";
    }

    /**
     * Adds green text color to the string
     * @param string $str the string to be colored
     * @return string the colored string
     */
    public function green($str)
    {
        return "\033[0;32m$str\033[0m";
    }

    /**
     * Adds yellow text color to the string
     * @param string $str the string to be colored
     * @return string the colored string
     */
    public function yellow($str)
    {
        return "\033[0;33m$str\033[0m";
    }

    /**
     * Adds gray text color to the string
     * @param string $str the string to be colored
     * @return string the colored string
     */
    public function gray($str)
    {
        return "\033[0;37m$str\033[0m";
    }
}

