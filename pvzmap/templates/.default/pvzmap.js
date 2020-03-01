filtered_form = {
    filter_button: document.getElementById('pvz_filter'),
    pvz: [],

    setFilterParams: function () {
        filtered_form.filter_form =  document.getElementsByClassName('filter_form')[0],

        filtered_form.filter_form.elements.havecashless.forEach(function (item) {
            if (item.checked) {
                filtered_form.havecashless = item.value;
            }
        });

        filtered_form.filter_form.elements.isdressingroom.forEach(function (item) {
            if (item.checked) {
                filtered_form.isdressingroom = item.value;
            }
        });

        filtered_form.pvz = [];
        filtered_form.filter_form.elements.pvz.forEach(function (item) {
            if (item.checked) {
                filtered_form.pvz.push(item.value);
            }
        });

        return [filtered_form.pvz, filtered_form.havecashless, filtered_form.isdressingroom];
    },

};

window.pvzmap = {
    pvzClasses: Object.keys(pvzObj.CLASS_MAP),

    init: function () {
        this.map = new ymaps.Map("pvzMap", {
            center: [pvzObj.CENTER.LATITUDE, pvzObj.CENTER.LONGITUDE],
            controls: ['zoomControl'],
            zoom: 10
        });

        setTimeout(this.addPoints(), 5000);
    },

    addPoints: function () {
        this.pvzClasses.forEach(function (name) {
            pvzmap.map.geoObjects.add(window[name].createPoints());
        });
    },

    changeFilteredPoints: function () {
        let params;
        params = filtered_form.setFilterParams();
        console.log(params);
        pvzmap.map.geoObjects.removeAll();
        params[0].forEach(function (name) {
            pvzmap.map.geoObjects.add(window[name].changeFilteredPoints(params[1], params[2]));
        });
    },

    show: function () {
        let widget;
        widget = document.getElementsByClassName('widget__popup-mask')[0];
        widget.style.display = 'block';
    },

    close: function () {
        let widget;
        widget = document.getElementsByClassName('widget__popup-mask')[0];
        widget.style.display = 'none';
    }
};

window.CDEK = {
    pvzlist: pvzObj.PVZ.CDEK,
    filtered_list: [],

    createClusterer: function () {
        this.clusterer = new ymaps.Clusterer({
            gridSize: 64,
            groupByCoordinates: false,
            hasBalloon: false,
            hasHint: false,
            margin: 10,
            maxZoom: 14,
            minClusterSize: 3,
            showInAlphabeticalOrder: false,
            viewportMargin: 128,
            zoomMargin: 0,
            clusterDisableClickZoom: false,
            preset: 'islands#darkGreenClusterIcons'
        })
    },

    createPoints: function () {
        this.createClusterer();
        this.pvzlist.forEach((item, index) => this.addPoint(item, index));
        return this.clusterer;
    },

    addPoint: function (item, index) {
        let Placemark = new ymaps.Placemark(
            [item.coordY, item.coordX],
            {},
            {
                preset: 'islands#darkGreenIcon'
            }
        );

        Placemark.link = index;

        Placemark.events.add(['balloonopen', 'click'], function (metka) {
            CDEK.setPanel(metka.get('target').link);
            if (panel.element.classList.contains('panel_hidden')){
                sidebar.burger.changeIcon();
            }
        });

        this.clusterer.add(Placemark);
    },
    
    getItemsByFilter: function (havecashless, isdressingroom) {
        this.pvzlist.forEach((item) => this.addItem(item, havecashless, isdressingroom));
    },

    addItem: function(item, havecashless, isdressingroom){
        let havecashless_result, isdressingroom_result;

        if (havecashless == 2) {
            havecashless_result = true;
        } else {
            havecashless_result = item.haveCashless == havecashless;
        }

        if (isdressingroom == 2) {
            isdressingroom_result = true;
        } else {
            isdressingroom_result = item.isDressingRoom == isdressingroom;
        }

        console.log('CDEK:' + havecashless + '-' + havecashless_result + '-' + isdressingroom + '-' + isdressingroom_result);

        if (havecashless_result && isdressingroom_result) {
            this.filtered_list.push(item);
        }
    },

    changeFilteredPoints: function (havecashless, isdressingroom) {
        this.filtered_list = [];
        this.getItemsByFilter(havecashless, isdressingroom);
        this.clusterer.removeAll();
        this.filtered_list.forEach((item, index) => this.addPoint(item, index));
        return this.clusterer;
    },

    setPanel: function (index) {
        let item, panel_body, div_placeholders, choose_button;

        item = CDEK.pvzlist[index];
        panel.setHeader(item.name);
        panel.setContent('');

        panel_body = document.getElementById('pvz_item');
        choose_button = document.getElementsByClassName('widget__choose')[0];
        div_placeholders = document.getElementsByClassName('panel-details__block-text');

        console.log(choose_button);

        choose_button.setAttribute('pvz_id', item.code);
        choose_button.setAttribute('pvz_name', item.name);
        choose_button.setAttribute('pvz', 'CDEK');

        console.log(choose_button);

        div_placeholders[0].innerHTML = item.address;
        div_placeholders[1].innerHTML = item.workTime;

        console.log(item.address);
        console.log(div_placeholders);

        panel.setContent(panel_body.innerHTML);
    },

    choose: function (id, name) {
        let input, button;

        input = document.getElementById('cart__delivery-cdek-input');
        button = document.getElementById('cart__delivery-cdek-button');

        console.log(input);
        console.log(button);

        input.value = id;
        button.value = name;
        pvzmap.close();
    }
};

