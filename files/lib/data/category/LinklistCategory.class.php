<?php
namespace linklist\data\category;
use wcf\data\category\ViewableCategory;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\WCF;

/** 
 * Represents a category
 *
 * @author  Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser Generel Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
 
class LinklistCategory extends ViewableCategory{
     public static $objectTypeName = 'de.codequake.linklist.category';
     
     public function getPermission($permission = 'canViewCategory') {
        if ($this->permissions === null) {
            $this->permissions = CategoryPermissionHandler::getInstance()->getPermissions($this->getDecoratedObject());
        }

        if (isset($this->permissions[$permission])) {
            return $this->permissions[$permission];
        }

        return WCF::getSession()->getPermission('user.linklist.category.read.'.$permission) || WCF::getSession()->getPermission('user.linklist.category.write.'.$permission) || WCF::getSession()->getPermission('user.linklist.link.read.'.$permission) || WCF::getSession()->getPermission('user.linklist.link.write.'.$permission);
        }
        
     public function checkPermission(array $permissions = array('canViewCategory')) {
        foreach ($permissions as $permission) {
            if (!$this->getPermission($permission)) {
            throw new PermissionDeniedException();
            }
        }
    }
     
    public static function getAccessibleCategoryIDs($permissions = array('canViewCategory', 'canEnterCategory')) {
        $categoryIDs = array();
        foreach (CategoryHandler::getInstance()->getCategories(static::$objectTypeName) as $category) {
            $result = true;
            $category = new LinklistCategory($category);
            foreach ($permissions as $permission) {
                $result = $result && $category->getPermission($permission);
            }

            if ($result) {
                $categoryIDs[] = $category->categoryID;
            }
            return $categoryIDs;
        }
    }
    
    public function isAccessible() {
        return $this->getPermission('canViewCategory') && $this->getPermission('canEnterCategory');
        }
    }