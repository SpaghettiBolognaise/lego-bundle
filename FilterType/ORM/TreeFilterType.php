<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\FilterType\ORM;


/**
 * StringFilterType
 */
class TreeFilterType extends EntityFilterType
{
    protected $startLevel;

     /**
     * @param string $columnName The column name
     * @param string $alias      The alias
     */
    public function load($columnName, $config = array(), $alias = 'b')
    {
        if(!isset($config['method'])) $config['method'] = 'getChildren';
        parent::load($columnName,$config,$alias);
        if(!isset($config['start_level'])) $config['start_level'] = 1;
        $this->startLevel = $config['start_level'];
    }


    /**
     * @param array  $data     The data
     * @param string $uniqueId The unique identifier
     */
    public function apply(array $data, $uniqueId,$alias,$col)
    {   
        if (isset($data['value'])) {
            if($this->getMultiple()){
              $nodes = $this->em->getRepository($this->table)->findById($data['value']);
              $ids = array();
              foreach($nodes as $node){
                $ids[] = $node;
                $children = $this->em->getRepository($this->table)->children($node,false);
                foreach($children as $child) $ids[] = $child->getId();
              }

            }else{
              $node = $this->em->getRepository($this->table)->find($data['value']);
              $children = $this->em->getRepository($this->table)->children($node,false);
              $ids = array($node);
              foreach($children as $child){
                  $ids[] = $child->getId();
              }
            }
            $this->queryBuilder->andWhere($this->queryBuilder->expr()->in($alias . $col, $ids));
        }
    }

    public function getEntities($data){
        $em = $this->em; 
        $m = $this->method;
        $elements = $em->getRepository($this->table)->$m();
        return $elements;
    }

    public function getValueEntity($entity){
      $return = null;
      if($this->display($entity)){
        for($i = $this->startLevel; $i < $entity->getLvl();$i++) $return .= '-';
        return $return.$entity->__toString();
      }
    }

    public function display($entity){
      return ($entity->getLvl() >= $this->startLevel);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return '@IdkLego/FilterType/treeFilter.html.twig';
    }
}
