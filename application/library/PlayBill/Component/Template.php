<?php


namespace PlayBill\Component;


trait Template
{
    public string $template = '';

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }


    public function render($data){
        return strtr($this->getTemplate(), $data);
    }
}
