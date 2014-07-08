<?php
namespace linklist\system\user\activity\event;

use linklist\data\link\LinkList;
use wcf\data\comment\response\CommentResponseList;
use wcf\data\comment\CommentList;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\data\user\User;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class LinkCommentResponseUserActivityEvent extends SingletonFactory implements IUserActivityEvent {

	public function prepare(array $events) {
		$objectIDs = array();
		foreach ($events as $event) {
			$objectIDs[] = $event->objectID;
		}
		
		// comments responses
		$responseList = new CommentResponseList();
		$responseList->getConditionBuilder()->add("comment_response.responseID IN (?)", array(
			$objectIDs
		));
		$responseList->readObjects();
		$responses = $responseList->getObjects();
		
		// comments
		$commentIDs = array();
		foreach ($responses as $response) {
			$commentIDs[] = $response->commentID;
		}
		$commentList = new CommentList();
		$commentList->getConditionBuilder()->add("comment.commentID IN (?)", array(
			$commentIDs
		));
		$commentList->readObjects();
		$comments = $commentList->getObjects();
		
		// get links
		$linkIDs = array();
		foreach ($comments as $comment) {
			$linkIDs[] = $comment->objectID;
		}
		
		$linkList = new LinkList();
		$linkList->getConditionBuilder()->add("link.linkID IN (?)", array(
			$linkIDs
		));
		$linkList->readObjects();
		$links = $linkList->getObjects();
		
		foreach ($events as $event) {
			if (isset($responses[$event->objectID])) {
				$response = $responses[$event->objectID];
				if (isset($comments[$response->commentID])) {
					$comment = $comments[$response->commentID];
					if (isset($links[$comment->objectID])) {
						$text = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.recentActivity.linkCommentResponse', array(
							'author' => new User($comment->userID),
							'link' => $links[$comment->objectID]
						));
						$event->setTitle($text);
						$event->setDescription($response->getFormattedMessage());
						$event->setIsAccessible();
					}
				}
			} else {
				$event->setIsOrphaned();
			}
		}
	}
}
