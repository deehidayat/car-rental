var CarRental = angular.module('CarRental', ['ui.bootstrap', 'ui.router']).config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/clients');
    $stateProvider.state('clients', {
        url: '/clients',
        templateUrl: 'tpls/clients.html',
        controller: 'ClientsController'
    }).state('cars', {
        url: '/cars',
        templateUrl: 'tpls/cars.html',
        controller: 'CarsController'
    }).state('rentals', {
        url: '/rentals',
        templateUrl: 'tpls/rentals.html',
        controller: 'RentalsController'
    });
}).run([function() {}])
.service('CRUDService', ['$http', function($http){
    function CRUDService() {
        this.baseUrl = '';
        this.dataset = [];
        this.data = {};
        this.error = null;
    }
    CRUDService.prototype.setBaseUrl = function(url) {
        this.baseUrl = url;
    };
    CRUDService.prototype.getIndexes = function() {
        var self = this;
        $http.get(self.baseUrl).then(function(response) {
            self.dataset = response.data;
        }, function(response) {
            self.error = response.data;
        });
    };
    CRUDService.prototype.setError = function(error) {
        this.error = error;
    };
    CRUDService.prototype.submit = function() {
        var self = this;
        if (self.data && self.data.id) {
            $http.put(self.baseUrl + '/' + self.data.id, self.data).then(function() {
                self.getIndexes();
                self.reset();
            }, function(response) {
                self.setError(response.data);
            });
        } else {
            $http.post(self.baseUrl, self.data).then(function() {
                self.getIndexes();
                self.reset();
            }, function(response) {
                self.setError(response.data);
            });
        }
    };
    CRUDService.prototype.reset = function() {
        this.data = {};
        this.error = null;
    }
    CRUDService.prototype.select = function(record) {
        this.data = angular.copy(record);
        this.error = null;
        scrollTo(0, 0);
    }
    CRUDService.prototype.delete = function(record) {
        var self = this;
        $http.delete(self.baseUrl + '/' + record.id).then(function() {
            self.getIndexes()
        }, function(response) {
            self.setError(response.data);
        });
    }
    
    return CRUDService;
}])
.controller('ClientsController', ['$scope', '$http', 'CRUDService', '$uibModal', function($scope, $http, CRUDService, $uibModal) {
    $scope.CRUD = new CRUDService;
    $scope.CRUD.setBaseUrl('clients');
    $scope.CRUD.getIndexes();
    // Client Histories
    $scope.histories = function(record) {
        $http.get('histories/client/' + record.id).then(function(response){
            $uibModal.open({
                controller: function($scope) {
                    $scope.data = response.data;
                },
                templateUrl: 'tpls/client-histories.html'
            });
        });
    }
}]).controller('CarsController', ['$scope', '$http', 'CRUDService', '$uibModal', function($scope, $http, CRUDService, $uibModal) {
    $scope.CRUD = new CRUDService;
    $scope.CRUD.setBaseUrl('cars');
    $scope.CRUD.getIndexes();
    // Car Histories
    $scope.histories = function(record) {
        $http.get('histories/car/' + record.id).then(function(response){
            $uibModal.open({
                controller: function($scope) {
                    $scope.data = response.data;
                },
                templateUrl: 'tpls/car-histories.html'
            });
        });
    }
}]).controller('RentalsController', ['$scope', '$http', 'CRUDService', '$filter', function($scope, $http, CRUDService, $filter) {
    $scope.CRUD = new CRUDService;
    $scope.CRUD.setBaseUrl('rentals');
    $scope.CRUD.getIndexes();
    $scope.date_from = new Date();
    $scope.date_to = new Date();
    $scope.dateFromOptions = {
        minDate: new Date()
    };
    $scope.dateToOptions = {
        minDate: new Date()
    };
    $scope.CRUD.reset = function() {
        $scope.CRUD.data = {};
        $scope.CRUD.error = null;
        $scope.date_from = new Date();
        $scope.date_to = new Date();
    }
    $scope.CRUD.select = function(record) {
        $scope.date_from = new Date(record.date_from);
        $scope.date_to = new Date(record.date_to);
        this.data = angular.copy(record);
        this.error = null;
        scrollTo(0, 0);
    }
    $scope.$watch('date_from', function(val){
        $scope.CRUD.data.date_from = val ? $filter('date')(val, 'yyyy-MM-dd') : null;
        $scope.dateToOptions.minDate = val;
    });
    $scope.$watch('date_to', function(val){
        $scope.CRUD.data.date_to = val ? $filter('date')(val, 'yyyy-MM-dd') : null;
    });
    // Clients
    $scope.clients = new CRUDService;
    $scope.clients.setBaseUrl('clients');
    $scope.clients.getIndexes();
    // Cars
    $scope.cars = new CRUDService;
    $scope.cars.setBaseUrl('cars');
    $scope.cars.getIndexes();
}]);