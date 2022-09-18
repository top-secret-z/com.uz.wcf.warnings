<ul class="sidebarItemList">
    {foreach from=$userProfiles item=userProfile}
        <li class="box32">
            <a href="{link controller='User' object=$userProfile}{/link}" aria-hidden="true">{@$userProfile->getAvatar()->getImageTag(32)}</a>

            <div class="sidebarItemTitle">
                <h3><a href="{link controller='User' object=$userProfile}{/link}" class="userLink" data-user-id="{@$userProfile->userID}">{$userProfile->username}</a></h3>
                <small>{lang}wcf.user.uzwarning.userCount{/lang}</small>

                {event name='userData'}
            </div>
        </li>
    {/foreach}
</ul>

{if !WARNING_DISPLAY_BOX_HIDE_ALL && $userProfiles|count >= WARNING_DISPLAY_BOX_ENTRIES}
    <a class="jsMostWarnedMembers button small more jsOnly">{lang}wcf.global.button.showAll{/lang}</a>

    <script data-relocate="true">
        $(function() {
            var $mostWarnedMembers = null;
            $('.jsMostWarnedMembers').click(function() {
                if ($mostWarnedMembers === null) {
                    $mostWarnedMembers = new WCF.User.List('wcf\\data\\user\\UserWarningListAction', '{lang}wcf.user.uzwarning.warnedMembers{/lang}');
                }
                $mostWarnedMembers.open();
            });
        });
    </script>
{/if}
