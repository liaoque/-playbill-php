<?php


class JsonView implements Yaf_View_Interface
{

    /**
     * @var array
     */
    protected array $_options = [];

    /**
     * @param string $tpl
     * @param array $tpl_vars
     * @return false|string
     */
    public function render($tpl, $tpl_vars = null)
    {
        $data = $this->_options;
        if ($tpl_vars && is_array($tpl_vars)) {
            $data = array_merge($data, $tpl_vars);
        }
        return json_encode($data);
    }

    function assign($name, $value = null)
    {
        // TODO: Implement assign() method.
        if (is_array($name)) {
            $this->_options = array_merge($this->_options, $name);
        } else {
            $this->_options[$name] = $value;
        }
    }

    /**
     * @param string $tpl
     * @param null $tpl_vars
     * @return bool|void
     */
    function display($tpl, $tpl_vars = null)
    {
        // TODO: Implement display() method.
    }

    function getScriptPath()
    {
        // TODO: Implement getScriptPath() method.
    }

    function setScriptPath($template_dir)
    {
        // TODO: Implement setScriptPath() method.
    }
}
