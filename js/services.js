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
        },
        removeToken: function(){
            return $window.localStorage && $window.localStorage.removeItem('token');
        }

    };
});

app.controller("Home", function($scope,$http,StoreBrowser){

});

app.controller("Auth",function($scope, $http, StoreBrowser, $window){
    $scope.visible = false;
    try {
        if (StoreBrowser.getData() != undefined || StoreBrowser.getData() != null) {
            $http.get('/auth?token='+StoreBrowser.getData()).then(function (response) {
               if(response.data.redirect){
                   $window.location.href = "/home";
               }else{
                   StoreBrowser.removeToken('token');
                   $window.location.href = "/";
                   $scope.visible = true;

               }
            });
        }else{
            $scope.visible = true;
        }

    }catch(e){
        console.log(e);
    }

    $scope.actAuth = function(){
        $http({
            method : 'POST',
            url    : '/auth',
            data   : {user: $scope.username,  password: $scope.passwordlogin},
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function(data){
            StoreBrowser.setData(data.token);
        });
    }

    $scope.actRegister = function(){
        $http({
            method : 'POST',
            url    : '/register',
            data   : { name: $scope.name, email: $scope.email, password: $scope.password},
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function(data){
            if(data.output) {
                $scope.name = "";
                $scope.email = "";
                $scope.password = "";
                $("#loginScreen").click();
            }else{
                $scope.msg = data.msg;
            }
        });
    }
});


