// public/js/mainCtrl.js

angular.module('mainCtrl', [])

// inject the Comment service into our controller
.controller('mainController', function($scope, $http, Comment) {
    // object to hold all the data for the new comment form
    $scope.commentData = {};
	
	this.replyComment = {};
	
    // loading variable to show the spinning loading icon
    $scope.loading = true;
	
	$scope.showModal = false;
	$scope.showModalContent = '';
	
	// get all the comments first and bind it to the $scope.comments object
    // use the function we created in our service
    // GET ALL COMMENTS ==============
    Comment.get()
        .success(function(data) {
            $scope.comments = data;
			$scope.loading = false;			
        });
		
    // function to handle submitting the form
    // SAVE A COMMENT ================
    $scope.submitComment = function() {
        $scope.loading = true;

        // save the comment. pass in comment data from the form
        // use the function we created in our service
        Comment.save($scope.commentData)
            .success(function(data) {

                // if successful, we'll need to refresh the comment list
                Comment.get()
                    .success(function(getData) {
                        $scope.comments = getData;
                        $scope.loading = false;
                    });
				$scope.commentData.title = '';
				$scope.commentData.text = '';
            })
            .error(function(data) {
                console.log(data);
            });
    };

    // function to handle deleting a comment
    // DELETE A COMMENT ====================================================
    $scope.deleteComment = function(id) {
        $scope.loading = true; 

        // use the function we created in our service
        Comment.destroy(id)
            .success(function(data, status) {

				if(data.code !== 200)
				{
					$scope.showModal = true;
					$scope.showModalContent = data;
				}
				
                // if successful, we'll need to refresh the comment list
                Comment.get()
                    .success(function(getData) {
                        $scope.comments = getData;
                        $scope.loading = false;
                    });

            });
    };
	
	$scope.voteUpComment = function(comment)
	{
		$http.post('vote-up', {'user_id':comment.user_id, 'comment_id':comment.id}).success(function(data){
			
			$scope.showModal = true;
			$scope.showModalContent = data;
				
			Comment.get()
			.success(function(data) {
				$scope.comments = data;
				$scope.loading = false;			
			});
			
			if(data.code === 200){
				window.setTimeout(function(){location.reload()},3000);
			}
		});
		
	}
	
	$scope.voteDownComment = function(comment)
	{
		$http.post('vote-down', {'user_id':comment.user_id, 'comment_id':comment.id }).success(function(data){
			$scope.showModal = true;
			$scope.showModalContent = data;
				
			Comment.get()
			.success(function(data) {
				$scope.comments = data;
				$scope.loading = false;			
			});
			
			if(data.code === 200){
				window.setTimeout(function(){location.reload()},3000);
			}
		});
		
	}
	
	this.showReply = function(comment) {
		if(this.currentCommentBeingReplied)
			this.currentCommentBeingReplied.showReply = false;
		comment.showReply = true;
		this.currentCommentBeingReplied = comment;
	};
	
	this.reply = function(comment)
	{
		$scope.loading = true;
		this.replyComment.parent = comment.id;

        // save the comment. pass in comment data from the form
        // use the function we created in our service
        Comment.save(this.replyComment)
            .success(function(data) {

                // if successful, we'll need to refresh the comment list
                Comment.get()
                    .success(function(getData) {
                        $scope.comments = getData;
                        $scope.loading = false;
                    });
            })
            .error(function(data) {
                console.log(data);
            });
		this.replyComment.title = '';
		this.replyComment.text = '';
		comment.showReply = false;
	}
	
});
