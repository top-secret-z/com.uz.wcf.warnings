<ul class="sidebarItemList">
    {foreach from=$warnings item=$warning}
        {assign var=userProfile value=$warning[profile]}
        {assign var=time value=$warning[time]}

        <li class="box32">
            <a href="{link controller='User' object=$userProfile}{/link}" aria-hidden="true">{@$userProfile->getAvatar()->getImageTag(32)}</a>

            <div class="sidebarItemTitle">
                <h3><a href="{link controller='User' object=$userProfile}{/link}" class="userLink" data-user-id="{@$userProfile->userID}">{$userProfile->username}</a></h3>
                <small>{@$time|time}</small>

                {event name='userData'}
            </div>
        </li>
    {/foreach}
</ul>
