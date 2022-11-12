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

use wcf\system\cache\builder\WarningMostWarnedMembersCacheBuilder;
use wcf\system\cache\runtime\UserProfileRuntimeCache;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Shows members with the most warnings.
 */
class WarningMostWarnedMembersBoxController extends AbstractBoxController
{
    /**
     * @inheritDoc
     */
    protected static $supportedPositions = [];

    /**
     * template name
     */
    protected $templateName = 'boxWarningMostWarnedMembers';

    /**
     * @inheritDoc
     */
    public function hasLink()
    {
        return MODULE_MEMBERS_LIST == 1;
    }

    /**
     * @inheritDoc
     */
    public function getLink()
    {
        if (MODULE_MEMBERS_LIST) {
            $parameters = 'sortField=uzWarnings&sortOrder=DESC';

            return LinkHandler::getInstance()->getLink('MembersList', [], $parameters);
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    protected function loadContent()
    {
        if (MODULE_USER_INFRACTION && WCF::getSession()->getPermission('user.profile.warning.canView')) {
            $userIDs = WarningMostWarnedMembersCacheBuilder::getInstance()->getData();
            if (!empty($userIDs)) {
                $userProfiles = UserProfileRuntimeCache::getInstance()->getObjects($userIDs);

                WCF::getTPL()->assign([
                    'userProfiles' => $userProfiles,
                ]);
                $this->content = WCF::getTPL()->fetch($this->templateName);
            }
        }
    }
}
