<?php
namespace linklist\page;
use wcf\page\SortablePage;

use linklist\data\category\LinklistCategoryNodeTree;
use linklist\data\category\LinklistCategoryNode;
use linklist\data\category\LinklistCategory;
use wcf\system\WCF;
use wcf\system\category\CategoryHandler;
use wcf\system\request\LinkHandler;
use wcf\system\breadcrumb\Breadcrumb;
/**
 * Shows the category page.
 *
 * @author	Jens Krumsieck
 * @copyright	2013 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.linklist
 */
class CategoryPage extends SortablePage {
    /**
     * @see	wcf\page\AbstractPage::$enableTracking
     */
    public $enableTracking = true;
    public $categoryList = null;
    public $categoryID;    
    public $objectTypeName = 'de.codequake.linklist.category';
    public $category;
    public $objectListClassName = 'linklist\data\link\LinkList';
    public $defaultSortField = 'time';
    public $validSortFields = array('title', 'time', 'visits');
    
    
    protected function initObjectList() {
        parent::initObjectList();
         $this->objectList->sqlConditionJoins .= 'WHERE categoryID = '.$this->categoryID;         
         $this->objectList->sqlJoins .= 'WHERE categoryID = '.$this->categoryID;
        }
    /**
     * @see wcf\page\IPage::readParameters()
     */
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id'])) $this->categoryID = intval($_GET['id']);
    }
    
    /**
     * @see wcf\page\IPage::readData()
     */
    public function readData() {
        parent::readData();
        $categoryTree = new LinklistCategoryNodeTree($this->objectTypeName, $this->categoryID);
        $this->categoryList = $categoryTree->getIterator();
        $this->categoryList->setMaxDepth(0);
        $category= CategoryHandler::getInstance()->getCategory($this->categoryID);
        if($category !== null) $this->category = new LinklistCategory($category);
        if($this->category === null) throw new IllegalLinkException();
        WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.page.index'), LinkHandler::getInstance()->getLink('Index')));
  }
    /**
     * @see wcf\page\IPage::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();


        WCF::getTPL()->assign(array(
            'categoryList' => $this->categoryList,
            'categoryID' => $this->categoryID,
            'category' => $this->category,
            'allowSpidersToIndexThisPage'   =>  true
        ));
    }
}
