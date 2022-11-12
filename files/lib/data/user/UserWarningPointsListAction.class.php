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
namespace wcf\data\user;

use wcf\data\IGroupedUserListAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * Shows a list of users with warning points.
 */
class UserWarningPointsListAction extends UserProfileAction implements IGroupedUserListAction
{
    /**
     * @inheritDoc
     */
    protected $allowGuestAccess = ['getGroupedUserList'];

    /**
     * @inheritDoc
     */
    public function validateGetGroupedUserList()
    {
        if (!WCF::getSession()->getPermission('user.profile.warning.canViewPoints')) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * @inheritDoc
     */
    public function getGroupedUserList()
    {
        $userList = new UserProfileList();
        $userList->getConditionBuilder()->add('user_table.uzWarningPoints > 0');
        $userList->sqlOrderBy = 'uzWarningPoints DESC';
        $userList->readObjects();

        WCF::getTPL()->assign([
            'users' => $userList->getObjects(),
        ]);

        return [
            'pageCount' => 1,
            'template' => WCF::getTPL()->fetch('userMostWarnedList'),
        ];
    }
}
