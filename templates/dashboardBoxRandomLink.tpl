<script data-relocate="true">
//<![CDATA[
		$(function() {
		$('.slideshowContainer').wcfSlideshow();
		});
		//]]>
</script>

<div class="container marginTop shadow slideshowContainer" data-type="de.codequake.linklist.link">
	<ul class="linklist containerList" data-type="de.codequake.linklist.link">
			{foreach from=$randomLinks item=randomLink}
	<li id="link{$randomLink->linkID}" class=" link {if $randomLink->isDisabled}linkDisabled{/if}"  {if $randomLink->isDisabled}data-is-active="0"{/if}>
				<div class="box128">
		<div style="height: 128px; width: 128px;">
		<a class="framed" href="{link application='linklist' controller='LinkVisit' object=$randomLink}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>{@$randomLink->getImage(128)}</a>
		</div>

		<div class="details">
		<div class="containerHeadline">
			<h3>
			<a data-link-id="{@$randomLink->linkID}" class="linklistLink messageGroupLink framed" href="{link application='linklist' controller='Link' id=$randomLink->linkID title=$randomLink->subject}{/link}">{$randomLink->subject}</a>
			{if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike') && $randomLink->likes || $randomLink->dislikes && LINKLIST_ENABLE_LIKES}<span class="likesBadge badge jsTooltip {if $randomLink->cumulativeLikes > 0}green{elseif $randomLink->cumulativeLikes < 0}red{/if}" title="{lang likes=$randomLink->likes dislikes=$randomLink->dislikes}wcf.like.tooltip{/lang}">{if $randomLink->cumulativeLikes > 0}+{elseif $randomLink->cumulativeLikes == 0}&plusmn;{/if}{#$randomLink->cumulativeLikes}
			</span>{/if}
			</h3>
		</div>
		<dl class="plain inlineDataList">
			<dt>{lang}linklist.link.author{/lang}</dt>
			<dd>
			{if $randomLink->getUserProfile()->userID != 0}<a class="userLink" data-user-id="{$randomLink->userID}" href="{link controller='User' object=$randomLink->getUserProfile()}{/link}">{$randomLink->username}</a>{else}{$randomLink->username}{/if} ({$randomLink->time|DateDiff})
			</dd>
		</dl>
		<dl class="plain inlineDataList">
			<dt>{lang}linklist.links.visits{/lang}</dt>
			<dd>{$randomLink->visits}</dd>
		</dl>
		<div>{@$randomLink->getExcerpt()}</div>
		{if $randomLink->getTags()|count && MODULE_TAGGING && LINKLIST_ENABLE_TAGS}
		<ul class="tagList">
			{foreach from=$randomLink->getTags() item=tag}
			<li>
			<a href="{link controller='Tagged' object=$tag}objectType=de.codequake.linklist.link{/link}" class="badge tag jsTooltip" title="{lang}wcf.tagging.taggedObjects.de.codequake.linklist.link{/lang}">{$tag->name}</a>
			</li>
			{/foreach}
		</ul>
		{/if}
		<nav class="buttonGroupNavigation">
			<ul class="buttonList" data-link-id="{$randomLink->linkID}">
			{if $randomLink->isDeleted}<li>
				<span class="icon icon16 icon-trash" title="{lang}linklist.link.deleted{/lang}"></span>
			</li>{/if}
			{if !$randomLink->isActive}<li>
				<span class="icon icon16 icon-off" title="{lang}linklist.link.disabled{/lang}"></span>
			</li>{/if}
			</ul>
		</nav>
		<nav class="jsMobileNavigation buttonGroupNavigation linkNavigation">
			<ul class="buttonGroup smallButtons">
			<li style="margin-right: 35px;">
				<a class="button" href="{link application='linklist' controller='LinkVisit' object=$randomLink}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>
				<span class="icon-link icon icon16"></span>
				<span>{lang}linklist.link.visit{/lang}</span>
				</a>
			</li>
			</ul>
		</nav>
		</div>
	</div>
			</li>
	{/foreach}
	</ul>
</div>
