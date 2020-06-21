<?php


class OxitBagliSecenekService
{

    public function getAllSituationService($parent_values, $child_values)
    {

        $option_value_data = array();

        for ($i = 0; $i < count($parent_values); $i++) {

            for ($j = 0; $j < count($child_values); $j++) {

                $option_value_data[] = array(


                    'parent_option_value_id' => $parent_values[$i]['option_value_id'],
                    'parent_option_name' => $parent_values[$i]['name'],
                    'child_option_value_id' => $child_values[$j]['option_value_id'],
                    'child_option_name' => $child_values[$j]['name'],

                );


            }

        }

        return $option_value_data;


    }


}




