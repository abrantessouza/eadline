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


app.factory("Validation",function(StoreBrowser, $http){

    var obj = {};
    obj.token = function(){
        return  $http.get('/auth?token='+StoreBrowser.getData());
    }
    return obj;
});

app.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);

app.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var fd = new FormData();
        fd.append('file', file);

        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })

            .success(function(){
            })

            .error(function(){
            });
    }
}]);


app.controller("Menu", function($scope,$http,StoreBrowser,$window){
    if($window.location.pathname.length >1){
        $scope.visible = true;
    }
});

app.controller("Home",function($scope, $window, Validation, StoreBrowser){
    $scope.visible = false;
    Validation.token().success(function(response){
       if(!response.redirect){
           StoreBrowser.removeToken();
           $window.location.href = "/";
       }
       else{
           $scope.visible = true;
       }
    });
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
            if(data.token.length > 30) {
                StoreBrowser.setData(data.token);
                $window.location.href = "/home";
            }
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

app.controller('managerCourse', ['$scope', 'fileUpload', function($scope, fileUpload){
    $scope.uploadFile = function(){
        var file = $scope.myFile;
        var uploadUrl = "/savedata";
        fileUpload.uploadFileToUrl(file, uploadUrl);
    };
}]);
