$(function() {

	var templates = {
			result: Handlebars.compile($('#result-template').html()),
			selection: Handlebars.compile($('#selection-template').html()),
		};


	$('form').on('submit', function(event) {
		var $this = $(this);

		event.preventDefault();

		$.post($this.data('action'), $this.serialize())
		.done(function() {
			console.log(arguments);
		});
	});

	$('.btn-group').button();

	$('input.card').select2({
		placeholder: 'Search for a card',
		minimumInputLength: 1,
		allowClear: true,

		formatResult: templates.result,
		formatSelection: templates.selection,

		// we do not want to escape markup since we are displaying html in results and selection
		escapeMarkup: function(html) {
			return html;
		},

		ajax: {
			cache: true,
			dataType: 'json',

			// search for a cards name and filter by the class if there is a class field on screen and the input doesn't have data-filter set to false
			url: function(term) {
				var $class = $('#class');
				return 'cards/' + term + ($class.val() && this.data('filter') !== false ? '/' + $class.val() : '');
			},

			results: function(data) {
				return {
					results: data
				};
			}
		},

		// TODO: do we need this?
		initSelection: function(element, callback) {
			// the input tag has a value attribute preloaded that points to a preselected movie's id
			// this function resolves that id attribute to an object that select2 can render
			// using its formatResult renderer - that way the movie name is shown preselected
			var value = $(element).val();

			// TODO: this value is the card's id and not its name
			if (value) {
				$.ajax('card/' + value, {
					dataType: 'json',
					cache: true
				}).done(callback);
			}
		}
	});

});
