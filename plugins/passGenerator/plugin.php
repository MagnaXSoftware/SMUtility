<?php
/**
 * Password Generator
 */
/**
 * Require plugin interfaces.
 */
require_once ROOT . 'plugins.php';

/**
 * Generates a password.
 *
 * @package Plugin
 * @subpackage PassGenerator
 */
class SMU_PassGenerator extends SMU_Plugin {
    /**
     * Array of the password types.
     * @var type
     */
    private $types = array(
        array('name' => 'alphanumeric corrected', 'ID' => '0', 'value' => 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789', 'default' => true),
        array('name' => 'alphanumeric full', 'ID' => '1', 'value' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
        array('name' => 'alphanumeric corrected extended', 'ID' => '2', 'value' => 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789!@#$%&_?*', 'default' => true),
        array('name' => 'alphanumeric full extended', 'ID' => '3', 'value' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&_?*'),
        array('name' => 'alpha corected', 'ID' => '4', 'value' => 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ'),
        array('name' => 'alpha full', 'ID' => '5', 'value' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
        array('name' => 'numeric', 'ID' => '6', 'value' => '0123456789'),
        array('name' => 'extra', 'ID' => '7', 'value' => '!@#$%&_?*')
    );

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
            ),
            array(
                'label' => 'Password Type',
                'name' => 'type',
                'type' => 'radio',
                'value' => $this->_getOptions()
            )
        );
    }

    /**
     * Runs the plugin and returns a multidimentional array that contains the
     * result of the plugin's execution.
     *
     * The default
     *
     * @see SMU_Executable::execute()
     * @param array $form Configuration values
     * @throws Exception
     * @return array
     */
    public function execute(array &$form) {
        if (!isset($form['length']) || empty($form['length'])) {
            $form['length'] = 8;
        }
        if (!isset($form['type']) || empty($form['type'])) {
            throw new Exception('No configuration option was sent to the plugin.');
        }
        $type = $this->_getOptions(false, $form['type']);
        return array(
            array(
                'label' => 'password',
                'type' => 'string',
                'value' => $this->_generatePass((int) $form['length'], $type['value'])
            ),
            'options' => array(
                'length' => (int) $form['length'],
                'type' => $type['name']
            )
        );
    }

    /**
     * Plays with the password types.
     *
     * If $form is true, then returns a multidimensional array for use in configuration.
     * If $form is false, then find the type that matches the given ID.
     *
     * @param bool $form Activate configuration behavior
     * @param int $setType ID to match
     * @return array
     * @throws Exception
     */
    private function _getOptions($form = true, $setType = null) {
        if ($form) {
            $result = array();
            foreach ($this->types as $type) {
                $result[] = array('label' => $type['name'], 'value' => $type['ID'], 'checked' => ((isset($type['default'])) ? $type['default'] : false), 'disabled' => ((isset($type['disabled'])) ? $type['disabled'] : false));
            }
            return $result;
        }

        foreach ($this->types as $type) {
            if ($type['ID'] == $setType) {
                return $type;
            }
        }
        throw new Exception('Specified type does not exist.');
    }

    /**
     * Generates the password.
     *
     * @param int $length Length of the generated password
     * @param string $characterList String containing usable characters
     * @return string
     */
    private function _generatePass($length, $characterList) {
        $i = 0;
        $pass = "";
        while ($i < $length) {
            $pass .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $pass;
    }
}
