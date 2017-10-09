import babelHelpers from '../vendor/babel-external-helpers/babel-external-helpers.js';
global.jQuery = require('../vendor/jquery/jquery.js');
const $ = jQuery;
global.Tether = require('tether');
require('bootstrap');
import autosize from 'autosize';

import animsition from '../vendor/animsition/animsition2.js';
require('../vendor/mousewheel/jquery.mousewheel.js');
require('../vendor/asscrollbar/jquery-asScrollbar.js');
require('../vendor/asscrollable/jquery-asScrollable.js');
require('../vendor/ashoverscroll/jquery-asHoverScroll.js');
require('../vendor/waves/waves.js');
require('../vendor/switchery/switchery.js');
global.toastr = require('../vendor/toastr/toastr.js');
require('../vendor/jquery-placeholder/jquery.placeholder.js');
require('../vendor/formvalidation/formValidation.js');
require('../vendor/formvalidation/framework/bootstrap4.js');

require('../es/State.js');
require('../es/Component.js');
require('../es/Plugin.js');
require('../es/Base.js');
global.Config = require('../es/Config.js');
require('../es/Section/Menubar.js');
require('../es/Section/GridMenu.js');
require('../es/Section/Sidebar.js');
require('../es/Section/PageAside.js');
require('../es/Plugin/menu.js');

var Site = require('../es/Site.js');

require('../es/Plugin/animsition.js');
require('../es/Plugin/asscrollable.js');
require('../es/Plugin/slidepanel.js');
require('../es/Plugin/switchery.js');
require('../es/Plugin/toastr.js');
require('../es/Plugin/jquery-placeholder.js');
require('../es/Plugin/material.js');

Config.set('assets', '../');

// autosize text areas
$(document).ready(function() {
	Site.run();
	autosize($('textarea'));
});