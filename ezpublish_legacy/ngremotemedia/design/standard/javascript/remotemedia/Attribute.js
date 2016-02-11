// A Remoemedia attributes model
RemoteMedia.models.Attribute = Backbone.Model.extend({
    // prefix : null,
    // medias : null,
    controller: null,

    initialize: function(options) {
        // _.bindAll(this);
        // this.medias = new RemoteMedia.models.MediaCollection();
        this.medias.attr = this;
    },

    // toScaleIndexed: function() {
    //     return _.reduce(this.get('toScale') || [], function(h, v) {
    //         h[v.name.toLowerCase()] = v;
    //         return h;
    //     }, {});
    // },

    // url: function(method, extra) {
    //     // extra = (extra || [this.id,this.version()]);
    //     // return this.get('prefix') + '/' + ['remotemedia', method].concat(extra).join('::');
    //     return ["/ezexceed/ngremotemedia/fetch", eZOeGlobalSettings.ez_contentobject_id, this.id, this.get('version')].join('/'); // /90430/5
    // },

    media: function(media, extra) {
        var _this = this;
        $.getJSON(this.url('media', extra), function(resp) {
            var content = resp.content,
                data = content.media;
            if (_(content).has('toScale'))
                _this.set('toScale', content.toScale);
            if (_(content).has('classList'))
                _this.set('classList', content.classList);
            if (_(content).has('viewModes'))
                _this.set('viewModes', content.viewModes);

            if ('content' in content)
                data.content = content.content;
            media.set(data);
        });
        return media;
    },

    // scale: function(media) {
    //     $.getJSON(this.url('scaler', [media]), this.onScale);
    // },

    // onScale: function(response) {
    //     this.trigger('scale', response);
    // },

    // combined_versions: function() {
    //     var indexed = this.toScaleIndexed();
    //     return _.map(this.get('available_versions'), function(v) {
    //         v = $.extend({}, v);
    //         var exact_version = indexed[v.name.toLowerCase()];
    //         exact_version && (v.coords = exact_version.coords);
    //         return v;
    //     });
    // },

    // // Create a new vanity url for a version
    // // name should be a string to put on the back of the object name
    // // coords should be an array [x,y,x2,y2]
    // // size shoudl be an array [width,height]
    // addVanityUrl: function(name, coords, size, options) {
    //     options = (options || {});
    //     var data = {
    //         name: name,
    //         size: size
    //     };

    //     if (coords) data.coords = coords;

    //     if (_(options).has('media')) {
    //         data.mediaId = options.media.id;
    //         data.remotemediaId = options.media.get('remotemediaId');
    //     }

    //     var url = ['remotemedia', 'saveVersion', this.get('id'), this.version()].join('::'),
    //         context = this;
    //     $.ez(url, data, function(response) {
    //         context.trigger('version.create', response.content);
    //     });
    //     return this;
    // },

    // version: function() {
    //     if (this.controller && this.controller.getVersion())
    //         return this.controller.getVersion();
    //     return this.get('version');
    // }
});