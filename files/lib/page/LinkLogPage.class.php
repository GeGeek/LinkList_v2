<?php
namespace linklist\page;
use linklist\data\link\Link;
use wcf\page\SortablePage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;
use wcf\system\request\LinkHandler;


class LinkLogPage extends SortablePage{
    
    public $activeMenuItem = 'linklist.pageMenu.index';
    public $defaultSortField = 'time';
    public $defaultSortOrder = 'DESC';
    public $validSortFields = array('logID', 'time', 'username');
    public $linkID = 0;
    public $link = null;
    public $objectListClassName = 'linklist\data\modification\log\LinkModificationLogList';
    
    public function readParameters() {
        parent::readParameters();
        if (isset($_REQUEST['id'])) $this->linkID = intval($_REQUEST['id']);
		$this->link = new Link($this->link);
		if ($this->link === null) {
			throw new IllegalLinkException();
		}
		if (!$this->link->getCategory()->getPermission('canEditLink')) {
			throw new PermissionDeniedException();
		}
	}
    protected function initObjectList() {
		parent::initObjectList();
		
		$this->objectList->setLink($this->link);
	}
    
    public function readData() {
		parent::readData();
		WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.index.title'), LinkHandler::getInstance()->getLink('CategoryList',array('application' => 'linklist'))));
        foreach($this->link->getCategory()->getParentCategories()    as $categoryItem) {
                                  WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
                                      'application' => 'linklist',
                                      'object' => $categoryItem
          ))));
          }
         WCF::getBreadcrumbs()->add(new Breadcrumb($this->link->getCategory()->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'linklist',
          'object' => $this->link->getCategory()
            ))));
         WCF::getBreadcrumbs()->add(new Breadcrumb($this->link->getTitle(), LinkHandler::getInstance()->getLink('Link', array('application' => 'linklist', 'object' => $this->link)))); 
           
	}
    
    public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'link' => $this->link
		));
	}
}