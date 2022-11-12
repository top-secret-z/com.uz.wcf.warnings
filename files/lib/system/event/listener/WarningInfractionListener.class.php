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
namespace wcf\system\event\listener;

use wcf\data\user\infraction\warning\UserInfractionWarning;
use wcf\data\user\User;
use wcf\data\user\UserEditor;
use wcf\system\cache\builder\WarningLatestWarnedMembersCacheBuilder;
use wcf\system\cache\builder\WarningMostPointsMembersCacheBuilder;
use wcf\system\cache\builder\WarningMostWarnedMembersCacheBuilder;
use wcf\system\moderation\queue\ModerationQueueReportManager;
use wcf\system\WCF;

/**
 * Listen to warning actions to update wcf1_user
 */
class WarningInfractionListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        // only if module is activated
        if (!MODULE_USER_INFRACTION) {
            return;
        }

        // only react on warnings
        if ($className != 'wcf\data\user\infraction\warning\UserInfractionWarningAction') {
            return;
        }

        // Need action create or revoke
        $action = $eventObj->getActionName();

        // update warning count in user table
        if ($action == 'revoke') {
            $objectIDs = $eventObj->getObjectIDs();
            foreach ($objectIDs as $id) {
                $warning = new UserInfractionWarning($id);
                if (!$warning->userWarningID) {
                    continue;
                }

                $user = new User($warning->userID);
                if (!$user->userID) {
                    continue;
                }

                // points
                $sql = "SELECT    COALESCE(SUM(points), 0) AS points
                        FROM    wcf" . WCF_N . "_user_infraction_warning
                        WHERE    userID = ? AND revoked = ?";
                $statement = WCF::getDB()->prepareStatement($sql);
                $statement->execute([$user->userID, 0]);
                $row = $statement->fetchArray();
                $points = $row['points'];

                $userEditor = new UserEditor($user);
                $userEditor->update([
                    'uzWarnings' => $user->uzWarnings > 0 ? $user->uzWarnings - 1 : 0,
                    'uzWarningPoints' => $points,
                ]);
            }
        }

        if ($action == 'create') {
            $params = $eventObj->getParameters();
            if (isset($params['data']['userID'])) {
                $user = new User($params['data']['userID']);
                if ($user->userID) {
                    if (isset($params['data']['points'])) {
                        $points = $params['data']['points'];
                    } else {
                        $points = 0;
                    }

                    $userEditor = new UserEditor($user);
                    $userEditor->update([
                        'uzWarnings' => $user->uzWarnings + 1,
                        'uzWarningPoints' => $user->uzWarningPoints + $points,
                    ]);

                    // report if configured
                    if (MODULE_WARNING_REPORT) {
                        $message = WCF::getLanguage()->getDynamicVariable('wcf.user.uzwarning.report', [
                            'moderator' => WCF::getUser()->username,
                            'receiver' => $user->username,
                        ]);
                        ModerationQueueReportManager::getInstance()->addReport('com.woltlab.wcf.user', $user->userID, $message);
                    }
                }
            }
        }

        // reset cache
        WarningMostPointsMembersCacheBuilder::getInstance()->reset();
        WarningMostWarnedMembersCacheBuilder::getInstance()->reset();
        WarningLatestWarnedMembersCacheBuilder::getInstance()->reset();
    }
}
