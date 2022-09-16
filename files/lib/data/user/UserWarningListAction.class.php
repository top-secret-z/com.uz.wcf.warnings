<?php
namespace wcf\data\user;
use wcf\data\IGroupedUserListAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * Shows a list of users with warnings.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class UserWarningListAction extends UserProfileAction implements IGroupedUserListAction {
	/**
	 * @inheritDoc
	 */
	protected $allowGuestAccess = ['getGroupedUserList'];
	
	/**
	 * @inheritDoc
	 */
	public function validateGetGroupedUserList() {
		if (!WCF::getSession()->getPermission('user.profile.warning.canView')) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function getGroupedUserList() {
		$userList = new UserProfileList();
		$userList->getConditionBuilder()->add('user_table.uzWarnings > 0');
		$userList->sqlOrderBy = 'uzWarnings DESC';
		$userList->readObjects();
		
		WCF::getTPL()->assign([
				'users' => $userList->getObjects()
		]);
		return [
			'pageCount' => 1,
			'template' => WCF::getTPL()->fetch('userMostWarnedList')
		];
	}
}
