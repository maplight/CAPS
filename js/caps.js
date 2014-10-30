function filter_candidates_list() {
  $('#candidate_list').empty();

  $.each(candidates, function(key, value) {
    if (value != '') {
      if (value == 'Select candidate') {
        $('#candidate_list')
          .append($('<option></option>')
          .attr('value', value)
          .text(value)); 
      } else {
        if (value.indexOf($('#search_candidates').val().toUpperCase()) != -1) {
          $('#candidate_list')
            .append($('<option></option>')
            .attr('value', value)
            .text(value));
        }
      }
    }
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