<?php

namespace App\Services;

class ProductsCleaningService
{
    public function clean($data)
    {
        array_walk_recursive($data, function (&$item, $key) use (&$data) {
            if (in_array($item, ['N/A', '-', '', null], true)) {
                $item = 'REMOVE_KEY'; // Mark for removal
            }
        });

        $data = $this->removeMarkedKeys($data); // Remove marked keys
        $this->addNullableToTitle($data);

        return $data;
    }

    protected function removeMarkedKeys($data)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $value = $this->removeMarkedKeys($value);
            }
            if ($value === 'REMOVE_KEY') {
                unset($data[$key]);
            }
        }
        return $data;
    }

    protected function addNullableToTitle(&$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $found = false;
                foreach ($value as $item) {
                    if (in_array($item, ['N/A', '-', '', null], true)) {
                        $found = true;
                        break;
                    }
                }
                if ($found && isset($value['title'])) {
                    $value['title'] .= " nullable";
                }
                $this->addNullableToTitle($value);
            }
        }
    }
}
