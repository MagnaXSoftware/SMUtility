<?php
/**
 * Plugin-related classes and interfaces
 *
 * @package Core
 * @subpackage Plugins
 */

/**
 * Allows the plugin to display a configuration.
 *
 * @package Core
 * @subpackage Plugins
 */
interface SMU_Configurable {

    /**
     * Returns a multidimentional array used to build the configuration page.
     *
     * @return array
     */
    public function form();
}

/**
 * Allows the plugin to execute.
 *
 * @package Core
 * @subpackage Plugins
 */
interface SMU_Executable {

    /**
     * Runs the plugin and returns a multidimentional array that contains the
     * result of the plugin's execution.
     *
     * @param array $form Configuration values
     * @return array
     */
    public function execute(array &$form);
}

/**
 * Base abstract class that plugins must extend.
 *
 * @package Core
 * @subpackage Plugins
 */
abstract class SMU_Plugin implements SMU_Configurable, SMU_Executable {

}
