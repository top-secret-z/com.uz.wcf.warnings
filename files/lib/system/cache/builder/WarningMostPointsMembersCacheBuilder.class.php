<?php
namespace wcf\system\cache\builder;
use wcf\system\cache\builder\AbstractSortedUserCacheBuilder;

/**
 * Caches the list of the members with the most warning points.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.warnings
 */
class WarningMostPointsMembersCacheBuilder extends AbstractSortedUserCacheBuilder {
	/**
	 * @inheritDoc
	 */
	protected $maxLifetime = 300;
	
	/**
	 * @inheritDoc
	 */
	protected $defaultLimit = WARNING_DISPLAY_BOX_ENTRIES;
	
	/**
	 * @inheritDoc
	 */
	protected $positiveValuesOnly = true;
	
	/**
	 * @inheritDoc
	 */
	protected $sortField = 'uzWarningPoints';
}
