var app = angular.module("eadline",['ngFileUpload']);


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


app.controller("saveTrainingController", function($scope, Upload, StoreBrowser){
    $scope.saveTraining = function(){
        if($scope.form.file.$valid && $scope.goFile){
            Upload.upload({
                url:'/savetraining',
                method: 'POST',
                file: $scope.goFile,
                data:{
                    'training_name': $scope.training_name,
                    'sumary': $scope.training_description,
                    'token': StoreBrowser.getData()
                }
            }).then(function(reps){
               if(reps.data.response){
                   $("#form_course").modal("hide");
               }
            });
        }
    }
});

app.controller("loadTrainingController", function($scope, $http, StoreBrowser){
    $http.get('/loadmytrainingcourses?token='+StoreBrowser.getData()).then(function (response) {

         $scope.myCourses = response.data;

    })
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


