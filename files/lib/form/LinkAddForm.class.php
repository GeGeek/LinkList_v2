<?php
namespace linklist\form;

use linklist\data\category\LinklistCategoryNodeList;
use linklist\data\category\LinklistCategory;
use linklist\data\link\LinkAction;

use wcf\system\WCF;
use wcf\system\category\CategoryHandler;
use wcf\form\MessageForm;
use wcf\util\StringUtil;
use wcf\util\HeaderUtil;
use wcf\system\request\LinkHandler;
use wcf\system\language\LanguageFactory;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\IllegalLinkException;

/**
 * @author  Jens Krumsieck
 * @copyright   2013 codeQuake
 * @license GNU Lesser General Public License
 * @pakage  de.codequake.linklist
 */
 
class LinkAddForm extends MessageForm{

    public $action = 'add';
    public $templateName = 'linkAdd';
    public $userID = 0;
    public $categoryID = 0;
    public $category = null;
    public $categoryNodeList = null;
    
    public $enableMultilingualism = true;
    
    protected $link = null;
    
    public $objectTypeName = 'de.codequake.linklist.category';
    
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id']))        {
            $this->categoryID = intval($_GET['id']);
            $category = CategoryHandler::getInstance()->getCategory($this->categoryID);

            if($category !== null) $this->category = new LinklistCategory($category);

            if ($this->category === null || !$this->category->categoryID) {
                throw new IllegalLinkException();
            }
            $this->category->checkPermission(array('canViewCategory', 'canEnterCategory'));
        }
    }
    
    public function readData(){
        parent::readData();
        // multilingualism
        if (!empty($this->availableContentLanguages)) {
            if (!$this->languageID) {
            $language = LanguageFactory::getInstance()->getUserLanguage();
            $this->languageID = $language->languageID;
            }

            if (!isset($this->availableContentLanguages[$this->languageID])) {
                $languageIDs = array_keys($this->availableContentLanguages);
                $this->languageID = array_shift($languageIDs);
            }
        }
        // read categories
        $this->categoryNodeList = new LinklistCategoryNodeList($this->objectTypeName);
    }
    
    public function readFormParameters() {
        parent::readFormParameters();

        if(isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
        if(isset($_POST['category'])) $this->categoryID = intval($_POST['category']);
      }
      
    
    public function assignVariables(){
        parent::assignVariables();
        WCF::getTPL()->assign(array('categoryNodeList'  =>  $this->categoryNodeList,
                                    'categoryID'    =>  $this->categoryID,
                                    'username'  =>  $this->username));
    }
    
    public function save(){
        parent::save();
        
        $data = array(  'subject'   =>  $this->subject,
                        'categoryID'    =>  $this->categoryID,
                        'message'   =>  $this->message,
                        'userID'    =>  $this->userID,
                        'username'  =>  $this->username,
                        'time'  =>  TIME_NOW,
                        'languageID'    =>  $this->languageID,
                        'enableSmilies' =>  $this->enableSmilies,
                        'enableHtml'    =>  $this->enableHtml,
                        'enableBBCodes' =>  $this->enableBBCodes);
        $this->objectAction = new LinkAction(array(), 'create', $data);
        $resultvalues = $this->objectAction->executeAction();
        
        $this->link = $resultvalues['returnValues'];
        $this->saved();
        
        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Link', array(
                                                                'application' => 'linklist',
                                                                'object' => $this->link
                                                                )));
        exit;
    }
    
    
}