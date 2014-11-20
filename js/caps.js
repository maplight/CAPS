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


function display_tooltip(event, tooltip_id) {
  switch (tooltip_id) {
    case 1: tip_text = 'Search contributions from all contributors.'; pos_x = 20; pos_y = -20; width = 160; break;
    case 2: tip_text = 'Search contributions from a particular state.'; pos_x = 20; pos_y = -20; width = 160; break;
    case 3: tip_text = 'Search contributions to candidate campaigns only.'; pos_x = 20; pos_y = -20; width = 160; break;
    case 4: tip_text = 'Search contributions to a particular candidate\'s campaign(s).'; pos_x = 20; pos_y = -20; width = 160; break;
    case 5: tip_text = 'Search contributions to all candidates running for a particular office.'; pos_x = 20; pos_y = -20; width = 160; break;
    case 6: tip_text = 'Search contributions to committees formed to support or oppose ballot measures. Your results may return duplicate contributions if a contributor gave money to a committee supporting or opposing multiple ballot measures.'; pos_x = 20; pos_y = -20; width = 240; break;
    case 7: tip_text = 'Search contributions to other committees, such as candidate office holder and legal defense committees.'; pos_x = 20; pos_y = -20; width = 160; break;
    case 8: tip_text = 'Search contributions by the date range in which they were made.'; pos_x = 20; pos_y = -20; width = 160; break;
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