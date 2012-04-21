<?php
/**
 * Hash Generator
 */

/**
 * Require plugin interfaces.
 */
require_once ROOT . 'plugins.php';

/**
 * Generates a hash from a clear text string.
 *
 * @package Plugin
 * @subpackage HashGenerator
 */
class SMU_HashGenerator extends SMU_Plugin {
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
        if (!isset($form['type']) || empty($form['type'])) {
            throw new Exception('No configuration option was sent to the plugin.');
        }
        if (!isset($form['value'])) {
            $form['value'] = '';
        }
        return array(
            array(
                'label' => 'Hashed value',
                'type' => 'string',
                'value' => $this->_generateHash($form['value'], $form['type'])
            ),
            'options' => array(
                'Hashing algorythm' => strtoupper($form['type']),
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
        foreach (scandir(ROOT . 'plugins' . DS . 'hashGenerator' . DS . 'algos') as $item) {
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
     * Generates the hash.
     *
     * @param string $clear
     * @param string $type
     * @return string
     * @throws Exception
     */
    private function _generateHash($clear, $type) {
        $file = ROOT . 'plugins' . DS . 'hashGenerator' . DS . 'algos' . DS . $type . '.php';
        if (file_exists($file)) {
            require_once $file;
            $class = "HashAddIn_" . strtoupper($type);
            $obj = new $class;
            return $obj->doHash($clear);
        }
        throw new Exception('Unknown hashing algorithm');
    }
}
