var app = angular.module("eadline",[]);


app.factory("StoreBrowser", function($window, $rootScope) {
    angular.element($window).on('storage', function(event) {
        if (event.key === 'token') {
            $rootScope.$apply();
        }
    });
    return {
        setData: function(val) {
            $window.localStorage && $window.localStorage.setItem('token', val);
            return this;
        },
        getData: function() {

            return $window.localStorage && $window.localStorage.getItem('token');
        }
    };
});

app.controller("Auth",function($scope, $http, StoreBrowser){

    try {
        if (StoreBrowser.getData() != undefined || StoreBrowser.getData() != null) {
            $http.get('/auth?token='+StoreBrowser.getData()).then(function (response) {
                console.log(response.data.redirect);
            });
        }
    }catch(e){
        console.log(e);
    }

    $scope.actAuth = function(){
        $http({
            method : 'POST',
            url    : '/auth',
            data   : {user: $scope.username},
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function(data){
            StoreBrowser.setData(data.token);
        });
    }

    $scope.actRegister = function(){
        $http({
            method : 'POST',
            url    : '/register',
            data   : { name: $scope.name, email: $scope.email},
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function(data){

        });
    }
});


