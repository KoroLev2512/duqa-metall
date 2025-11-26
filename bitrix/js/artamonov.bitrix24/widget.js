/*
 * @updated 04.12.2020, 0:44
 * @author Артамонов Денис <artamonov.ceo@gmail.com>
 * @copyright Copyright (c) 2020, Компания Webco
 * @link http://webco.io
 */

'use strict';

const Widget = {
    _instance: false,
    _openline: false,
    construct: function () {
        window.onload = function () {
            if (typeof window.BX === 'undefined' || !window.BX.SiteButton || !window.BX.SiteButton.check() || !window['ArtamonovBitrix24Widget']) return;
            Widget._instance = window['ArtamonovBitrix24Widget'];
            let channels = window.BX.SiteButton.wm.getList();
            for (let i = 0; i < channels.length; i++) {
                if (channels[i].type === 'openline') {
                    Widget._openline = true;
                    break;
                }
            }
            Widget.modify();
        }
    },
    getInstance: function () {
        return this._instance;
    },
    getSettings: function () {
        return this.getInstance().settings;
    },
    hasOpenLine: function () {
        return this._openline;
    },
    modify: function () {
        if (this.getSettings().whatsApp.active) this.whatsApp();
    },
    whatsApp: function () {
        if (!this.hasOpenLine()) return;
        window.BX.SiteButton.buttons.add({
            href: 'https://api.whatsapp.com/send?phone=' + this.getSettings().whatsApp.account,
            id: 'openline_whatsapp',
            title: this.getSettings().whatsApp.title || 'WhatsApp',
            sort: this.getSettings().whatsApp.sort || 99999,
            icon: '/bitrix/images/artamonov.bitrix24/widget-whats-app.png'
        });
    }
};

Widget.construct();
