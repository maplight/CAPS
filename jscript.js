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
  } else {
    $('#candidates_list')
      .append($('<option></option>')
      .attr('value', 'ALL')
      .attr('selected', 'SELECTED')
      .text('-- All candidates')); 
  }

  $.each(options_array, function(key, value) {   
    $('#candidates_list')
      .append($('<option></option>')
      .attr('value', value)
      .text(value)); 
  });
}


function filter_propositions_list() {
  var options_array = propositions.slice(0);
  $('#propositions_list').empty();

  $('#propositions_list')
    .append($('<option></option>')
    .attr('value', 'ALL')
    .text('-- All propositions')); 

  if ($('#search_propositions').val().toUpperCase() != '') {
    options_array = filter_select(options_array, $('#search_propositions').val().toUpperCase());
  }

  $.each(options_array, function(key, value) {   
    $('#propositions_list')
      .append($('<option></option>')
      .attr('value', value)
      .text(value)); 
  });
}