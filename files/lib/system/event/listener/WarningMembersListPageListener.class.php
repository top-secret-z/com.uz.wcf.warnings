<?php
namespace wcf\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\WCF;

/**
 * Adds 'uzWarnings' sort field for members list.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningMembersListPageListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (WCF::getSession()->getPermission('user.profile.warning.canView')) {
			$eventObj->validSortFields[] = 'uzWarnings';
		}
		
		if (WCF::getSession()->getPermission('user.profile.warning.canViewPoints')) {
			$eventObj->validSortFields[] = 'uzWarningPoints';
		}
	}
}
