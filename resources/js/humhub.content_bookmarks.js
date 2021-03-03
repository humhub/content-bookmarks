/*
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
humhub.module('content_bookmarks', function (module, require, $) {
    var client = require('client');
    var streamEntry = require('stream.StreamEntry');

    streamEntry.prototype.bookmark = function(evt) {
        this.loader();
        var that = this;
        client.post(evt).then(function () {
            that.reload();
        }).catch(function (err) {
            module.log.error(err, true);
            that.loader(false);
        });
    }

    module.export = streamEntry;
});
