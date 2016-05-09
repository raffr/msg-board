angular.module('ExamApp', []).
  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when('/', {templateUrl: 'assets/tpl/lists.html', controller: ListCtrl}).
      otherwise({redirectTo: '/'});
}]);

function ListCtrl($scope, $http, $timeout) {

  $http.get('api/msgs').success(function(data) {
    $scope.list = data;
  });

  $scope.add_new = function(msg, addMsg) {
    $http.post('api/add_msg',{message: msg.newmsg}).success(function(data) {
      $timeout(function() {
        $scope.list.push({message: data.message});
        $scope.message = {};
      });
    });
  }
  
}