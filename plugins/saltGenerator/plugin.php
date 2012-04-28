<?php
/**
 * Salt Generator
 */

/**
 * Require plugin interfaces.
 */
require_once ROOT . 'plugins.php';

/**
 * Generates a salt.
 *
 * @package Plugin
 * @subpackage SaltGenerator
 */
class SMU_SaltGenerator extends SMU_Plugin {

    /**
     * Returns a multidimentional array used to build the configuration page.
     *
     * @see SMU_Configurable::form()
     * @return array
     */
    public function form() {
        return array(
            array(
                'label' => 'length',
                'name' => 'length',
                'type' => 'integer'
            )
        );
    }

    /**
     * Runs the plugin and returns a multidimentional array that contains the
     * result of the plugin's execution.
     *
     * @see SMU_Executable::execute()
     * @param array $form Configuration values
     * @return array
     */
    public function execute(array &$form) {
        if (!isset($form['length']) || empty($form['length'])) {
            $form['length'] = 15;
        }
        return array(
            array(
                'label' => 'salt',
                'type' => 'string',
                'value' => $this->_generateSalt((int) $form['length'])
            ),
            'options' => array(
                'length' => (int) $form['length']
            )
        );
    }

    /**
     * Generates the salt.
     *
     * @param int $length
     * @return string
     */
    private function _generateSalt($length) {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $length) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
    }
}
