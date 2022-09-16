<?php
namespace wcf\system\event\listener;
use wcf\system\cache\builder\WarningStatsCacheBuilder;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

/**
 * Adds the warning stats in the statistics box.
 *
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningStatisticsBoxControllerListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		WCF::getTPL()->assign([
				'warningStatistics' => WarningStatsCacheBuilder::getInstance()->getData()
		]);
	}
}
