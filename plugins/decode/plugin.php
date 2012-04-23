<?php
/**
 * Decoder
 */

/**
 * Require plugin interfaces.
 */
require_once ROOT . 'plugins.php';
require_once ROOT . 'plugins' . DS . 'common' . DS . 'algos.php';

/**
 * Decodes a string.
 *
 * @package Plugin
 * @subpackage Decode
 */
class SMU_Decode extends SMU_Plugin {
    /**
     * Directory where algorithms are kept
     * @var string
     */
    private $algosDir = '';

    /**
     * Constructor
     */
    public function __construct() {
        $this->algosDir = ROOT . 'plugins' . DS . 'common' . DS . 'algos';
    }

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
                'value' => $this->_listDecs()
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
                'label' => 'Decoded value',
                'type' => 'string',
                'value' => $this->_decode($form['value'], $form['type'])
            ),
            'options' => array(
                'Encoding algorythm' => strtoupper($form['type']),
                'Encoded value' => $form['value']
            )
        );
    }

    /**
     * Returns a list of the decoders (all files in algos subdirectory).
     *
     * @return array
     */
    private function _listDecs() {
        $return = array();
        foreach (scandir($this->algosDir) as $item) {
            if (preg_match('/(\w+)\.php/', $item, $matches)) {
                include_once $this->algosDir . DS . $item;
                $class = "Algo_" . strtoupper($matches[1]);
                $obj = new $class;
                if ($obj instanceof Enc_Decodable) {
                    $return[] = array(
                        'label' => strtoupper($matches[1]),
                        'value' => $matches[1]
                    );
                }
                unset($obj);
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
    private function _decode($clear, $type) {
        $file = ROOT . 'plugins' . DS . 'decode' . DS . 'algos' . DS . $type . '.php';
        if (file_exists($file)) {
            require_once $file;
            $class = "Algo_" . strtoupper($type);
            $obj = new $class;
            return $obj->decode($clear);
        }
        throw new Exception('Unknown decoding algorithm');
    }
}
