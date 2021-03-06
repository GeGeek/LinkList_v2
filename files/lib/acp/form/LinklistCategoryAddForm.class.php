<?php
namespace linklist\acp\form;

use wcf\acp\form\AbstractCategoryAddForm;
use wcf\system\WCF;
use wcf\util\StringUtil;

class LinklistCategoryAddForm extends AbstractCategoryAddForm {

	/**
	 *
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'linklist.acp.menu.link.linklist.category.add';

	/**
	 *
	 * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
	 */
	public $objectTypeName = 'de.codequake.linklist.category';

	/**
	 *
	 * @see wcf\acp\form\AbstractCategoryAddForm::$title
	 */
	public $title = 'linklist.acp.category.add';

	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST['isMainCategory'])) $this->additionalData['isMainCategory'] = intval($_POST['isMainCategory']);
		if (isset($_POST['icon'])) $this->additionalData['icon'] = StringUtil::trim($_POST['icon']);
	}

	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
			'isMainCategory' => isset($this->additionalData['isMainCategory']) ? $this->additionalData['isMainCategory'] : 0,
			'icon' => isset($this->additionalData['icon']) ? $this->additionalData['icon'] : ''
			));

	}
}
