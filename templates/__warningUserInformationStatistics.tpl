{if MODULE_USER_INFRACTION}
    {if WARNING_DISPLAY_USERINFORMATION && $user->uzWarnings && $__wcf->session->getPermission('user.profile.warning.canView')}
        {if $__wcf->session->getPermission('mod.infraction.warning.canWarn')}
            <dt><a href="{link controller='User' object=$user}{/link}#warning" class="jsTooltip" title="{lang user=$user}wcf.user.uzwarning.showWarnings{/lang}">{lang}wcf.user.uzwarning.warnings{/lang}</a></dt>
            <dd>{#$user->uzWarnings}</dd>
        {else}
            <dt>{lang}wcf.user.uzwarning.warnings{/lang}</dt>
            <dd>{#$user->uzWarnings}</dd>
        {/if}
    {/if}

    {if WARNING_POINTS_DISPLAY_USERINFORMATION && $__wcf->session->getPermission('user.profile.warning.canViewPoints')}
        {if $user->uzWarningPoints || $user->uzWarnings}
            {if $__wcf->session->getPermission('mod.infraction.warning.canWarn')}
                <dt><a href="{link controller='User' object=$user}{/link}#warning" class="jsTooltip" title="{lang user=$user}wcf.user.uzwarning.showWarnings{/lang}">{lang}wcf.user.uzwarning.warningPoints{/lang}</a></dt>
                <dd>{#$user->uzWarningPoints}</dd>
            {else}
                <dt>{lang}wcf.user.uzwarning.warningPoints{/lang}</dt>
                <dd>{#$user->uzWarningPoints}</dd>
            {/if}
        {/if}
    {/if}
{/if}
