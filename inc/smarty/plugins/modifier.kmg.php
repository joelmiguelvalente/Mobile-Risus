<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty count_words modifier plugin
 *
 * Type:     modifier
 * Name:     KMG
 * @author   KMario19
 * @param string
 * @return integer
 */
/* MODIFIER_KMG */
   function smarty_modifier_kmg($number){
      $pre = 'KMG';
      if ($number >= 1000) {
         for ($i=-1; $number>=1000; ++$i) {
            $number /= 1000;
         }
         return round($number,1).$pre[$i];
      } else return $number;
   }

?>