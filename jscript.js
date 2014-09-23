function filter_select(select_array, filter_value) {
  for (var i = select_array.length; i--;) {
    if (select_array[i].indexOf(filter_value) == -1) select_array.splice(i, 1);
  }
  return select_array;
}


function filter_candidates_list() {
  var options_array = candidates.slice(0);
  if ($('#search_candidates').val().toUpperCase() != '') {
    options_array = filter_select(options_array, $('#search_candidates').val().toUpperCase());
  }
  $('#candidates_list').empty();
  $.each(options_array, function(key, value) {   
    $('#candidates_list')
      .append($('<option></option>')
      .attr('value', value)
      .text(value)); 
  });
}