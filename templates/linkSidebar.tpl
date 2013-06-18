{capture assign='sidebar'}
        <aside class="sidebar">
		<div>
			<fieldset>
			<legend class="invisible">{lang}linklist.link.sidebar.image{/lang}</legend>
			<div class="userAvatar">
				<a class="framed" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><img src="http://api.webthumbnail.org?width=150&height=150&screen=1280&format=png&url={$link->url}" alt="Captured by webthumbnail.org" /></a>
			</div>
		</fieldset>
            <fieldset class="linklistLinkSidebar">
                <legend>{lang}linklist.link.sidebar.info{/lang}</legend>
                <div>
                    <ul class="sidebarBoxList">
                        <li class="box24" style="margin-top: 20px;">
                            <div class="sidebarBoxHeadline">
                                <h1>{lang}linklist.link.sidebar.title{/lang}</h1>
                                <h2><small>{$link->getTitle()|language}</small></h2>
                            </div>
                        </li>
                        <li class="box24" style="margin-top: 20px;">
                            <div class="sidebarBoxHeadline">
                                <h1>{lang}linklist.link.sidebar.author{/lang}</h1>
                                <h2><small>{if $link->getUserID()}<a href="{link controller='User' id=$link->getUserID() title=$link->getUsername()}{/link}">{$link->getUsername()}</a>{else}{$link->getUsername()}{/if}</small></h2>
                            </div>
                        </li>
                        <li class="box24" style="margin-top: 20px;">
                            <div class="sidebarBoxHeadline">
                                <h1>{lang}linklist.link.sidebar.category{/lang}</h1>
                                <h2><small><a href="{link application='linklist' controller='Category' object=$link->getCategory()}{/link}">{$link->getCategory()->getTitle()|language}</a></small></h2>
                            </div>
                        </li>
                        <li class="box24"  style="margin-top: 20px;">
                            <div class="sidebarBoxHeadline">
                                <h1>{lang}linklist.link.sidebar.visits{/lang}</h1>
                                <h2><small>{$link->visits}</small></h2>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </fieldset>

            <fieldset class="LinklistSidebarButton">
                    <legend></legend>
                <div>
                    <a class="button" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><h1 style="font-size:120%;">{lang}linklist.link.sidebar.visit{/lang}</h1></a>
                </div>
            </fieldset>
		</div>
        </aside>
    {/capture}