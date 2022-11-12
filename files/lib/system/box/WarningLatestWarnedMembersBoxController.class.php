<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: http://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace wcf\system\box;

use wcf\system\cache\builder\WarningLatestWarnedMembersCacheBuilder;
use wcf\system\WCF;

/**
 * Shows members with the latest warnings.
 */
class WarningLatestWarnedMembersBoxController extends AbstractBoxController
{
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
    public function hasLink()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function loadContent()
    {
        if (MODULE_USER_INFRACTION && WCF::getSession()->getPermission('user.profile.warning.canView')) {
            $warnings = WarningLatestWarnedMembersCacheBuilder::getInstance()->getData();
            if (!empty($warnings)) {
                WCF::getTPL()->assign([
                    'warnings' => $warnings,
                ]);
                $this->content = WCF::getTPL()->fetch($this->templateName);
            }
        }
    }
}
