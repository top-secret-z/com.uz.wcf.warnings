<?php
namespace wcf\system\cache\builder;
use wcf\system\WCF;

/**
 * Caches the amount of warnings for stats.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningStatsCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @inheritDoc
	 */
	protected $maxLifetime = 300;
	
	/**
	 * @inheritDoc
	 */
	protected function rebuild(array $parameters) {
		$data = [];
		
		// amount of warnings
		$sql = "SELECT	COUNT(*) AS amount
				FROM	wcf".WCF_N."_user_infraction_warning
				WHERE	revoked = 0";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		$data['count'] = $statement->fetchColumn();
		
		return $data;
	}
}
