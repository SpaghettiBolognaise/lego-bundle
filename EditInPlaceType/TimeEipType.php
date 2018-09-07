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

class TimeEipType extends AbstractEipType{


    public function __construct(){

    }

    public function getTemplate(){
        return '@IdkLego/EditInPlaceType/_time.html.twig';
    }

    public function formatValue($value){
        return $value->format('H:i');
    }

    public function getValueFromAction(Request $request, EditInPlaceAction $action)
    {
        $value = $request->request->get('value');
        if($value != ''){
            $value = \DateTime::createFromFormat('H:i',$request->request->get('value'));
        } else {
            $value = null;
        }
        return $value;
    }
}
