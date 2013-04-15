<?php
namespace linklist\acp\form;

use wcf\acp\form\AbstractCategoryAddForm;

class LinklistCategoryAddForm extends AbstractCategoryAddForm {
    /**
    * @see wcf\acp\form\ACPForm::$activeMenuItem
    */
    public $activeMenuItem = 'linklist.acp.menu.link.linklist.category.add';

    /**
    * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
    */
    public $objectTypeName = 'de.codequake.linklist.category';

    /**
    * @see wcf\acp\form\AbstractCategoryAddForm::$title
    */
    public $title = 'linklist.acp.category.add';
}