<!doctype html>
<html lang="en" ng-app="commentApp">
	<head>
		<meta charset="UTF-8">
		<title>Ask a question</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="{{ url() }}/css/main.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		
		<script src="{{ url() }}/js/controller.js"></script>
		<script src="{{ url() }}/js/services.js"></script> 
		<script src="{{ url() }}/js/app.js"></script>
		<script src="{{ url() }}/js/ui-bootstrap-tpls-0.14.2.min.js"></script>
		
		<style type="text/css">
			.modal-dialog {
				position:absolute;
				top:50% !important;
				left:25%;
				transform: translate(0, -50%) !important;
				-ms-transform: translate(0, -50%) !important;
				-webkit-transform: translate(0, -50%);
				margin: auto 5%;
				
			}
		</style>
	</head> 
	
	<body class="container" ng-init="logged_user_id = {{ $logged_user->id }}" ng-controller="mainController as c">
	<div class="row">
		<div class="col-md-12">
			<p>
			The {{ $top_user->username }} is the most successful in giving answers<br> You need {{ $top_user->vote - $logged_user->vote }} points to overtake him!
				
				<span class="pull-right">
				You have {{ $logged_user->vote }} 
				@if($logged_user->vote < 11)
					<img src="{{ url('images/bronze.jpg') }}" alt="" style="width:20px;height:20px" />
				@elseif($logged_user->vote > 10 && $logged_user->vote < 21)
					<img src="{{ url('images/silver.jpg') }}" alt="" style="width:20px;height:20px" />
				@else
					<img src="{{ url('images/golden.jpg') }}" alt="" style="width:20px;height:20px" />
				@endif
				points
				</span>
			</p>			
		</div>
		
	</div>
		<div class="col-md-8 col-md-offset-2">
		
		<hr>
			
			<div>
				<span class="pull-left col-md-3" style="text-align:right"><strong>{{ $top_user->username }} </strong></span>
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="{{ $top_user->vote }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 1em; width: {{ $top_user->vote }}%;">
					{{ $top_user->vote }}
					</div>
				</div>
			</div>
			
			<div>
				<span class="pull-left col-md-3" style="text-align:right"><strong>You </strong></span>
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="{{ $logged_user->vote }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 1em; width: {{ $logged_user->vote }}%;">
					{{ $logged_user->vote }}
					</div>
				</div>
			</div>
			
			<hr>

			<div id="navbar" class="collapse navbar-collapse">
		          <ul class="nav navbar-nav">
		            <li class="active"><a href="#">Questions</a></li>
		            <li><a href="leaderboard">Leaderboard</a></li>
		          </ul>
        	</div>

			<div class="page-header">
				<h2>Ask a question 
				@if(Session::has('user_id'))
				{{ \App\User::find(session('user_id'))->username }}
				@else
					My Name
				@endif
				</h2>
			</div>

			<form ng-submit="submitComment()">
			
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
					<h3>@{{ comment.title }} <small>by @{{ comment.user.username }}</h3>
					<p>@{{ comment.text }}</p>

					<p>
					<span ng-if="logged_user_id === comment.user.id">
						<a class="btn btn-xs btn-danger" href="" ng-click="deleteComment(comment.id)">Delete</a> | 
					</span>
					<a class="btn btn-primary btn-xs" href="" ng-click="c.showReply(comment)">Reply</a> |  
					<a class="btn btn-info btn-xs" href="" ng-click="voteUpComment(comment)">Vote Up</a>
					<a class="btn btn-info btn-xs" href="" ng-click="voteDownComment(comment)">Vote Down</a></p>
						<div ng-if="comment.showReply">
							<form id="comment.id" ng-submit="c.reply(comment)">
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
		
		<modal title="Notification" visible="showModal">
		<p>@{{ showModalContent.message }}</p>
		</modal>

	</body>
	
</html>
