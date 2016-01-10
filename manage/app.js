var partnerApp = angular.module('partnerApp', ['ngRoute', 'angularFileUpload']);

partnerApp.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
	$routeProvider
		.when('/', {
			templateUrl : 'main.html',
			controller: 'mainTabCtrl'
		});
}]);
partnerApp.run(['$rootScope', '$location', 'Data', function ($rootScope, $location, Data) {
	$rootScope.$on("$routeChangeStart", function (event, next, current) {
		$rootScope.authenticated = false;
		Data.get("getSession.php?buster=" + new Date().getTime()).then(function (results) {
			if (typeof results == "string") {
				console.error("Error return value from get session:", results)
			}
			
			if (results.c_salt && results.c_partner_name) {
				$rootScope.authenticated = true;
				$rootScope.c_partner_name = results.c_partner_name;
				$rootScope.partner_name = results.c_partner_name;
			} else {
				var nextUrl = next.$$route.originalPath;
				if (nextUrl == '/login') {

				} else {
					window.location.href = "login.php";
				}
			}
		});
	});
}]);

partnerApp.controller('mainController', function($scope) {
	$scope.logout = function logout() {
		window.location.href = "logout.php";
	}
	$scope.changePassword = function changePassword() {
		window.location.href = "changePassword.php";
	}
});

