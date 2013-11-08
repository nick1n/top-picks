$(function() {

	$('input.card').select2({
		placeholder: 'Search for a card',
		minimumInputLength: 1,
		// dropdownCssClass: 'bigdrop', // apply css that makes the dropdown taller

		ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
			url: function(term) {
				return 'cards/' + term;
			},

			cache: true,

			dataType: 'json',

			results: function(data) {
				return {
					results: data
				};
			}
		},

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
		},

		formatResult: function(card) {
			var html =
				'<div>' +
					'<span class="badge mana">' + card.mana + '</span> ' +
				'</div>' +
				'<div>' +
					'<strong>' +
						card.name +
					'</strong>' +
				'</div>' +
				'<div>' +
					card.text +
				'</div>' +
				'<div>' +
					'<span class="badge attack">' + card.attack + '</span> <span class="badge health">' + card.health + '</span>' +
				'</div>';

			return html;
		},

		formatSelection: function(card) {
			return '<span class="badge mana">' + card.mana + '</span> ' + card.name;
		},

		// we do not want to escape markup since we are displaying html in results
		escapeMarkup: function(html) {
			return html;
		}
	});

});
