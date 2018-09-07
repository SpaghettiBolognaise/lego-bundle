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

use DateTime;

use Symfony\Component\HttpFoundation\Request;

/**
 * DateFilterType
 */
class DateFilterType extends AbstractORMFilterType
{

    public function __construct($columnName, $config = [], $alias = 'b')
    {
        parent::__construct($columnName, $config, $alias);
        $this->yearRange = (isset($config['yearRange']))? $config['yearRange']:null;
    }

    /**
     * @param Request $request  The request
     * @param array   &$data    The data
     * @param string  $uniqueId The unique identifier
     */
    public function bindRequest(array &$data, $uniqueId)
    {
        $data['comparator'] = $this->getValueSession('filter_comparator_' . $uniqueId);
        $data['value']      = $this->getValueSession('filter_value_' . $uniqueId);
        return ($data['value'] != '');
    }

    /**
     * @param array  $data     The data
     * @param string $uniqueId The unique identifier
     */
    public function apply(array $data, $uniqueId,$alias,$col)
    {
        if (isset($data['value']) && isset($data['comparator'])) {
            $date = DateTime::createFromFormat('d/m/Y', $data['value']);
            switch ($data['comparator']) {
                case 'equal':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->lte($alias.$col, ':varlte_' . $uniqueId));
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->gte($alias.$col, ':var_' . $uniqueId));
                    $this->queryBuilder->setParameter('varlte_' . $uniqueId, $date->format('Y-m-d'));
                    break;
                case 'before':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->lte($alias.$col, ':var_' . $uniqueId));
                    break;
                case 'after':
                    $this->queryBuilder->andWhere($this->queryBuilder->expr()->gt($alias.$col, ':var_' . $uniqueId));
                    break;
            }
            $this->queryBuilder->setParameter('var_' . $uniqueId, $date->format('Y-m-d'));
        }
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return '@IdkLego/FilterType/dateFilter.html.twig';
    }

    public function getDatePickerOptions(){
        $options = [];
        $options['changeMonth'] =  true;
        $options['changeYear'] = true;
        $options['dateFormat'] = 'dd/mm/yy';
        if($this->yearRange) $options['yearRange'] = $this->yearRange;
        return $options;

    }
}
