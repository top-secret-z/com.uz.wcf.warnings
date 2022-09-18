{if MODULE_USER_INFRACTION}
    {if WARNING_DISPLAY_MESSAGE_SIDEBAR && $userProfile->uzWarnings && $__wcf->session->getPermission('user.profile.warning.canView')}
        {if $__wcf->session->getPermission('mod.infraction.warning.canWarn')}
            <dt><a href="{link controller='User' object=$userProfile}{/link}#warning" class="jsTooltip" title="{lang user=$userProfile}wcf.user.uzwarning.showWarnings{/lang}">{lang}wcf.user.uzwarning.warnings{/lang}</a></dt>
            <dd>{#$userProfile->uzWarnings}</dd>
        {else}
            <dt>{lang}wcf.user.uzwarning.warnings{/lang}</dt>
            <dd>{#$userProfile->uzWarnings}</dd>
        {/if}
    {/if}

    {if WARNING_POINTS_DISPLAY_MESSAGE_SIDEBAR && $__wcf->session->getPermission('user.profile.warning.canViewPoints')}
        {if $userProfile->uzWarningPoints || $userProfile->uzWarnings}
            {if $__wcf->session->getPermission('mod.infraction.warning.canWarn')}
                <dt><a href="{link controller='User' object=$userProfile}{/link}#warning" class="jsTooltip" title="{lang user=$userProfile}wcf.user.uzwarning.showWarnings{/lang}">{lang}wcf.user.uzwarning.warningPoints{/lang}</a></dt>
                <dd>{#$userProfile->uzWarningPoints}</dd>
            {else}
                <dt>{lang}wcf.user.uzwarning.warningPoints{/lang}</dt>
                <dd>{#$userProfile->uzWarningPoints}</dd>
            {/if}
        {/if}
    {/if}
{/if}
