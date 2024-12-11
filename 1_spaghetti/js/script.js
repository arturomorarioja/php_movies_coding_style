'use strict';
/**
 * API calls
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0.0 January 2019
 * @version 2.0.0 September 2019. Movie search added
 * @version 2.0.1 February 2023. Refactoring 
 */
$(function() {
    // Initial movie load
    loadMovies();

    $('#txtMovieSearch').on('input', () => {
        $('#lstMovies').empty();
        $('#lstMovies').change();   // It triggers button disabling
        $.ajax({
            url: 'api/',
            type: 'POST',
            data: {
                action: 'search',
                movie_search_text: $('#txtMovieSearch').val()
            },            
            success: (data) => {
                const MOVIE_ID = 0;
                const NAME = 1;
                const acMovies = JSON.parse(data);
                $.each(acMovies, (index, value) => {
                    $('#lstMovies').append(new Option(value[NAME], value[MOVIE_ID]));
                });
            }
        });
    });

    // Button enabling    
    $('#lstMovies').on('change', function() {
        if($(this).val() == null) {
            $('#btnModify').prop('disabled', 'disabled');
            $('#btnDelete').prop('disabled', 'disabled');
        } else {
            $('#btnModify').prop('disabled', '');
            $('#btnDelete').prop('disabled', '');
        }
    });

    // Modal button enabling
    $('#txtMovie').on('change', function() {
        if($(this).val() === '') {
            $('#btnOk').prop('disabled', 'disabled');
        } else {
            $('#btnOk').prop('disabled', '');
        }
    });

    // Add button clicked
    $('#btnAdd').click(() => {
        $('#txtMovie').val('');
        $('#modalCaption').text('Add Movie');
    });

    // Modify button clicked
    $('#btnModify').click(() => {
        $('#txtMovie').val($('#lstMovies option:selected').text());
        $('#modalCaption').text('Modify Movie');
    });

    // Delete button clicked
    $('#btnDelete').click(() => {
        if (window.confirm('The movie will be deleted. Are you sure?')) {
            $.ajax({
                url: 'api/',
                type: 'POST',
                data: {
                    action: 'delete',
                    movie_id: $('#lstMovies').val()
                },
                success: function(data) {
                    const RES_OK = 1;                    
                    const result = JSON.parse(data);

                    if (result == RES_OK) {
                        loadMovies();
                    } else {                        
                        alert('The movie could not be deleted');
                    }
                }
            });
        }
    });

    // Ok button clicked
    $('#btnOk').click(() => {
        if ($('#modalCaption').text() == 'Add Movie') {      // Add movie
            const data = {
                action: 'add',
                movie_name: $('#txtMovie').val()
            };

            $.post('api/', data, () => {
                $('#btnCancel').click();
                loadMovies();
            });
        } else {                                            // Modify movie
            const data = {
                action: 'modify',
                movie_id: $('#lstMovies').val(),
                movie_name: $('#txtMovie').val()
            };

            $.post('api/', data, () => {
                $('#btnCancel').click();
                loadMovies();
                $('#lstMovies').trigger('change');
            });
        }
    });
});

/**
 * Loads all the movies in the listBox in alphabetic order
 */
const loadMovies = () => {
    $('#lstMovies').empty();
    $.ajax({
        url: 'api/',
        type: 'POST',
        data: {action: 'load'},
        success: function(data) {
            const MOVIE_ID = 0;
            const NAME = 1;
            const acMovies = JSON.parse(data);
            $.each(acMovies, function(index, value) {
                $('#lstMovies').append(new Option(value[NAME], value[MOVIE_ID]));
            });
        }
    });
}