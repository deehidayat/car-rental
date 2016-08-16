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
.controller('ClientsController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
    $scope.data = null;
    $scope.error = null;
    $scope.dataset = [];

    function getData() {
        $http.get('clients').then(function(response) {
            $scope.dataset = response.data;
        });
    }
    getData();

    function setError(error) {
        $scope.error = error;
    }
    $scope.onFormSubmit = function() {
        if ($scope.data && $scope.data.id) {
            $http.put('clients/' + $scope.data.id, $scope.data).then(function() {
                getData();
                $scope.data = null;
            }, function(response) {
                setError(response.data);
            });
        } else {
            $http.post('clients', $scope.data).then(function() {
                getData();
                $scope.data = null;
            }, function(response) {
                setError(response.data);
            });
        }
    };
    $scope.onResetClick = function() {
        $scope.data = null;
        $scope.error = null;
    }
    $scope.onEditClick = function(data) {
        $scope.data = angular.copy(data);
        $scope.error = null;
    }
    $scope.onDeleteClick = function(data) {
        $http.delete('clients/' + data.id).then(function() {
            getData();
        }, function(response) {
            setError(response.data);
        });
    }
}]).controller('CarsController', ['$scope', '$http', function($scope, $http) {
    $scope.data = null;
    $scope.error = null;
    $scope.dataset = [];

    function getData() {
        $http.get('cars').then(function(response) {
            $scope.dataset = response.data;
        });
    }
    getData();

    function setError(error) {
        $scope.error = error;
    }
    $scope.onFormSubmit = function() {
        if ($scope.data && $scope.data.id) {
            $http.put('cars/' + $scope.data.id, $scope.data).then(function() {
                getData();
                $scope.data = null;
            }, function(response) {
                setError(response.data);
            });
        } else {
            $http.post('cars', $scope.data).then(function() {
                getData();
                $scope.data = null;
            }, function(response) {
                setError(response.data);
            });
        }
    };
    $scope.onResetClick = function() {
        $scope.data = null;
        $scope.error = null;
    }
    $scope.onEditClick = function(data) {
        $scope.data = angular.copy(data);
        $scope.error = null;
    }
    $scope.onDeleteClick = function(data) {
        $http.delete('cars/' + data.id).then(function() {
            getData();
        }, function(response) {
            setError(response.data);
        });
    }
}]);