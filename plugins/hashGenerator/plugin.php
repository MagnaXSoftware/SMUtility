<?php
/**
 * Hash Generator
 */

/**
 * Require plugin interfaces.
 */
require_once ROOT . 'plugins.php';
require_once ROOT . 'plugins' . DS . 'common' . DS . 'algos.php';

/**
 * Generates a hash from a clear text string.
 *
 * @package Plugin
 * @subpackage HashGenerator
 */
class SMU_HashGenerator extends SMU_Plugin {
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
                'label' => 'Hash',
                'name' => 'type',
                'type' => 'radio',
                'value' => $this->_listHashes()
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
        $algo = $this->_cleanPath($form['type']);
        return array(
            array(
                'label' => 'Hashed value',
                'type' => 'string',
                'value' => $this->_generateHash($form['value'], $algo)
            ),
            'options' => array(
                'Hashing algorithm' => strtoupper($algo),
                'clear text' => $form['value']
            )
        );
    }

    /**
     * Returns a list of the hashes (all files in algos subdirectory).
     *
     * @return array
     */
    private function _listHashes() {
        $return = array();
        foreach (scandir($this->algosDir) as $item) {
            if (preg_match('/(\w+)\.php/', $item, $matches)) {
                include_once $this->algosDir . DS . $item;
                $class = "Algo_" . strtoupper($matches[1]);
                $obj = new $class;
                if ($obj instanceof Hash_Hashable) {
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
     * Generates the hash.
     *
     * @param string $clear
     * @param string $type
     * @return string
     * @throws Exception
     */
    private function _generateHash($clear, $type) {
        $file = $this->algosDir . DS . $type . '.php';
        if (file_exists($file)) {
            require_once $file;
            $class = "Algo_" . strtoupper($type);
            $obj = new $class;
            if (!($obj instanceof Hash_Hashable)) {
                throw new Exception('Algorithm is not hashable');
            }
            return $obj->hash($clear);
        }
        throw new Exception('Unknown hashing algorithm');
    }
}
