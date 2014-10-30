function filter_select(select_array, filter_value) {
  for (var i = select_array.length; i--;) {
    if (select_array[i].indexOf(filter_value) == -1) select_array.splice(i, 1);
  }
  return select_array;
}


function filter_candidates_list() {
  var options_array = candidates.slice(0);
  $('#candidates_list').empty();

  if ($('#search_candidates').val().toUpperCase() != '') {
    options_array = filter_select(options_array, $('#search_candidates').val().toUpperCase());
  }

  $.each(options_array, function(key, value) {   
    $('#candidates_list')
      .append($('<option></option>')
      .attr('value', value)
      .text(value)); 
  });
}


function filter_propositions_list() {
  $('#propositions_list').empty();

  $.each(propositions, function(key, value) {
    if (value.length == 2) {
      if (value[0].substring(0, 3) == 'ALL') {
        $('#propositions_list')
          .append($('<option></option>')
          .attr('value', value[0])
          .text(value[1]));
      } else {
        if (value[0].indexOf($('#search_propositions').val().toUpperCase()) != -1) {
          $('#propositions_list')
            .append($('<option></option>')
            .attr('value', value[0])
            .text(value[1]));
        }
      }
    } 
  });
}