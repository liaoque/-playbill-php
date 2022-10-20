<?php


namespace PlayBill\Component;


class AbstractComponent
{
    protected \stdClass $options;

    public function __construct($options)
    {

        $this->options = $options;
    }
}
