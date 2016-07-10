String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
(function($){
	

	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
	    clearTimeout (timer);
	    timer = setTimeout(callback, ms);
	  };
	})();
	$(document).ready(function(){
		$('body').delegate('.post-suggestion', 'keyup', function(e) {
			e.preventDefault();
			var _this = this;

			if( $(this).val() === '' )
				return;
			
			delay(function(){
				$.ajax({
					url: ajaxurl,
					type: 'GET',
					context:_this,
					data: {
						action: 'spw_posts_suggestion',
						q: $(_this).val()
					},
					success: function(data){
						$('.chef-posts-suggestions').remove();
						$(data).insertAfter($(_this));						
					}
				});
			}, 500);			
		});

		$('body').delegate('.chef-posts-suggestions li', 'click', function(e) {
			e.preventDefault();
			var _field = $(this).closest('.post-suggestion-wrap').find('input[type="hidden"]');
			$('.chef-posts-suggestions').remove();
			var _template = $('.selected-posts [data-template]').html();

			var id = $(this).data('id');
			var name = $('.selected-posts [data-template]').data('name');

			_template = _template.replaceAll('{id}', id).replaceAll('{title}', $(this).text());
			console.log($(this))
			$(_field).val( $(_field).val() + ' '+id );
			
			if( $('.selected-posts').find('[data-id="'+id+'"]').length > 0 )
				$('.selected-posts').find('[data-id="'+id+'"]').remove();

			$('.selected-posts').append(_template);
		});

		$('body').delegate('.selected-posts > div .remove', 'click', function(e) {
			e.preventDefault();

			var _field = $($(this).closest('.post-suggestion-wrap')).find('input[type="hidden"]');
			var _id = $(this).parent().data('id');
			console.log(_id, $(_field).val().replaceAll(_id, ''));
			$(_field).val( $(_field).val().replaceAll(_id, '') );
			
			$(this).parent().remove();
		});

		function bindSortable(){
			$('.selected-posts').sortable({
				helper:'clone', 
				stop : function(event,ui){ 
					var divs = $(ui.item).closest('.selected-posts').find('[data-id]');
					var _allIDs = $(divs).map(function() {
						if($(this).data('id') !== '{id}')
					    	return $(this).data('id');
					}).get();
					var _field = $(ui.item).closest('.post-suggestion-wrap').find('input[type="hidden"]');
					$(_field).val( _allIDs.join(' ') );
				}
			});
		};

		bindSortable();

		$(document).on('widget-updated', function(e, widget){
			bindSortable();
		});

	});
})(jQuery)
