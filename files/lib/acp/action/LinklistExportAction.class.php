<?php
namespace linklist\acp\action;
use wcf\action\AbstractAction;
use wcf\data\object\type\ObjectTypeCache;
use linklist\data\link\LinkList;
use wcf\util\XMLWriter;
use wcf\util\StringUtil;
use wcf\system\io\TarWriter;

class LinklistExportAction extends AbstractAction{
    public $data = array();
    public $neededPermissions = array('admin.linklist.data.canImportData');
    public $filename;
    
    public function readParameters(){
        parent::readParameters();
        //categories
        $objectTypeID = ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.category', 'de.codequake.linklist.category');
        $sql = "SELECT * FROM wcf".WCF_N."_category WHERE objectTypeID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array($objectTypeID));
        $i = 0;
        while($row = $statement->fetchArray()){
            $data['categoryData'][$i]['categoryID'] = $row['categoryID'];
            $data['categoryData'][$i]['parentID'] = $row['parentCategoryID'];
            $data['categoryData'][$i]['description'] = $row['description'];
            $data['categoryData'][$i]['title'] = $row['title'];
            $data['categoryData'][$i]['isDisabled'] = 0;
            $i++;
        }
        
        //links
        $list = new Linklist();
        $list->readObjects();
        $i = 0;
        foreach($list->getObjects() as $item){
            $data['linkData'][$i]['linkID'] = $item->linkID;
            $data['linkData'][$i]['subject'] = $item->getTitle();
            $data['linkData'][$i]['url'] = $item->url;
            $data['linkData'][$i]['categoryID'] = $item->categoryID;
            $data['linkData'][$i]['message'] = $item->message;
            $data['linkData'][$i]['userID'] = $item->userID;
            $data['linkData'][$i]['username'] = $item->username;
            $data['linkData'][$i]['time'] = $item->time;
            $data['linkData'][$i]['languageID'] = $item->languageID;
            $data['linkData'][$i]['enableSmilies'] = $item->enableSmilies;
            $data['linkData'][$i]['enableBBCodes'] = $item->enableBBCodes;
            $data['linkData'][$i]['enableHtml'] = $item->enableHtml;
            $data['linkData'][$i]['visits'] = $item->visits;
            $data['linkData'][$i]['ipAddress'] = $item->ipAddress;
            $i++;
        }

    }
    
    protected function buildXML(){
        $xml = new XMLWriter();
        $xml->beginDocument();
        $xml->startElement('data');
        foreach($data['categoryData'] as $cat){
            $xml->startElement('linkListCategory');
            $xml->writeElement('categoryID', $cat['categoryID']);
            $xml->writeElement('parentID', $cat['parentID']);
            $xml->writeElement('title', $cat['title']);
            $xml->writeElement('description', $cat['description']);
            $xml->endElement();
        }
        foreach($data['linkData'] as $link){
            $xml->startElement('linkListLink');
            $xml->writeElement('linkID', $link['linkID']);
            $xml->writeElement('categoryID', $link['categoryID']);            
            $xml->writeElement('subject', $link['subject']);            
            $xml->writeElement('message', $link['message']);
            $xml->writeElement('isDisabled', $link['isDisabled']);
            $xml->writeElement('userID', $link['userID']);
            $xml->writeElement('username', $link['username']);
            $xml->writeElement('url', $link['url']);
            $xml->writeElement('time', $link['time']);
            $xml->writeElement('visits', $link['visits']);
            $xml->writeElement('enableSmilies', $link['enableSmilies']);
            $xml->writeElement('enableBBCodes', $link['enableBBCodes']);
            $xml->writeElement('enableHtml', $link['enableHtml']);            
            $xml->writeElement('ipAddress', $link['ipAddress']);
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument(LINKLIST_DIR.'tmp/linkListData.xml');
    }
    
    protected function tar(){
        $this->filename = LINKLIST_DIR.'tmp/linkListData-Export.'.StringUtil::getRandomID().'.gz';
        $tar = new TarWriter($this->filename, true);        
        $this->buildXML();
        $tar->add(LINKLIST_DIR.'tmp/linkListData.xml','', LINKLIST_DIR.'tmp/');
        $tar->create();
        @unlink(LINKLIST_DIR.'tmp/linkListData.xml');
    }
    
    public function execute(){
        parent::execute();
        $this->tar();
        $this->executed();
		// headers for downloading file
		header('Content-Type: application/x-gzip; charset='.CHARSET);
		header('Content-Disposition: attachment; filename="LinkListData-Export.tar.gz"');
		readfile($this->filename);
		// delete temp file
		@unlink($this->filename);
    }
}
