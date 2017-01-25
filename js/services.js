var app = angular.module("eadline",[]);

app.controller("Auth",function($scope, $http){
    $scope.actAuth = function(){
        $http({
            method : 'POST',
            url    : '/auth',
            data   : {user: $scope.username},
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function(data){
            console.log('Foi');
        });
    }
});


