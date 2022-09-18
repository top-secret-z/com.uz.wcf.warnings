{if MODULE_USER_INFRACTION && $__wcf->session->getPermission('user.profile.warning.canView') && $warningStatistics|isset}
    <dt>{lang}wcf.user.uzwarning.warnings{/lang}</dt>
    <dd>{#$warningStatistics[count]}</dd>
{/if}
