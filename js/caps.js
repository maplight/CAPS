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


function display_tooltip(event, tip_text, pos_x, pos_y, width) {
var tooltip_id = 0;
  switch (tooltip_id) {
    case 9: tip_text = 'This is the total amount received. The table below contains individual contributions.'; pos_x = -180; pos_y = 10; width = 160; break;
    case 10: tip_text = 'Download the search results as a CSV file.'; pos_x = -180; pos_y = 10; width = 160; break;
    case 11: tip_text = 'Show more columns in the table for additional information on contributors.'; pos_x = -180; pos_y = 10; width = 160; break;
    case 12: tip_text = 'This is the total amount received by candidate-controlled committees in the selected date range. The table below contains individual contributions.'; pos_x = -180; pos_y = 10; width = 160; break;
    case 13: tip_text = 'This is the total amount given by the specified contributors in the selected date range. The table below contains individual contributions.'; pos_x = -180; pos_y = 10; width = 160; break;
    case 14: tip_text = 'This is the total amount given towards the specified ballot measures. The table below contains individual contributions.'; pos_x = -180; pos_y = 10; width = 160; break;
  }

  if (window.event) {
    x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
  } else {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  
  document.getElementById('tooltip_text').innerHTML = tip_text;
  tip = document.getElementById('tooltip');
  x = x + pos_x;
  y = y + pos_y;
  tip.style.width = width + 'px';
  tip.style.top = y + 'px';
  tip.style.left = x + 'px';
  tip.style.display = 'block';
}