function initVideoManual(params) {
    let data = JSON.parse(params.data);
    if (data) {
        window['manual_parameter_' + params.propertyID] = new VideoManual(data, params);
    }
}

function VideoManual(data, params) {
    let rand = BX.util.getRandomString(5);
    this.params = params || {};
    this.data = data || {};
    this.parentNode = BX.findParent(this.params.oCont);

    this.creatTab();

    this.iframeNode = this.createIframe();
    this.removeChildren();
    this.createNode();

    BX.loadCSS(this.getPath() + '/style.css?' + rand);
}

VideoManual.prototype = {
    getPath: function() {
        let path = this.params.propertyParams.JS_FILE.split('/');
        path.pop();
        return path.join('/');
    },
    creatTab: function() {

        const manualTab = BX.create('div', {
            attrs:{
                'className': "bxcompprop-item bxcompprop-item_custom",
                "data-bx-comp-group-id_custom" : "VIDEO_MANUAL",
            },
            props: {},
            html: '<span class="bxcompprop-item-alignment"></span><span class="bxcompprop-item-text">Видео инструкция</span>'
        });

        const parentTab = document.querySelector(".bxcompprop-items-block");

        BX.append(manualTab, parentTab);

        BX.bind(manualTab, 'click', this.moveToTab);

    },
    moveToTab: function(e) {
        e.preventDefault();

        const elementToScroll = document.querySelector("#VIDEO_MANUAL"),
            parentElement = document.querySelector(".bxcompprop-content");

        parentElement.scrollBy({
            top: elementToScroll.offsetTop,
            behavior: 'smooth'
        });
    },
    createIframe: function() {
        const {data} = this;
        return BX.create('iframe', {
            attrs: {
                'readonly': 'readonly',
                'className': "manual_frame",
                'allow': "accelerometer",
                'clipboard-write': 'clipboard-write',
                'encrypted-media': 'encrypted-media',
                'gyroscope': 'gyroscope',
                'picture-in-picture': 'picture-in-picture',
                'allowfullscreen': 'allowfullscreen'
            },
            props: {
                'src': data.SOURCE
            }
        });
    },
    removeChildren: function () {
        BX.cleanNode(this.parentNode);
    },
    createNode: function () {
        const headingNode = BX.create('td', {
            attrs:{
                'className': "bxcompprop-cont-table-l-r bxcompprop-cont-table-l-r_video-manual",
                'colspan' : 2,
                'id': "VIDEO_MANUAL"
            },
            props: {},
            html: '<div class="video-manual__heading">'+this.params.propertyParams.NAME+'</div>'
        });

        BX.append(headingNode, this.parentNode);

        const videoNode = BX.create('div', {
            attrs:{
                'className': "video-manual__value",
            },
            props: {},
            children: [this.iframeNode]
        });

        BX.append(videoNode, headingNode);

    }
};