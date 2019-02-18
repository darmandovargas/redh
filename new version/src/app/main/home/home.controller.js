(function ()
{
    'use strict';

    angular
        .module('app.home')
        .controller('HomeController', HomeController);

    /** @ngInject */
    function HomeController(HomeData)
    {
        var vm = this;

        // Data
        vm.helloText = HomeData.data.helloText;

        // Methods

        //////////
    }
})();
