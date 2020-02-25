<?php
namespace Psmb\Newsletter\Parser;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Configuration\ConfigurationManager;

/**
 * Class RequestParser
 * @Flow\Scope("singleton")
 * @package Psmb\Newsletter\Parser
 */
class RequestParser {

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var \Neos\Flow\Configuration\ConfigurationManager
     * @Flow\Inject
     */
    protected $configurationManager;

    /**
     * @var string
     */
    protected $device;

    /**
     * @var string
     */
    protected $operationSystem;

    const DEVICE_TYPE_DESKTOP              = 0;
    const DEVICE_TYPE_SMARTPHONE           = 1;
    const DEVICE_TYPE_TABLET               = 2;

    /**
     * Detectable device types
     *
     * @var array
     */
    protected static $deviceTypes = array(
        'desktop'               => self::DEVICE_TYPE_DESKTOP,
        'smartphone'            => self::DEVICE_TYPE_SMARTPHONE,
        'tablet'                => self::DEVICE_TYPE_TABLET
    );

    /**
     * Operating system families mapped to the short codes of the associated operating systems
     *
     * @var array
     */
    protected static $osFamilies = array(
        'Android'               => 'Android',
        'AmigaOS'               => 'Other',
        'Apple TV'              => 'Other',
        'BlackBerry'            => 'Other',
        'Brew'                  => 'Other',
        'BeOS'                  => 'Other',
        'Chrome OS'             => 'Other',
        'Firefox OS'            => 'Other',
        'Gaming Console'        => 'Other',
        'Google TV'             => 'Other',
        'IBM'                   => 'Other',
        'iOS'                   => 'iOS',
        'RISC OS'               => 'Other',
        'GNU/Linux'             => 'Unix',
        'Mac'                   => 'Apple',
        'Mobile Gaming Console' => 'Other',
        'Real-time OS'          => 'Other',
        'Other Mobile'          => 'Other',
        'Symbian'               => 'Other',
        'Unix'                  => 'Unix',
        'WebTV'                 => 'Other',
        'Windows'               => 'Windows',
        'Windows Mobile'        => 'Windows',
    );

    /**
     * RequestParser constructor.
     * @param $userAgent
     */
    public function __construct($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return bool|mixed|string
     */
    public function getDevice()
    {
        $this->configurationManager->registerConfigurationType('Devices', ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT);
        $devices = $this->configurationManager->getConfiguration('Devices');

        if ($devices) {
            foreach ($devices as $brand => $data) {
                $matches = $this->matchUserAgent($data['regex']);

                if ($matches) {
                    break;
                }
            }

            if (empty($matches)) {
                return 'desktop';
            }

            if (isset($data['device']) && in_array($data['device'], self::$deviceTypes)) {
                $this->device = self::$deviceTypes[$data['device']];
            }

            if (isset($data['models'])) {
                foreach ($data['models'] as $modelRegex) {
                    $modelMatches = $this->matchUserAgent($modelRegex['regex']);
                    if ($modelMatches) {
                        break;
                    }
                }

                if (isset($modelRegex['device']) && in_array($modelRegex['device'], self::$deviceTypes)) {
                    $this->device = self::$deviceTypes[$modelRegex['device']];
                }
            }
        }

        return $this->device;
    }

    /**
     * @return string
     */
    public function getOperatingSystem()
    {
        $this->configurationManager->registerConfigurationType('OSS', ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT);
        $operatingSystems = $this->configurationManager->getConfiguration('OSS');

        foreach ($operatingSystems as $osRegex) {
            $matches = $this->matchUserAgent($osRegex['regex']);
            if ($matches) {
                break;
            }
        }

        if (!$matches) {
            return 'other';
        }

        $name = $this->buildByMatch($osRegex['name'], $matches);

        if (isset(self::$osFamilies[$name])) {
            $this->operationSystem = self::$osFamilies[$name];
        }

        return strtolower($this->operationSystem);
    }

    /**
     * Matches the useragent against the given regex
     *
     * @param $regex
     * @return array|bool
     */
    protected function matchUserAgent($regex)
    {
        // only match if useragent begins with given regex or there is no letter before it
        $regex = '/(?:^|[^A-Z0-9\-_]|[^A-Z0-9\-]_|sprd-)(?:' . str_replace('/', '\/', $regex) . ')/i';
        if (preg_match($regex, $this->userAgent, $matches)) {
            return $matches;
        }
        return false;
    }

    /**
     * @param string $item
     * @param array $matches
     * @return string type
     */
    protected function buildByMatch($item, $matches)
    {
        for ($nb=1;$nb<=3;$nb++) {
            if (strpos($item, '$' . $nb) === false) {
                continue;
            }
            $replace = isset($matches[$nb]) ? $matches[$nb] : '';
            $item = trim(str_replace('$' . $nb, $replace, $item));
        }
        return $item;
    }
}