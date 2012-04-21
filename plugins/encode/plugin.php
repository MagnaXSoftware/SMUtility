<?php
/**
 * Encoder
 */

/**
 * Require plugin interfaces.
 */
require_once ROOT . 'plugins.php';

/**
 * Encodes a string.
 *
 * @package Plugin
 * @subpackage Encode
 */
class SMU_Encode extends SMU_Plugin {

    /**
     * Returns a multidimentional array used to build the configuration page.
     *
     * @see SMU_Configurable::form()
     * @return array
     */
    public function form() {
        return array(
            array(
                'label' => 'value',
                'name' => 'value',
                'type' => 'text'
            ),
            array(
                'label' => 'Encodings',
                'name' => 'type',
                'type' => 'radio',
                'value' => $this->_listEncs()
            )
        );
    }

    /**
     * Runs the plugin and returns a multidimentional array that contains the
     * result of the plugin's execution.
     *
     * @see SMU_Executable::execute()
     * @param array $form Configuration values
     * @throws Exception
     * @return array
     */
    public function execute(array &$form) {
        if ((!isset($form['type']) || empty($form['type'])) || (!isset($form['value']) || empty($form['value']))) {
            throw new Exception('No configuration option was sent to the plugin.');
        }
        return array(
            array(
                'label' => 'Encoded value',
                'type' => 'string',
                'value' => HTML::wrap('code', $this->_encode($form['value'], $form['type']))
            ),
            'options' => array(
                'Encoding algorythm' => strtoupper($form['type']),
                'clear text' => $form['value']
            )
        );
    }

    /**
     * Returns a list of the encoders (all files in algos subdirectory).
     *
     * @return array
     */
    private function _listEncs() {
        $return = array();
        foreach (scandir(ROOT . 'plugins' . DS . 'encode' . DS . 'algos') as $item) {
            if (preg_match('/(\w+)\.php/', $item, $matches)) {
                $return[] = array(
                    'label' => strtoupper($matches[1]),
                    'value' => $matches[1]
                );
            }
        }
        return $return;
    }

    /**
     * Encode the string.
     *
     * @param string $clear
     * @param string $type
     * @return string
     * @throws Exception
     */
    private function _encode($clear, $type) {
        $file = ROOT . 'plugins' . DS . 'encode' . DS . 'algos' . DS . $type . '.php';
        if (file_exists($file)) {
            require_once $file;
            $class = "EncodeAddIn_" . strtoupper($type);
            $obj = new $class;
            return $obj->encode($clear);
        }
        throw new Exception('Unknown encoding algorithm');
    }
}
