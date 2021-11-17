<?php

class Stack
{
    public $stack;

    function __construct($stack)
    {
        $this->stack = $stack;
    }

    public function push($values = [])
    {
        foreach ($values as $value) {
            $this->stack[] = $value;
        }
    
        $count = count($this->stack);
    
        return $count;
    }

    public function pop()
    {
        $temp = [];
        $found = null;
        $end = count($this->stack) - 1;

        foreach ($this->stack as $key => $value) {
            if ($key !== $end) {
                $temp[] = $value;
            } else {
                $found = $this->stack[$end];
            }
        }

        $this->stack = $temp;

        return $found;
    }

    // public function reset()
    // {
    //     foreach ($this->stack as $key => &$value) {
    //     }
    // }

    public function peek()
    {
        $head = count($this->stack) - 1;

        return $this->stack[$head];
    }
}