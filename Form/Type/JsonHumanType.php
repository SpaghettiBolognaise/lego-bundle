<?php
namespace Idk\LegoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JsonHumanType extends AbstractType {


    public function getParent()
    {
        return TextareaType::class;
    }

    public function getName()
    {
        return 'lego_json_human';
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            "schema"=> [
                "type" => "object",
                "properties" => [
                    "name" => ["type"=>"string"]
                ]
            ],
            "disable_edit_json" => true,
            "no_additional_properties" => false,
            "required_by_default" => true,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options){
        $view->vars['schema'] = json_encode($options['schema']);
        $view->vars['disable_edit_json'] = ($options['disable_edit_json'])? 'true':'false';
        $view->vars['no_additional_properties'] = ($options['no_additional_properties'])? 'true':'false';
        $view->vars['required_by_default'] = ($options['required_by_default'])? 'true':'false';
    }


}