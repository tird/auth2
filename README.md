# auth2
  public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    
    
    
            function LoginController($location,  $scope, $http) {
        var vm = this;

        vm.user = {
            username: '',
            password: '',
            rememberMe: 1
        }

        vm.logIn = function(){

            vm.user.username = vm.username;
            vm.user.password = vm.password;

            vm.send = function () {
                return $http.post('rest.php/users/login', vm.user)
                    .then(successHandler)
                    .catch(errorHandler);
                function successHandler(result) {
                    localStorage.setItem('username',vm.username);
                    alert('success');
                    $location.path('/resource/index');

                }
                function errorHandler(result){
                    alert(result.data[0].message);
                    console.log(result.data[0].message);
                }
            };
            vm.send();
        }
    }
    
    
    function run($rootScope, $location, $cookieStore, $http){


        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            var test = localStorage.getItem('username');
            if (!test) {
                $location.path('/site/login');
            }
            console.log('change');
        });
    }
