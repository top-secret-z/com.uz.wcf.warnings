<?php
namespace wcf\system\box;
use wcf\system\cache\builder\WarningLatestWarnedMembersCacheBuilder;
use wcf\system\WCF;

/**
 * Shows members with the latest warnings.
 *
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningLatestWarnedMembersBoxController extends AbstractBoxController {
	/**
	 * @inheritDoc
	 */
	protected static $supportedPositions = [];
	
	/**
	 * template name
	 */
	protected $templateName = 'boxWarningLatestWarnedMembers';
	
	/**
	 * @inheritDoc
	 */
	public function hasLink() {
		return false;
	}
	
	/**
	 * @inheritDoc
	 */
	protected function loadContent() {
		if (MODULE_USER_INFRACTION && WCF::getSession()->getPermission('user.profile.warning.canView')) {
			$warnings = WarningLatestWarnedMembersCacheBuilder::getInstance()->getData();
			if (!empty($warnings)) {
				WCF::getTPL()->assign([
						'warnings' => $warnings
				]);
				$this->content = WCF::getTPL()->fetch($this->templateName);
			}
		}
	}
}
