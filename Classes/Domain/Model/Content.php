<?php
namespace Unima\Publist4ubma2\Domain\Model;

/**
 * Content
 */
class Content extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

   /**
    * uid
    *
    * @var string
    */
   protected $uid = '';

   /**
    * pid
    *
    * @var string
    */
   protected $pid = '';


   /**
    * Gets the uid
    *
    * @return string $uid
    */
   public function getUid() {
      return $this->uid;
   }
   /**
    * Gets the pid
    *
    * @return string $pid
    */
   public function getPid() {
      return $this->pid;
   }



}
