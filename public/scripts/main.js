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
    });
}).run([function() {}])
.service('CRUDService', ['$http', function($http){
    function CRUDService() {
        this.baseUrl = '';
        this.dataset = [];
        this.data = null;
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
        this.data = null;
        this.error = null;
    }
    CRUDService.prototype.select = function(record) {
        this.data = angular.copy(record);
        this.error = null;
    }
    CRUDService.prototype.delete = function(record) {
        var self = this;
        $http.delete(self.baseUrl + '/' + record.id).then(function() {
            self.getIndexes()
        }, function(response) {
            self.setError(response.data);
        });
    }
    
    return new CRUDService;
}])
.controller('ClientsController', ['$scope', '$http', 'CRUDService', function($scope, $http, CRUDService) {
    $scope.CRUD = CRUDService;
    $scope.CRUD.setBaseUrl('clients');
    $scope.CRUD.getIndexes();
}]).controller('CarsController', ['$scope', '$http', 'CRUDService', function($scope, $http, CRUDService) {
    $scope.CRUD = CRUDService;
    $scope.CRUD.setBaseUrl('cars');
    $scope.CRUD.getIndexes();
}]);