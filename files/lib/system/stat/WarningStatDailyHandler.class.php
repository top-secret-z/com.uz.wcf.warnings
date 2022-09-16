<?php
namespace wcf\system\stat;
use wcf\system\WCF;

/**
 * Stat handler implementation for user warnings
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningStatDailyHandler extends AbstractStatDailyHandler {
	/**
	 * @inheritDoc
	 */
	public function getData($date) {
		$sql = "SELECT	COUNT(*)
				FROM	wcf".WCF_N."_user_infraction_warning
				WHERE	time BETWEEN ? AND ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([$date, $date + 86399]);
		$counter = intval($statement->fetchColumn());
		
		$sql = "SELECT	COUNT(*)
				FROM	wcf".WCF_N."_user_infraction_warning
				WHERE	time < ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([$date + 86400]);
		$total = intval($statement->fetchColumn());
		
		return [
				'counter' => $counter,
				'total' => $total
		];
	}
}