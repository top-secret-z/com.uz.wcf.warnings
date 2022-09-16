<?php
namespace wcf\system\stat;
use wcf\system\WCF;

/**
 * Stat handler implementation for user warning points
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningPointsStatDailyHandler extends AbstractStatDailyHandler {
	/**
	 * @inheritDoc
	 */
	public function getData($date) {
		$sql = "SELECT	COALESCE(SUM(points), 0)
				FROM	wcf".WCF_N."_user_infraction_warning
				WHERE	time BETWEEN ? AND ? AND revoked = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([$date, $date + 86399, 0]);
		$counter = intval($statement->fetchColumn());
		
		$sql = "SELECT	COALESCE(SUM(points), 0)
				FROM	wcf".WCF_N."_user_infraction_warning
				WHERE	time < ? AND revoked = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([$date + 86400, 0]);
		$total = intval($statement->fetchColumn());
		
		return [
				'counter' => $counter,
				'total' => $total
		];
	}
}