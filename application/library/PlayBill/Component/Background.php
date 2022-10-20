<?php


namespace PlayBill\Component;



use PlayBill\Component\ComponentInterface;

class Background extends AbstractComponent implements ComponentInterface
{

    public function run()
    {
        $fill = $this->options->fill;
        $src = $this->options->src;
//         TODO: Implement run() method.
        var_dump($this);
exit();

    }
}
