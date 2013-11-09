$(function() {

	var templates = {
		result: Handlebars.compile($('#result-template').html()),
		selection: Handlebars.compile($('#selection-template').html()),
	};

	$('input.card').select2({
		placeholder: 'Search for a card',
		minimumInputLength: 1,
		formatResult: templates.result,
		formatSelection: templates.selection,

		// we do not want to escape markup since we are displaying html in results and selection
		escapeMarkup: function(html) {
			return html;
		},

		ajax: {
			cache: true,
			dataType: 'json',

			url: function(term) {
				return 'cards/' + term;
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

			if (value) {
				$.ajax('cards/' + value, {
					dataType: 'json',
					cache: true
				}).done(callback);
			}
		}
	});

});