partnerApp.controller('mainTabCtrl', ['$scope', 'FileUploader', '$http', 'Data', function ($scope, FileUploader, $http, Data) {
	$scope.initController = function initiController() {
		Data.get("getSession.php?buster=" + new Date().getTime()).then(function (results) {
			if (typeof results == "string") {
				console.error("Error return value from get session:", results)
			}
			
			if (results.c_partner_name) {
				$scope.partner_name = results.c_partner_name;
			} else {
				alert("Opps, something wrong with upload url");
				console.error("$scope.partner_name not found...");
			}
		});
		
		$scope.get_partner_images();
	}
	
	
	var uploader = $scope.uploader = new FileUploader({
		url: "partnerUpload.php?partner_name={partner_name}&current_file={current_file}&position={position}",
		templateUrl: "partnerUpload.php?partner_name={partner_name}&current_file={current_file}&position={position}"
	});
	uploader.uploadProcessFailed = false;
	uploader.onErrorItem = function(fileItem, response, status, headers) {
		console.error('onErrorItem', fileItem, response, status, headers);
		uploader.uploadProcessFailed = true;
	};
	uploader.onSuccessItem = function(fileItem, response, status, headers) {
		console.info('onSuccessItem', response);
		if (response && typeof response == "string") {
			uploader.uploadProcessFailed = true;
		}
	};
	uploader.onWhenAddingFileFailed = function(item //{File|FileLikeObject}
			, filter, options) {
		console.error('onWhenAddingFileFailed', item, filter, options);
		uploader.uploadProcessFailed = true;
	};

	$scope.get_partner_images = function() {
		$http.get("partnerDb.php?action=get_item&partner_name=" + $scope.partner_name + "&buster=" + new Date().getTime()).success(function(data) {
			if (typeof data == "string") {
				console.error("Unexpected data returned: " + data);
				return;
			} else if (data.length > 1) {
				console.error("More than 1 data returned for this partner name: " + data);
			}
			
			if (!data || !data[0]) {
				$scope.get_partner_images();
				return;
			}
			
			$scope.partner_id = data[0]["partner_id"];

			var dataArr = [];
			for (ii = 1; ii < 6; ii++) {
				var dataObject = {};
				dataObject.position = ii;
				dataObject.value = data[0]["partner_image_" + ii];

				dataArr.push(dataObject);
			}

			$scope.itemArray = dataArr;
		});
	}
	
	$scope._resetAllData = function resetAllData(data) {
		for (var i = 0, l = data.length; i < l; i++) {
			$scope.adv_delete(data[i].id)
		}
	}
	
	$scope.update_partner_images = function() {
		if (!$scope.currentChangingItemPosition) {
			alert("Please select a slot position for the image.");
			return;
		}
		if (uploader.queue.length != 1 || !uploader.queue[0]._file || !uploader.queue[0]._file.name) {
			alert("Please select and attach a file.");
			return;
		} else {
			var newFileValue = uploader.queue[0]._file.name
		}
		
		uploader.queue[0].url = uploader.templateUrl
			.replace("{partner_name}", $scope.partner_name)
			.replace("{current_file}", $scope.itemArray[$scope.currentChangingItemPosition - 1].value)
			.replace("{position}", $scope.currentChangingItemPosition);

		uploader.uploadProcessFailed = false;
		uploader.uploadAll();
		
		uploader.onSuccessItem = function(fileItem, response, status, headers) {
			$http.post('partnerDb.php?action=update_partner_image', {
				'partner_id' : $scope.partner_id,
				'partner_name' : $scope.partner_name,
				'currentChangingItemPosition' : $scope.currentChangingItemPosition,
				'newFileValue' : response.fileSavedPath
			}).success(function (data, status, headers, config) {
				if (typeof data == "string") {
					console.error("Unexpected data returned: " + data);
					return;
				}
			
				uploader.cancelAll();
				uploader.clearQueue();
				$scope.file_upload = "";
				
				if (!uploader.uploadProcessFailed) {
					$scope.itemArray[$scope.currentChangingItemPosition - 1].value = response.fileSavedPath;
				} else {
					alert("Upload Failed.");
				}
			})
			.error(function(data, status, headers, config){
				// error handling
			});
		};
	}
	
	$scope.delete_partner_images = function(targetDeletePosition) {
		if (!targetDeletePosition) {
			console.error("Position for the target delete image not found.");
			return;
		}
		
		$http.post('partnerDb.php?action=delete_partner_image', {
			'partner_id': $scope.partner_id,
			'targetDeletePosition': targetDeletePosition,
			'currentFile': $scope.itemArray[targetDeletePosition - 1].value
		}).success(function (data, status, headers, config) {
			if (typeof data == "string") {
				console.error("Unexpected data returned: " + data);
				return;
			}

			if (!uploader.uploadProcessFailed) {
				$scope.itemArray[targetDeletePosition - 1].value = "";
			} else {
				alert("Delete Failed.");
			}
		})
		.error(function(data, status, headers, config){
			// error handling
		});
	}
	
	$scope.stimulateClearAndUpload = function stimulateClearAndUpload(index) {
		var fileUploadInput = document.querySelector("[name='file_upload']");

		if (uploader.queue.length > 0) {
			$scope.file_upload = "";
			uploader.clearQueue();
		}
		fileUploadInput.click();
	}
	
	$scope.changeItem = function changeItem(position) {
		$scope.currentChangingItemPosition = position;

		var imageChangeButtons = document.querySelectorAll(".promo-image-change button");
		for (var i = 0, len = imageChangeButtons.length; i < len; i++) {
			var imageChangeButton = imageChangeButtons[i];
			if (i == position - 1) {
				imageChangeButton.style.backgroundColor = "lightyellow";
			} else {
				imageChangeButton.style.backgroundColor = "";
			}
		}
	}
	
}]);

partnerApp.factory("Data", ['$http',
    function ($http) {
        var serviceBase = "";

        var obj = {};
        obj.get = function (url) {
            return $http.get(serviceBase + url).then(function (results) {
                return results.data;
            });
        };
        obj.post = function (url, object) {
            return $http.post(serviceBase + url, object).then(function (results) {
                return results.data;
            });
        };
        obj.put = function (url, object) {
            return $http.put(serviceBase + url, object).then(function (results) {
                return results.data;
            });
        };
        obj.delete = function (url) {
            return $http.delete(serviceBase + url).then(function (results) {
                return results.data;
            });
        };

        return obj;
}]);

partnerApp.filter('formatter', function($filter) {
	return function() {
		var filterValue = arguments[0];
		var filterName = arguments[1];
		if (filterName == "unescape") {
			return unescape(filterValue);
		}
	};
});
