<?php
/**
 * UUID Generator
 */

/**
 * Require plugin interfaces.
 */
require_once ROOT . 'plugins.php';

/**
 * Generates a version 3, 4, or 5 UUID.
 *
 * @package Plugin
 * @subpackage UUIDGenerator
 */
class SMU_UUIDGenerator extends SMU_Plugin {
    /**
     * Array of the UUID types.
     * @var array
     */
    private $types = array(
        array('name' => 'version 3', 'ID' => '3'),
        array('name' => 'version 4', 'ID' => '4', 'default' => true),
        array('name' => 'version 5', 'ID' => '5')
    );

    /**
     * Array of the namespaces.
     * @var array
     */
    private $ns = array(
        array('name' => 'DNS', 'ID' => 'dns', 'uuid' => '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
        array('name' => 'URL', 'ID' => 'url', 'uuid' => '6ba7b811-9dad-11d1-80b4-00c04fd430c8'),
        array('name' => 'OID', 'ID' => 'oid', 'uuid' => '6ba7b812-9dad-11d1-80b4-00c04fd430c8'),
        array('name' => 'X500', 'ID' => 'x500', 'uuid' => '6ba7b814-9dad-11d1-80b4-00c04fd430c8')
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
                'label' => 'UUID Type',
                'name' => 'type',
                'type' => 'radio',
                'value' => $this->_getOptions()
            ),
            array(
                'label' => 'Namespace <em>(Only valid for UUID version 3 or 5)</em>',
                'name' => 'ns',
                'type' => 'radio',
                'value' => $this->_getNamespaces()
            ),
            array(
                'label' => 'Text <em>(Only  valid for UUID version 3 or 5)</em>',
                'name' => 'name',
                'type' => 'string'
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
        $type = $this->_getOptions(false, $form['type']);
        $options = array(
            'UUID Version' => $type['ID']
        );
        if ($form['type'] == 3 || $form['type'] == 5) {
            if (!isset($form['ns']) || empty($form['ns'])) {
                throw new Exception('No namespace was selected.');
            }
            if (!isset($form['name'])) {
                throw new Exception('No string was sent to the plugin.');
            }
            $ns = $this->_getNamespaces(false, $form['ns']);
            $options['namespace'] = $ns['name'];
            $options['text'] = $form['name'];
        }
        return array(
            array(
                'label' => 'UUID',
                'type' => 'string',
                'value' => $this->_generateUUID($form['type'], ((isset($ns)) ? $ns['uuid'] : null), ((isset($form['name'])) ? $form['name'] : null))
            ),
            'options' => $options
        );
    }

    /**
     * Plays with the UUID types.
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
                $result[] = array('label' => $type['name'], 'value' => $type['ID'], 'checked' => ((isset($type['default'])) ? $type['default'] : false));
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
     * Plays with the UUID namespaces.
     *
     * If $form is true, then returns a multidimensional array for use in configuration.
     * If $form is false, then find the namespace that matches the given ID.
     *
     * @param bool $form Activate configuration behavior
     * @param int $setNS ID to match
     * @return array
     * @throws Exception
     */
    private function _getNamespaces($form = true, $setNS = null) {
        if ($form) {
            $result = array();
            foreach ($this->ns as $ns) {
                $result[] = array('label' => $ns['name'], 'value' => $ns['ID']);
            }
            return $result;
        }

        foreach ($this->ns as $ns) {
            if ($ns['ID'] == $setNS) {
                return $ns;
            }
        }
        throw new Exception('Specified namespace does not exist.');
    }

    /**
     * Calls the UUID generators.
     *
     * @param int $type UUID type to generate
     * @param string $ns Namespace UUID
     * @param string $name Text to hash
     * @return string
     * @throws Exception
     */
    private function _generateUUID($type, $ns = null, $name = null) {
        $raw = array(
            'time_low' => null,
            'time_mid' => null,
            'time_high_and_version' => null,
            'clock_sec_and_reserved' => null,
            'clock_sec_low' => null,
            'node' => null
        );
        $uuid = '';

        switch ($type) {
            case 3:
                $this->_generateUUID_3($raw, $ns, $name);
                break;
            case 4:
                $this->_generateUUID_4($raw);
                break;
            case 5:
                $this->_generateUUID_5($raw, $ns, $name);
                break;
            default:
                throw new Exception('Unknown option');
                break;
        }

        /*
         * PHP doesn't support 32-bit unsigned integers, therefore, the max size
         * for each field is 4 hex digits.
         */
        foreach ($raw['time_low'] as $tm) {
            $uuid .= sprintf('%04x', $tm);
        }
        $uuid .= sprintf('-%04x-%04x-%02x%02x-', $raw['time_mid'], $raw['time_high_and_version'], $raw['clock_sec_and_reserved'], $raw['clock_sec_low']
        );
        foreach ($raw['node'] as $node) {
            $uuid .= sprintf('%04x', $node);
        }

        return $uuid;
    }

    /**
     * Generates a version 3 UUID. (md5 hash)
     *
     * @param array $raw Hexadecimal UUID, passed by reference
     * @param string $ns Namespace UUID
     * @param string $name Text to hash
     */
    private function _generateUUID_3(&$raw, $ns, $name) {
        $nsbin = $this->_str_to_bin($ns);
        $hash = md5($nsbin . $name);

        $raw = array(
            'time_low' => array(
                hexdec(substr($hash, 0, 4)),
                hexdec(substr($hash, 4, 4))
            ),
            'time_mid' => hexdec(substr($hash, 8, 4)),
            'time_high_and_version' => (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
            'clock_sec_and_reserved' => (hexdec(substr($hash, 16, 2)) & 0x3f) | 0x80,
            'clock_sec_low' => hexdec(substr($hash, 18, 2)),
            'node' => array(
                hexdec(substr($hash, 20, 4)),
                hexdec(substr($hash, 24, 4)),
                hexdec(substr($hash, 28, 4)),
            )
        );
    }

    /**
     * Generates a version 4 UUID.
     *
     * @param array $raw Hexadecimal UUID, passed by reference
     */
    private function _generateUUID_4(&$raw) {
        $raw = array(
            'time_low' => array(
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            ),
            'time_mid' => mt_rand(0, 0xffff),
            'time_high_and_version' => mt_rand(0, 0x0fff) | 0x4000,
            'clock_sec_and_reserved' => mt_rand(0, 0x3f) | 0x80,
            'clock_sec_low' => mt_rand(0, 0xff),
            'node' => array(
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
            )
        );
    }

    /**
     * Generates a version 5 UUID. (sha1 hash)
     *
     * @param array $raw Hexadecimal UUID, passed by reference
     * @param string $ns Namespace UUID
     * @param string $name Text to hash
     */
    private function _generateUUID_5(&$raw, $ns, $name) {
        $nsbin = $this->_str_to_bin($ns);
        $hash = sha1($nsbin . $name);

        $raw = array(
            'time_low' => array(
                hexdec(substr($hash, 0, 4)),
                hexdec(substr($hash, 4, 4))
            ),
            'time_mid' => hexdec(substr($hash, 8, 4)),
            'time_high_and_version' => (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
            'clock_sec_and_reserved' => (hexdec(substr($hash, 16, 2)) & 0x3f) | 0x80,
            'clock_sec_low' => hexdec(substr($hash, 18, 2)),
            'node' => array(
                hexdec(substr($hash, 20, 4)),
                hexdec(substr($hash, 24, 4)),
                hexdec(substr($hash, 28, 4)),
            )
        );
    }

    /**
     * Converts an hexadecimal string UUID to it's binary representation.
     *
     * @param string $uuid UUID
     * @return binary
     */
    private function _str_to_bin($uuid) {
        $hex = str_replace(array('-', '{', '}'), '', $uuid);
        $bin = '';

        for ($i = 0, $max = strlen($hex); $i < $max; $i += 2) {
            $bin .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $bin;
    }
}
