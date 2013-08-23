{include file='documentHeader'}

<head>
	<title>{$category->getTitle()|language} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
		<script data-relocate="true" type="text/javascript">
			//<![CDATA[
				WCF.Clipboard.init('linklist\\page\\CategoryPage', {@$hasMarkedItems}, { });
			//]]>
		</script>
	<link rel="canonical" href="{link application='linklist' controller='Category' object=$category}{if $pageNo > 1}pageNo={@$pageNo}&{/if}sortField={@$sortField}&sortOrder={@$sortOrder}{/link}" />
</head>

<body id="tpl{$templateName|ucfirst}">
{capture assign='sidebar'}
	{include file='categoryDisplayOptions' application='linklist'}
	{@$__boxSidebar}
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">

		<h1>{$category->getTitle()|language}</h1>
		{hascontent}<h2>{content}{$category->description|language}{/content}</h2>{/hascontent}

</header>

<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>
{include file='categoryList' application='linklist'}

{include file='linksList' application='linklist'}

{include file='footer' sandbox=false}
</body>
</html>