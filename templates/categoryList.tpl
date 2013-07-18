<div>
  <ul class="linklistCategoryList">
    {foreach from=$categoryList item=categoryItem}
    <li class="linklistCategoryContainer container linklistNodeTop" data-category-id="{@$categoryItem->categoryID}">
      <div class="linklistCategoryNode1 linklistCategory box32">
        <span class="icon icon32 icon-globe"></span>
        <div>
          <div class="containerHeadline">
            <h1>
              <a href="{link application='linklist' controller='Category' id=$categoryItem->categoryID title=$categoryItem->getTitle()|language}{/link}">{$categoryItem->getTitle()}</a>
            </h1>
            {hascontent}
            <h2 class="linklistCategoryDescription">
              {content}{$categoryItem->description|language}{/content}
            </h2>
            {/hascontent}
            
            {if $categoryItem->hasChildren()}
            <ul class="subCategory">
              {implode from=$categoryItem->getChildCategories() item=subCategoryItem}
              <li data-category-id="{@$subCategoryItem->categoryID}">
                <span class="icon icon16 icon-globe"></span>
                <a href="{link application='linklist' controller='Category' id=$subCategoryItem->categoryID title=$subCategoryItem->title|language}{/link}">{$subCategoryItem->title|language}</a>

              </li>
              {/implode}
            </ul>
            {/if}
          </div>
            <div class="linkStats">
              <dl class="statsDataList plain">
                <dt>{lang}linklist.links.list{/lang}</dt>
                <dd>{$categoryItem->getLinks()}</dd>
                <dt>{lang}linklist.links.visits{/lang}</dt>
                <dd>{$categoryItem->getVisits()}</dd>
              </dl>
            </div>
        </div>
      </div>
    </li>
    {/foreach}
  </ul>
</div>