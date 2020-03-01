<div class="widget__popup-mask" style="display: none;">
    <div class="widget__popup">
        <a class="widget__popup__close-btn" onclick="pvzmap.close();"></a>
        <div id="pvzWidget">
            <div id="pvzMap"></div>
            <div class="pvz_sidebar">
                <ul class="sidebar-menu">
                    <li>
                        <div class="sidebar-burger close">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="pvz_panel panel_hidden">
                <div class="panel_header"><span></span></div>
                <div class="panel_content">
                    <div class="panel_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden_block">
    <div id="filter_block">
        <form class="filter_form">
            <div class="panel-list__item">
                <p class="panel-list__item-name">Оплата картой</p>
                <p class="panel-list__item-chebox-list">
                    <label>
                        <input type="radio" name="havecashless" value="2" checked> Все
                    </label><br>
                    <label>
                        <input type="radio" name="havecashless" value="1"> Есть
                    </label><br>
                    <label>
                        <input type="radio" name="havecashless" value="0"> Нет
                    </label>
                </p>
            </div>

            <div class="panel-list__item">
                <p class="panel-list__item-name">Примерка</p>
                <p class="panel-list__item-chebox-list">
                    <label>
                        <input type="radio" name="isdressingroom" value="2" checked> Все
                    </label><br>
                    <label>
                        <input type="radio" name="isdressingroom" value="1"> Есть
                    </label><br>
                    <label>
                        <input type="radio" name="isdressingroom" value="0"> Нет
                    </label>
                </p>
            </div>

            <div class="panel-list__item">
                <p class="panel-list__item-name">Сервисы</p>
                <p class="panel-list__item-chebox-list">
                    <? foreach ($arResult['PVZ']['CLASS_MAP'] as $class_name => $name) : ?>
                        <label>
                            <input type="checkbox" name="pvz" value="<?=$class_name?>" checked><?=$name?>
                        </label><br>
                    <? endforeach; ?>
                </p>
            </div>
        </form>
        <button id="pvz_filter" onclick="pvzmap.changeFilteredPoints();">Применить фильтр</button>
    </div>

    <div id="pvz_item">
        <div class="panel-details__block">
            <p class="panel-details__block-head">Адрес пункта выдачи заказов:</p>
            <p class="panel-details__block-text"></p>
        </div>

        <div class="panel-details__block">
            <p class="panel-details__block-head">Время работы:</p>
            <p class="panel-details__block-text"></p>
        </div>

        <div class="panel-details__block">
            <button class="widget__choose" data-label="Выбрать" onclick="panel.choose(this);">Выбрать</button>
        </div>
    </div>
</div>

<script>
    window.pvzObj = <?=CUtil::PhpToJSObject($arResult['PVZ'])?>;

    window.onload = function () {
        let script = document.createElement("script");
        script.src = 'https://api-maps.yandex.ru/2.1/?apikey=ab43734d-eaa7-46b7-b203-b6c5d32acc8c&lang=ru_RU&onload=pvzmap.init&load=package.standard';
        document.getElementsByTagName("head")[0].appendChild(script);

    }
</script>

<script async src="/local/components/qsoft/pvzmap/templates/.default/pvzmap.js"></script>