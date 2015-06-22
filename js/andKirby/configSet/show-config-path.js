/*jslint browser: true, nomen: true */
/*global $, console, Element, ANDKIRBY, ANDKIRBY_CONFIG_PATH_SHOW */

(function () {
    'use strict';
    if (!window.hasOwnProperty('ANDKIRBY')) {
        window.ANDKIRBY = {};
    }

    //init namespaces
    ANDKIRBY.ConfigSet = window.ANDKIRBY.ConfigSet || {};
    ANDKIRBY.ConfigSet.SystemConfig = window.ANDKIRBY.ConfigSet.SystemConfig || {};
    var SystemConfig = ANDKIRBY.ConfigSet.SystemConfig;

    SystemConfig.ConfigPath = {
        /**
         * Grab XPath from element attributes
         *
         * @param {Element} element
         * @returns {string}
         * @private
         */
        _getValueXpath: function (element) {
            var name = element.name,
                id = element.id,
                result,
                section,
                group,
                field;

            result = /groups\[([A-z_]+)\]\[fields\]\[([A-z_]+)\]\[value\]/.exec(name);
            if (null === result) {
                return null;
            }
            group = result[1];
            field = result[2];
            section = id.replace('_' + group + '_' + field, '');

            return section + '/' + group + '/' + field;
        },

        /**
         * Get config value containers
         *
         * @returns {[Element]}
         * @private
         */
        _getConfigValueContainers: function () {
            return $('config_edit_form').select('table.form-list td.value');
        },

        /**
         *
         * Put path information under value element
         *
         * @param {Element} element
         * @param {String} path
         * @returns {ANDKIRBY.ConfigSet.SystemConfig.ConfigPath}
         * @private
         */
        _putPathInfo: function (element, path) {
            element.insert({
                after: '<p>Path: <code>' + path + '</code></p>'
            });
            return this;
        },

        /**
         * Show XPath of config value elements
         *
         * @returns {ANDKIRBY.ConfigSet.SystemConfig.ConfigPath}
         */
        show: function () {
            var valueCols = this._getConfigValueContainers();
            valueCols.each(function (elementContainer) {
                var valueElement = elementContainer.firstDescendant(),
                    path;

                path = this._getValueXpath(valueElement);
                if (null === path) {
                    path = '-no-path-';
                }
                this._putPathInfo(valueElement, path);
            }.bind(this));
            return this;
        }
    };

    document.observe('dom:loaded', function () {
        if (ANDKIRBY_CONFIG_PATH_SHOW) {
            SystemConfig.ConfigPath.show();
        }
    });
}());
