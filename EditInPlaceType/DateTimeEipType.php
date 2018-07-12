<?php
/**
 *  This file is part of the Lego project.
 *
 *   (c) Joris Saenger <joris.saenger@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Idk\LegoBundle\EditInPlaceType;

use Idk\LegoBundle\Action\EditInPlaceAction;
use Symfony\Component\HttpFoundation\Request;

class DateTimeEipType extends AbstractEipType{


    public function __construct(){

    }

    public function getTemplate(){
        return 'IdkLegoBundle:EditInPlaceType:_datetime.html.twig';
    }

    public function formatValue($value){
        return $value->format('d/m/Y H:i');
    }

    public function getValueFromAction(Request $request, EditInPlaceAction $action)
    {
        $value = $request->request->get('value');
        if($value != ''){
            $value = \DateTime::createFromFormat('d/m/Y H:i',$request->request->get('value'));
        } else {
            $value = null;
        }
        return $value;
    }
}
