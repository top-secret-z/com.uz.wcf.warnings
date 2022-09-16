<?php
namespace wcf\system\worker;
use wcf\data\user\UserList;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Worker implementation to synchronize warning counts in user table
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class UzWarningsRebuildDataWorker extends AbstractRebuildDataWorker {
	/**
	 * @inheritDoc
	 */
	protected $objectListClassName = UserList::class;
	
	/**
	 * @inheritDoc
	 */
	protected $limit = 100;
	
	/**
	 * @inheritDoc
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		$this->objectList->sqlOrderBy = 'user_table.userID';
	}
	
	/**
	 * @inheritDoc
	 */
	public function execute() {
		parent::execute();
		
		// only if module
		if (!MODULE_USER_INFRACTION) return;
		
		$userIDs = [];
		foreach ($this->getObjectList() as $user) {
			$userIDs[] = $user->userID;
		}
		
		if (!empty($userIDs)) {
			// update warning count
			$conditionBuilder = new PreparedStatementConditionBuilder();
			$conditionBuilder->add('user_table.userID IN (?)', [$userIDs]);
			$sql = "UPDATE	wcf".WCF_N."_user user_table
					SET	uzWarnings = (
							SELECT	COUNT(*)
							FROM	wcf".WCF_N."_user_infraction_warning
							WHERE	userID = user_table.userID AND revoked = 0
							),
						uzWarningPoints = (
							SELECT	COALESCE(SUM(points), 0)
							FROM	wcf".WCF_N."_user_infraction_warning
							WHERE	userID = user_table.userID AND revoked = 0
							)
					".$conditionBuilder;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditionBuilder->getParameters());
		}
	}
}
