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
namespace wcf\system\worker;

use wcf\data\user\UserList;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Worker implementation to synchronize warning counts in user table
 */
class UzWarningsRebuildDataWorker extends AbstractRebuildDataWorker
{
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
    protected function initObjectList()
    {
        parent::initObjectList();

        $this->objectList->sqlOrderBy = 'user_table.userID';
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        parent::execute();

        // only if module
        if (!MODULE_USER_INFRACTION) {
            return;
        }

        $userIDs = [];
        foreach ($this->getObjectList() as $user) {
            $userIDs[] = $user->userID;
        }

        if (!empty($userIDs)) {
            // update warning count
            $conditionBuilder = new PreparedStatementConditionBuilder();
            $conditionBuilder->add('user_table.userID IN (?)', [$userIDs]);
            $sql = "UPDATE    wcf" . WCF_N . "_user user_table
                    SET    uzWarnings = (
                            SELECT    COUNT(*)
                            FROM    wcf" . WCF_N . "_user_infraction_warning
                            WHERE    userID = user_table.userID AND revoked = 0
                            ),
                        uzWarningPoints = (
                            SELECT    COALESCE(SUM(points), 0)
                            FROM    wcf" . WCF_N . "_user_infraction_warning
                            WHERE    userID = user_table.userID AND revoked = 0
                            )
                    " . $conditionBuilder;
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute($conditionBuilder->getParameters());
        }
    }
}
