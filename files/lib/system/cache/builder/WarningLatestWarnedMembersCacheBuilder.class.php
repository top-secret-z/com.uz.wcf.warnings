<?php
namespace wcf\system\cache\builder;
use wcf\data\user\infraction\warning\UserInfractionWarningList;
use wcf\system\cache\runtime\UserProfileRuntimeCache;

/**
 * Caches the list of the latest warned members.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningLatestWarnedMembersCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @inheritDoc
	 */
	protected $maxLifetime = 300;
	
	/**
	 * @inheritDoc
	 */
	protected function rebuild(array $parameters) {
		$warnings = [];
		$warningList = new UserInfractionWarningList();
		$warningList->getConditionBuilder()->add('user_infraction_warning.revoked = 0');
		$warningList->sqlOrderBy = 'user_infraction_warning.time DESC';
		$warningList->sqlLimit = WARNING_DISPLAY_BOX_ENTRIES;
		$warningList->readObjects();
		
		foreach($warningList->getObjects() as $warning) {
			$warnings[] = [
					'profile' => UserProfileRuntimeCache::getInstance()->getObject($warning->userID),
					'time' => $warning->time
			];
		}
		
		return $warnings;
	}
}
