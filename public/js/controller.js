// public/js/mainCtrl.js

angular.module('mainCtrl', [])

// inject the Comment service into our controller
.controller('mainController', function($scope, $http, Comment) {
    // object to hold all the data for the new comment form
    $scope.commentData = {};
	
	this.replyComment = {};
	
    // loading variable to show the spinning loading icon
    $scope.loading = true;
	
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
				$scope.commentData.author = '';
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
            .success(function(data) {

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
		$http.post('vote-up', {'author':comment.author}).success(function(data){
		 alert('Succesfully voted up!');
		});
	}
	
	$scope.voteDownComment = function(comment)
	{
		$http.post('vote-down', {'author':comment.author}).success(function(data){
		 alert('Succesfully voted down!');
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
		this.replyComment.author = '';
		this.replyComment.title = '';
		this.replyComment.text = '';
		comment.showReply = false;
	}
	
});