window.IML = {
    pvzlist: pvzObj.PVZ.IML,
    filtered_list: [],

    createClusterer: function() {
        this.clusterer = new ymaps.Clusterer({
            gridSize: 64,
            groupByCoordinates: false,
            hasBalloon: false,
            hasHint: false,
            margin: 10,
            maxZoom: 14,
            minClusterSize: 3,
            showInAlphabeticalOrder: false,
            viewportMargin: 128,
            zoomMargin: 0,
            clusterDisableClickZoom: false,
            preset: 'islands#yellowClusterIcons'
        });
    },

    createPoints: function () {
        this.createClusterer();
        this.pvzlist.forEach((item, index) => this.addPoint(item, index));
        return this.clusterer;
    },

    addPoint: function (item, index) {
        let Placemark = new ymaps.Placemark(
            [item.Latitude, item.Longitude],
            {},
            {
                preset: 'islands#yellowIcon'
            }
        );

        Placemark.link = index;

        Placemark.events.add(['balloonopen', 'click'], function (metka) {
            IML.setPanel(metka.get('target').link);
            if (panel.element.classList.contains('panel_hidden')){
                sidebar.burger.changeIcon();
            }
        });

        this.clusterer.add(Placemark);
    },

    getItemsByFilter: function (havecashless, isdressingroom) {
        this.pvzlist.forEach((item) => this.addItem(item, havecashless, isdressingroom));
    },

    addItem: function(item, havecashless, isdressingroom){
        let havecashless_result, isdressingroom_result;

        if (havecashless == 2) {
            havecashless_result = item.PaymentType == 3 || item.PaymentType == 1 || item.PaymentType == 2 || item.PaymentType == 0;
        } else  if (havecashless == 0){
            havecashless_result = item.PaymentType == 1 || item.PaymentType == 3;
        } else if (havecashless == 1) {
            havecashless_result = item.PaymentType == 2 || item.PaymentType == 3;
        }

        if (isdressingroom == 2) {
            isdressingroom_result = true;
        } else {
            isdressingroom_result = item.FittingRoom == isdressingroom;
        }

        console.log('IML:' + havecashless + '-' + havecashless_result + '-' + isdressingroom + '-' + isdressingroom_result);

        if (havecashless_result && isdressingroom_result) {
            this.filtered_list.push(item);
        }
    },

    changeFilteredPoints: function (havecashless, isdressingroom) {
        this.filtered_list = [];
        this.getItemsByFilter(havecashless, isdressingroom);
        this.clusterer.removeAll();
        this.filtered_list.forEach((item, index) => this.addPoint(item, index));
        return this.clusterer;
    },

    setPanel: function (index) {
        let item, panel_body, div_placeholders, choose_button;

        item = IML.pvzlist[index];
        panel.setHeader(item.Name + ' ' + item.ID);
        panel.setContent('');

        panel_body = document.getElementById('pvz_item');
        choose_button = document.getElementsByClassName('widget__choose')[0];
        div_placeholders = document.getElementsByClassName('panel-details__block-text');

        choose_button.setAttribute('pvz_id', item.ID);
        choose_button.setAttribute('pvz_name', item.Name + ' ' + item.ID);
        choose_button.setAttribute('pvz', 'IML');

        div_placeholders[0].innerHTML = item.Address;
        div_placeholders[1].innerHTML = item.WorkMode;

        console.log(item.address);
        console.log(div_placeholders);

        panel.setContent(panel_body.innerHTML);
    },

    choose: function (id, name) {
        let input, button;

        input = document.getElementById('cart__delivery-cdek-input');
        button = document.getElementById('cart__delivery-cdek-button');

        console.log(input);
        console.log(button);

        input.value = id;
        button.value = name;
        pvzmap.close();
    }
};

window.sidebar = {
    burger: {
        element: document.getElementsByClassName('sidebar-burger')[0],
        changeIcon: function () {
            sidebar.burger.element.classList.toggle('close');
            sidebar.burger.element.classList.toggle('open');
            panel.showPanel();
        },

        showPanel: function () {
            panel.setHeader('Фильтр');
            panel.setContent(sidebar.burger.getFilterForm());
        },

        getFilterForm: function () {
            return document.getElementById('filter_block').innerHTML;

        }
    },

    sidebar_menu: {
        element: document.getElementsByClassName('sidebar-menu')[0],
        items: document.getElementsByClassName('sidebar-menu')[0].children,

        activate: function (event) {
            let active; // флаг активности элемента

            active = event.target.closest('li').classList.contains('active');

            if (event.target.tagName == 'LI' || event.target.tagName == 'DIV' || event.target.tagName == 'SPAN') {
                for (let i = 0; i < sidebar.sidebar_menu.items.length; i++) {
                    sidebar.sidebar_menu.items[i].classList.remove('active');
                }
                if (active == false) {
                    event.target.closest('li').classList.add('active');
                }
            }

        }
    }


};

window.panel = {
    element: document.getElementsByClassName('pvz_panel')[0],
    header: document.getElementsByClassName('panel_header')[0].children[0],
    body: document.getElementsByClassName('panel_container')[0],

    showPanel: function () {
        panel.element.classList.toggle('panel_hidden');
        panel.element.classList.toggle('panel_show');
    },

    setHeader: function (title) {
        this.header.innerHTML = title;
    },

    setContent: function (content) {
        this.body.innerHTML = content;
    },

    choose: function (button) {
        let pvz, id, name;
        pvz = button.getAttribute('pvz');
        id = button.getAttribute('pvz_id');
        name = button.getAttribute('pvz_name');

        window[pvz].choose(id, name);
    }
};

sidebar.burger.element.addEventListener('click', sidebar.burger.changeIcon);
sidebar.burger.element.addEventListener('click', sidebar.burger.showPanel);
sidebar.sidebar_menu.element.addEventListener('click', sidebar.sidebar_menu.activate);

