<!doctype html>
<html lang="en" ng-app="commentApp">
	<head>
		<meta charset="UTF-8">
		<title>Ask a question</title>
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css"> 
		<link rel="stylesheet" href="{{ url() }}/css/main.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
		
		
		<script src="{{ url() }}/js/controller.js"></script>
		<script src="{{ url() }}/js/services.js"></script> 
		<script src="{{ url() }}/js/app.js"></script>
		
	</head> 
	
	<body class="container" ng-controller="mainController as c">
		<div class="col-md-8 col-md-offset-2">

			<div id="navbar" class="collapse navbar-collapse">
		          <ul class="nav navbar-nav">
		            <li class="active"><a href="#">Questions</a></li>
		            <li><a href="leaderboard">Leaderboard</a></li>
		          </ul>
        	</div>

			<div class="page-header">
				<h2>Ask a question</h2>
			</div>

			<form ng-submit="submitComment()">
			
				<div class="form-group">
					<input type="text" class="form-control input-sm" name="author" ng-model="commentData.author" placeholder="Your username" required>
				</div>
			
				<div class="form-group">
					<input type="text" class="form-control input-lg" name="title" ng-model="commentData.title" placeholder="Ask a question" required>
				</div>
			
				<div class="form-group">
					<input type="text" class="form-control input-lg" name="comment" ng-model="commentData.text" placeholder="Description" required>
				</div>
			
				<div class="form-group text-right">   
					<button type="submit" class="btn btn-primary btn-lg">Submit</button>
				</div>
			</form>
			
			<p class="text-center" ng-show="loading"><span class="fa fa-meh-o fa-5x fa-spin"></span></p>
			
            <!-- replies on questions -->
			<script id="comments.template" type="text/ng-template">
				<div class="comment">
					<h3>@{{ comment.title }} <small>by @{{ comment.author }}</h3>
					<p>@{{ comment.text }}</p>

					<p><a class="btn btn-xs btn-danger" href="" ng-click="deleteComment(comment.id)">Delete</a> | 
					<a class="btn btn-primary btn-xs" href="" ng-click="c.showReply(comment)">Reply</a> |  
					<a class="btn btn-info btn-xs" href="" ng-click="voteUpComment(comment)">Vote Up</a>
					<a class="btn btn-info btn-xs" href="" ng-click="voteDownComment(comment)">Vote Down</a></p>
						<div ng-if="comment.showReply">
							<form id="comment.id" ng-submit="c.reply(comment)">
								<div class="form-group">
									<input type="text" class="form-control input-sm" name="author" ng-model="c.replyComment.author" placeholder="Your Username" required>
								</div>
								<div class="form-group">
									<input type="text" class="form-control input-sm" name="title" ng-model="c.replyComment.title" placeholder="Your reply" required>
								</div>
								<div class="form-group">
									<input type="text" class="form-control input-sm" name="comment" ng-focus="comment.showReply" ng-model="c.replyComment.text" placeholder="Describe the sollution to the problem" required>
								</div>
	                        <div class="form-group">
	                            <button type="submit" class="btn btn-danger btn-xs">reply</button>
	                            <button class="btn btn-danger btn-xs" ng-click="comment.showReply=false">cancel</button>
	                        </div>
							</form>
	                    </div>
						
						<ng-include src="'comments.template'" ng-repeat="comment in comment.children"></ng-include>
						
				</div> 
			</script>
            <ng-include src="'comments.template'" ng-repeat="comment in comments"></ng-include>
			
		</div> 
		
	</body> 
</html>