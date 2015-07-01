angular.module('commentService', [])

.factory('Comment', function($http) {

    return {
        // get all the comments
        get : function() {
            return $http.get('api/comments');
        },

        // save a comment (pass in comment data)
        save : function(commentData) {
            return $http.post(
				'api/comments',
				{
					'parent_id'	: commentData.parent,
					'author'	: commentData.author,
					'title'		: commentData.title,
					'text'		: commentData.text
				}
			);
        },

        // destroy a comment
        destroy : function(id) {
            return $http.delete('api/comments/' + id);
        }
    }

});