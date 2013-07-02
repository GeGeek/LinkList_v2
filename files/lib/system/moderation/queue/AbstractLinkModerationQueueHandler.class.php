<?php
namespace linklist\system\moderation\queue;
use linklist\data\link\Link;
use linklist\data\link\LinkEditor;
use linklist\data\link\LinkList;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\moderation\queue\AbstractModerationQueueHandler;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\system\WCF;

abstract class AbstractLinkModerationQueueHandler extends AbstractModerationQueueHandler {

	protected static $links = array();

	public function assignQueues(array $queues) {
		$assignments = array();
		
		foreach ($queues as $queue) {
			$assignUser = 0;
			if (WCF::getSession()->getPermission('mod.linklist.link.canToggleLink')) {
				$assignUser = 1;
			}
				
			$assignments[$queue->queueID] = $assignUser;
		}
	
		ModerationQueueManager::getInstance()->setAssignment($assignments);
	}
	

	public function getContainerID($objectID) {
		return $this->getLink($objectID)->categoryID;
	}
	
	public function isValid($objectID) {
		if ($this->getLink($objectID) === null) {
			return false;
		}
		
		return true;
	}
	

	protected function getLink($objectID) {
		if (!array_key_exists($objectID, self::$links)) {
			self::$links[$objectID] = new Link($objectID);
			if (!self::$links[$objectID]->linkID) {
				self::$links[$objectID] = null;
			}
		}
		
		return self::$links[$objectID];
	}

	public function populate(array $queues) {
		$objectIDs = array();
		foreach ($queues as $object) {
			$objectIDs[] = $object->objectID;
		}
		
		$list = new LinkList();
		$list->getConditionBuilder()->add("link.linkID IN (?)", array($objectIDs));
		$list->readObjects();
		$links = $list->getObjects();

		foreach ($queues as $object) {
			if (isset($links[$object->objectID])) {
				$object->setAffectedObject($links[$object->objectID]);
			}
		}
	}

	public function removeContent(ModerationQueue $queue, $message) {
		$link = new Link($queue->objectID);
        $editor = new LinkEditor($link);
            $editor->update(array(
                'isDeleted' => 1,
                'deleteTime' => TIME_NOW
            ));
	}
}
