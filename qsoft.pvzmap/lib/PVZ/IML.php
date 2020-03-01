<?php


namespace Qsoft\Pvzmap\PVZ;

use Qsoft\Pvzmap\iPvz;

class IML implements iPvz
{
    /**
     * Получаем информацию со службы доставки
     * @return string|false
     */
    private function loadData()
    {
        $url = 'http://list.iml.ru/sd?type=json';
        $response = file_get_contents($url);
        echo 'lol';
        return !empty($response) ? $response : false;
    }

    private function prepareData()
    {
        //Подготовка данных к нужному формату удаление ненужной инфы. Вернуть array.
        $arPVZ = json_decode($this->loadData(), JSON_OBJECT_AS_ARRAY);
        $arReturn = [];

        foreach ($arPVZ as $pvz) {
            $arReturn[$pvz['RegionCode']][] = $pvz;
        }

        return $arReturn;
    }

    public function getData()
    {
        //Обертка  prepareData

        return json_encode($this->prepareData());
    }

    public function getArray()
    {
        return $this->prepareData();
    }

    public function getArrayByCity($city, $arPVZ)
    {
        $city = strtoupper($city);

        return $arPVZ[$city];
    }
}
