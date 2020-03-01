<?php


namespace Qsoft\Pvzmap;

//Название лучше нужно придумать
class PVZFactory
{
    private static $namespace = 'Qsoft\\Pvzmap\\PVZ\\';

    /**
     * Возвращает json определенной службы доставки для использования в js.
     * При передаче доп. аргумента возвращает массив для обработки.
     * @param $class_name string
     * @param bool $return_array
     * @return string
     */
    public static function getPVZ($class_name, $return_array = false)
    {
        /** @var iPvz $class */
        $class_name = self::$namespace . $class_name; //Пример new Qsoft\\Pvzmap\\PVZ\\CDEK()
        $class = new $class_name();
        if ($return_array === true) {
            $return = $class->getArray();
        } else {
            $return = $class->getData();
        }
        return $return;
    }

    /**
     * Возвращает json всех доступных служб доставки для текущего заказа для использования в js
     * @return string|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getPVZCollection()
    {
        $arPVZ = self::loadPVZ();

        if (empty($arPVZ)) {
            return false;
        }

        $arReturn['CLASS_MAP'] = self::getClassMap($arPVZ);

        foreach ($arReturn['CLASS_MAP'] as $class_name => $name) {
            $arReturn['PVZ'][$class_name] = self::getPVZ($class_name, true);
        }

        return $arReturn;
    }


    public static function getPVZCollectionByCity($city, $arPVZ)
    {
        /** @var iPvz $class */
        foreach ($arPVZ as $class_name => $pvz) {
            $full_class_name = self::$namespace . $class_name; //Пример new Qsoft\\Pvzmap\\PVZ\\CDEK()
            $class = new $full_class_name();
            $arReturn[$class_name] = $class->getArrayByCity($city, $pvz);
        }

        return $arReturn;
    }

    /**
     * Получает доступные службы доставки
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private static function loadPVZ()
    {
        return PVZTable::getList([
            'filter' => ['ACTIVE' => 'Y']
        ])->fetchAll();
    }

    /**
     * Возвращает массив для построения меню на карте. Необходимо передать в js.
     * @param $arPVZ
     * @return array
     */
    private static function getClassMap($arPVZ)
    {
        $arReturn = [];
        foreach ($arPVZ as $pvz) {
            $arReturn[$pvz['CLASS_NAME']] = $pvz['NAME'];
        }
        return $arReturn;
    }


    //Нужна логика привязки к службам доставки
}
