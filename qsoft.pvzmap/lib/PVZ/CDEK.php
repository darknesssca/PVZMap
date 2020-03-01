<?php


namespace Qsoft\Pvzmap\PVZ;

use Qsoft\Pvzmap\iPvz;

class CDEK implements iPvz
{
    private function loadData()
    {
        //Получение данных вернуть ответ если нет или выдать ошибку

        $token = $this->getToken();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.edu.cdek.ru/v2/offices');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        return !empty($response) ? $response : false;
    }

    private function prepareData()
    {
        //Подготовка данных к нужному формату удаление ненужной инфы. Вернуть json.

        $arPVZ = json_decode($this->loadData(), JSON_OBJECT_AS_ARRAY);
        $arReturn = [];

        foreach ($arPVZ['pvz'] as $pvz) {
            $city = strtoupper($pvz['city']);

            if (strpos($city, ',') || strpos($city, '(')) {
                preg_match('/(.+)(\s\(|,)/U', $city, $matches);
                $city = $matches[1];
            }

            $arReturn[$city][] = $pvz;
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

    private function getToken()
    {
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            'http://api.edu.cdek.ru/v2/oauth/token'
        );
        curl_setopt($ch, CURLOPT_POST, true);

        //TODO Сделать передачу через параметры
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            "grant_type=" . "client_credentials"
            ."&client_id=" . "z9GRRu7FxmO53CQ9cFfI6qiy32wpfTkd"
            ."&client_secret=" . "w24JTCv4MnAcuRTx0oHjHLDtyt3I6IBq"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        $arToken = json_decode($response, JSON_OBJECT_AS_ARRAY);

        return $arToken['access_token'];
    }
}
